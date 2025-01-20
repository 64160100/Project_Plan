<?php

namespace App\Http\Controllers;

use App\Models\StorageFileModel;
use App\Models\ListProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageFileController extends Controller
{
    public function index($project_id = null)
    {
        
        if ($project_id) {
            $files = StorageFileModel::where('Project_Id', $project_id)->get();
        } else {
            $files = StorageFileModel::all();
        }

        return view('StorageFiles.index', compact('files', 'project_id'));
    }

    public function view($id)
    {
        $file = StorageFileModel::findOrFail($id);
        
        if (Storage::disk('public')->exists($file->Path_Storage_File)) {
            return response()->file(storage_path('app/public/' . $file->Path_Storage_File));
        }
    
        return redirect()->back()->with('error', 'File not found');
    }

    public function store(Request $request)
    {   
        $maxFileSizeInMB = config('filesystems.max_upload_size', 20);
        $maxFileSizeInBytes = $maxFileSizeInMB * 1024 * 1024; 

        // ตรวจสอบ project_id
        $projectId = $request->input('project_id');
        if (!$projectId) {
            Log::error('File upload failed: Missing project ID', [
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Project ID is required for file upload.');
        }

        // ตรวจสอบว่าโครงการมีอยู่จริง
        $project = ListProjectModel::find($projectId);
        if (!$project) {
            Log::error('File upload failed: Invalid project ID', [
                'project_id' => $projectId
            ]);
            return redirect()->back()->with('error', 'Invalid project ID.');
        }

        if (!$request->hasFile('file')) {
            Log::warning('File upload request: No file present in the request', [
                'project_id' => $projectId,
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
                'project_id' => $projectId,
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
            ]);
            return redirect()->back()->with('error', 'File upload failed: ' . $errorMessage);
        }

        // ตรวจสอบขนาดไฟล์
        $fileSizeInMB = round($file->getSize() / (1024 * 1024), 2);
        if ($fileSizeInMB > $maxFileSizeInMB) {
            Log::warning('File size exceeds limit', [
                'project_id' => $projectId,
                'file_size' => $fileSizeInMB,
                'max_size' => $maxFileSizeInMB,
            ]);
            return redirect()->back()->with('error', "File size ({$fileSizeInMB} MB) exceeds the limit of {$maxFileSizeInMB} MB");
        }

        // ตรวจสอบประเภทไฟล์
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            Log::warning('Invalid file type', [
                'project_id' => $projectId,
                'mime_type' => $file->getMimeType()
            ]);
            return redirect()->back()->with('error', 'Invalid file type. Only PDF and image files are allowed.');
        }

        try {
            // สร้างชื่อโฟลเดอร์ตาม project_id
            $folderPath = 'uploads/project_' . $projectId;
            
            // สร้าง StorageFile record
            $storageFile = StorageFileModel::createFromUploadedFile($file, $folderPath, $projectId);
            
            if (!$storageFile) {
                throw new \Exception('Failed to create storage file record');
            }

            Log::info('File uploaded successfully', [
                'project_id' => $projectId,
                'file_name' => $storageFile->Name_Storage_File,
                'file_size' => $fileSizeInMB,
                'path' => $storageFile->Path_Storage_File
            ]);

            // ส่งกลับไปที่หน้าแสดงไฟล์ของโครงการนั้นๆ
            return redirect()->route('StorageFiles.index', ['project_id' => $projectId])
                ->with('success', "File uploaded successfully. Size: {$fileSizeInMB} MB");

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'project_id' => $projectId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'File upload failed: ' . $e->getMessage());
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