@extends('navbar.app')

@section('content')
<div class="container">

    <h2>
        <a href="{{ url()->previous() }}" style="color: inherit; text-decoration: none;">
            <i class='bx bx-left-arrow-alt' style="cursor: pointer;"></i>
        </a>
        Storage Files
    </h2>

    <form action="{{ route('StorageFiles.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="form-group">
            <input type="file" name="file" id="fileInput" class="form-control-file" accept=".pdf,image/*">
            <small class="form-text text-muted">Allowed file types: PDF, Images (JPG, PNG, GIF, etc.). Max size: 10
                MB</small>
        </div>
        <div id="fileInfo" class="mt-2"></div>
        <button type="submit" class="btn btn-primary mt-2">Upload File</button>
    </form>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file->Name_Storage_File }}</td>
                <td>{{ $file->Type_Storage_File }}</td>
                <td>{{ $file->getHumanReadableSize() }}</td>
                <td>
                    <a href="{{ route('StorageFiles.view', $file->Id_Storage_File) }}" target="_blank"
                        class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('StorageFiles.download', $file->Id_Storage_File) }}"
                        class="btn btn-sm btn-success">Download</a>
                    <form action="{{ route('StorageFiles.destroy', $file->Id_Storage_File) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(event) {
    var file = event.target.files[0];
    var fileInfo = document.getElementById('fileInfo');
    if (file) {
        var fileSize = (file.size / (1024 * 1024)).toFixed(2);
        var fileType = file.type;
        var isValid = (fileType === 'application/pdf' || fileType.startsWith('image/')) && file.size <= 10 *
            1024 * 1024;

        fileInfo.innerHTML = 'File: ' + file.name + '<br>Size: ' + fileSize + ' MB<br>Type: ' + fileType;
        fileInfo.style.color = isValid ? 'green' : 'red';

        if (!isValid) {
            fileInfo.innerHTML +=
                '<br><strong>Error: File must be a PDF or image and not exceed 10 MB.</strong>';
        }
    } else {
        fileInfo.innerHTML = '';
    }
});

document.getElementById('uploadForm').onsubmit = function(event) {
    var fileInput = document.getElementById('fileInput');
    var file = fileInput.files[0];
    if (file) {
        var fileType = file.type;
        var fileSize = file.size / (1024 * 1024);
        if ((fileType !== 'application/pdf' && !fileType.startsWith('image/')) || fileSize > 10) {
            event.preventDefault();
            alert('Please upload only PDF or image files, not exceeding 10 MB.');
        }
    }
};
</script>

@endsection