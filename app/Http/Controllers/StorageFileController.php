<?php

namespace App\Http\Controllers;

use App\Models\StorageFileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageFileController extends Controller
{
    public function index()
    {
        $files = StorageFileModel::all();
        return view('StorageFiles.index', compact('files'));
    }

    public function view($id)
    {
        $file = StorageFileModel::findOrFail($id);
        
        if (Storage::disk('public')->exists($file->Path_Storage_File)) {
            return response()->file(storage_path('app/public/' . $file->Path_Storage_File));
        }
    
        return redirect()->back()->with('err3or', 'File not found');
    }

    public function store(Request $request)
    {   
        $maxFileSizeInMB = config('filesystems.max_upload_size', 20);
        $maxFileSizeInBytes = $maxFileSizeInMB * 1024 * 1024; 

        // ตรวจสอบว่ามีไฟล์ถูกส่งมาหรือไม่
        if (!$request->hasFile('file')) {
            Log::warning('File upload request: No file present in the request', [
                'request_data' => $request->all(),
                'files' => $request->allFiles(),
            ]);
            return redirect()->back()->with('error', 'No file was uploaded. Please select a file and try again.');
        }

        $file = $request->file('file');

        // ตรวจสอบว่าไฟล์ถูกอัปโหลดสำเร็จหรือไม่
        if (!$file->isValid()) {
            $errorCode = $file->getError();
            $errorMessage = $this->getUploadErrorMessage($errorCode);
            Log::error('File upload failed', [
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
            ]);
            return redirect()->back()->with('error', 'File upload failed: ' . $errorMessage);
        }

        // ตรวจสอบขนาดไฟล์
        $fileSizeInMB = round($file->getSize() / (1024 * 1024), 2);
        if ($fileSizeInMB > $maxFileSizeInMB) {
            Log::warning('File size exceeds limit', [
                'file_size' => $fileSizeInMB,
                'max_size' => $maxFileSizeInMB,
            ]);
            return redirect()->back()->with('error', "File size ({$fileSizeInMB} MB) exceeds the limit of {$maxFileSizeInMB} MB");
        }

        // ตรวจสอบประเภทไฟล์
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            Log::warning('Invalid file type', ['mime_type' => $file->getMimeType()]);
            return redirect()->back()->with('error', 'Invalid file type. Only PDF and image files are allowed.');
        }

        try {
            $storageFile = StorageFileModel::createFromUploadedFile($file, 'uploads');
            
            if (!$storageFile) {
                throw new \Exception('Failed to create storage file record');
            }

            Log::info('File uploaded successfully', [
                'file_name' => $storageFile->Name_Storage_File,
                'file_size' => $fileSizeInMB,
            ]);
            return redirect()->back()->with('success', "File uploaded successfully. Size: {$fileSizeInMB} MB");
        } catch (\Exception $e) {
            Log::error('File upload failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
        }
    }

    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    public function destroy($id)
    {
        $file = StorageFileModel::findOrFail($id);
        
        if (Storage::disk('public')->exists($file->Path_Storage_File)) {
            Storage::disk('public')->delete($file->Path_Storage_File);
        }

        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully');
    }

    public function download($id)
    {
        $file = StorageFileModel::findOrFail($id);
        
        if (Storage::disk('public')->exists($file->Path_Storage_File)) {
            return Storage::disk('public')->download(
                $file->Path_Storage_File, 
                $file->Name_Storage_File
            );
        }

        return redirect()->back()->with('error', 'File not found');
    }
}