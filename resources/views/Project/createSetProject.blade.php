@extends('navbar.app')


<hade>
    <link rel="stylesheet" href="{{ asset('css/createSetProject.css') }}">
</hade>


@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-0">จัดการชุดโครงการ</h4>
        </div>
        <div class="card-body">
            <!-- Project Selection Section -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">เลือกโครงการเพื่อสร้างชุด</h5>
                </div>
                <div class="card-body">
                    <div class="projects-list">
                        @foreach($projects as $project)
                        @php
                        $isAssigned = $projectBatchRelations->contains('Project_Id', $project->Id_Project);
                        @endphp

                        @if(!$isAssigned)
                        <div class="project-item">
                            <div class="form-check">
                                <input class="form-check-input project-checkbox" type="checkbox"
                                    value="{{ $project->Id_Project }}" id="project-{{ $project->Id_Project }}">
                                <label class="form-check-label" for="project-{{ $project->Id_Project }}">
                                    <div class="project-info">
                                        <div class="project-name">{{ $project->Name_Project }}</div>
                                        <div class="department-name">
                                            {{ $project->employee->department->Name_Department ?? 'ไม่ระบุหน่วยงาน' }}
                                        </div>
                                        <div class="project-details">
                                            <span class="budget">
                                                <i class='bx bx-wallet-alt'></i>
                                                งบประมาณ: {{ $project->Status_Budget === 'Y' ? '500,000 บาท' : '-' }}
                                            </span>
                                            <span class="start-date">
                                                <i class='bx bxs-calendar-event'></i>
                                                วันที่เริ่ม: {{ $project->First_Time }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <!-- Form Type Selection -->
                    <div class="form-type-selector mb-4">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="formType" id="newBatchBtn" autocomplete="off"
                                checked>
                            <label class="btn btn-outline-primary" for="newBatchBtn">
                                <i class='bx bx-layer-plus'></i> สร้างชุดโครงการใหม่
                            </label>

                            <input type="radio" class="btn-check" name="formType" id="existingBatchBtn"
                                autocomplete="off">
                            <label class="btn btn-outline-success" for="existingBatchBtn">
                                <i class='bx bx-folder-plus'></i> เพิ่มในชุดโครงการที่มีอยู่
                            </label>
                        </div>
                    </div>

                    <!-- Form Container -->
                    <div id="formContainer">
                        <!-- New Batch Form -->
                        <div id="newBatchForm" class="batch-form">
                            <form id="batchForm" action="{{ route('project-batches.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="batchName" class="form-label">ชื่อชุดโครงการ</label>
                                    <input type="text" class="form-control" id="batchName" name="batch_name" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">โครงการที่เลือก</label>
                                    <div id="selectedProjects" class="selected-projects"></div>
                                </div>

                                <input type="hidden" name="project_ids" id="projectIds">

                                <div class="button-container">
                                    <button type="submit" class="btn btn-primary" disabled id="submitBatch">
                                        <i class='bx bx-layer-plus'></i> สร้างชุดโครงการ
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Add to Existing Batch Form -->
                        <div id="existingBatchForm" class="batch-form" style="display:none;">
                            <form id="addToBatchForm" method="POST" action="{{ route('project-batches.addProjects') }}">
                                @csrf
                                <div class="form-group mb-4">
                                    <label for="existingBatch" class="form-label">เลือกชุดโครงการ</label>
                                    <select class="form-control" id="existingBatch" name="Id_Project_Batch" required>
                                        <option value="">-- เลือกชุดโครงการ --</option>
                                        @foreach($projectBatchRelations->groupBy('batch.Id_Project_Batch') as $batchId
                                        => $relations)
                                        <option value="{{ $batchId }}">{{ $relations->first()->batch->Name_Batch }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">โครงการที่เลือก</label>
                                    <div id="selectedProjectsForExisting" class="selected-projects"></div>
                                </div>

                                <input type="hidden" name="project_ids" id="projectIdsForExisting">

                                <div class="button-container">
                                    <button type="submit" class="btn btn-success" disabled id="submitAddToBatch">
                                        <i class='bx bx-plus-circle'></i> เพิ่มในชุดโครงการ
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">ชุดโครงการที่สร้างแล้ว</h5>
                </div>
                <div class="card-body">
                    <div class="batches-list">
                        @foreach($projectBatchRelations->groupBy('batch.Name_Batch') as $batchName => $relations)
                        @php
                        // Filter out relations with Count_Steps_Batch set to 1
                        $filteredRelations = $relations->filter(function ($relation) {
                        return $relation->Count_Steps_Batch != 1;
                        });
                        @endphp
                        @if($filteredRelations->isNotEmpty())
                        <div class="batch-item">
                            <div class="batch-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    {{ $batchName }}
                                    <small>({{ $filteredRelations->count() }} โครงการ)</small>
                                </h5>
                                <form
                                    action="{{ route('project-batches.removeBatch', ['batch_id' => $filteredRelations->first()->Project_Batch_Id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบชุดโครงการนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class='bx bx-trash'></i> ลบชุดโครงการ
                                    </button>
                                </form>
                            </div>
                            <div class="batch-projects">
                                @foreach($filteredRelations as $index => $relation)
                                <div class="batch-project-item d-flex justify-content-between align-items-center">
                                    <div class="project-name">
                                        {{ $index + 1 }}. {{ $relation->project->Name_Project }}
                                    </div>
                                    <form
                                        action="{{ route('project-batches.removeProject', ['batch_id' => $relation->Project_Batch_Id, 'project_id' => $relation->Project_Id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบโครงการนี้ออกจากชุดโครงการ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class='bx bx-trash'></i> ลบ
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            <div class="button-container mt-3">
                                <form
                                    action="{{ route('projects.submitForApproval', ['id' => $filteredRelations->first()->project->Id_Project]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @foreach($filteredRelations as $relation)
                                    <input type="hidden" name="project_ids[]"
                                        value="{{ $relation->project->Id_Project }}">
                                    @endforeach
                                    <button type="submit" class="btn btn-primary">
                                        <i class='bx bx-log-in-circle'></i> เสนอเพื่อพิจารณา
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const projectCheckboxes = document.querySelectorAll('.project-checkbox');
    const newBatchBtn = document.getElementById('newBatchBtn');
    const existingBatchBtn = document.getElementById('existingBatchBtn');
    const newBatchForm = document.getElementById('newBatchForm');
    const existingBatchForm = document.getElementById('existingBatchForm');
    const selectedProjectsDiv = document.getElementById('selectedProjects');
    const selectedProjectsExistingDiv = document.getElementById('selectedProjectsForExisting');
    const projectIdsInput = document.getElementById('projectIds');
    const projectIdsExistingInput = document.getElementById('projectIdsForExisting');
    const submitBatchButton = document.getElementById('submitBatch');
    const submitAddToBatchButton = document.getElementById('submitAddToBatch');
    const batchForm = document.getElementById('batchForm');
    const addToBatchForm = document.getElementById('addToBatchForm');
    const batchNameInput = document.getElementById('batchName');
    const existingBatchSelect = document.getElementById('existingBatch');

    // State
    let selectedProjects = new Set();

    function updateSelectedProjects(isExisting = false) {
        const targetDiv = isExisting ? selectedProjectsExistingDiv : selectedProjectsDiv;
        const targetInput = isExisting ? projectIdsExistingInput : projectIdsInput;
        const targetButton = isExisting ? submitAddToBatchButton : submitBatchButton;

        targetDiv.innerHTML = '';
        const projectArray = Array.from(selectedProjects);

        projectArray.forEach(projectId => {
            const checkbox = document.getElementById(`project-${projectId}`);
            if (checkbox) {
                const projectInfo = checkbox.nextElementSibling.querySelector('.project-info')
                    .cloneNode(true);
                const projectDiv = document.createElement('div');
                projectDiv.className = 'batch-project-item';
                projectDiv.appendChild(projectInfo);
                targetDiv.appendChild(projectDiv);
            }
        });

        targetInput.value = projectArray.join(',');

        // Update button state based on conditions
        if (isExisting) {
            targetButton.disabled = projectArray.length === 0 || !existingBatchSelect.value;
        } else {
            targetButton.disabled = projectArray.length === 0 || !batchNameInput.value.trim();
        }
    }

    function toggleForms(showNew) {
        newBatchForm.style.display = showNew ? 'block' : 'none';
        existingBatchForm.style.display = showNew ? 'none' : 'block';

        // Reset form fields
        if (showNew) {
            batchNameInput.value = '';
        } else {
            existingBatchSelect.selectedIndex = 0;
        }

        // Reset selections only if checkboxes are clicked
        projectCheckboxes.forEach(cb => cb.checked = false);
        selectedProjects.clear();

        // Update both forms
        updateSelectedProjects(true);
        updateSelectedProjects(false);
    }

    // Event Listeners
    projectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedProjects.add(this.value);
            } else {
                selectedProjects.delete(this.value);
            }
            updateSelectedProjects(existingBatchBtn.checked);
        });
    });

    // Form type toggle listeners
    newBatchBtn.addEventListener('change', () => toggleForms(true));
    existingBatchBtn.addEventListener('change', () => toggleForms(false));

    // Batch name input listener
    batchNameInput.addEventListener('input', () => updateSelectedProjects(false));

    // Existing batch select listener
    existingBatchSelect.addEventListener('change', () => updateSelectedProjects(true));

    // New batch form submission
    batchForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!batchNameInput.value.trim()) {
            alert('กรุณากรอกชื่อชุดโครงการ');
            return;
        }

        if (selectedProjects.size === 0) {
            alert('กรุณาเลือกโครงการอย่างน้อย 1 โครงการ');
            return;
        }

        projectIdsInput.value = Array.from(selectedProjects).join(',');
        this.submit();
    });

    // Existing batch form submission
    addToBatchForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!existingBatchSelect.value) {
            alert('กรุณาเลือกชุดโครงการ');
            return;
        }

        if (selectedProjects.size === 0) {
            alert('กรุณาเลือกโครงการอย่างน้อย 1 โครงการ');
            return;
        }

        projectIdsExistingInput.value = Array.from(selectedProjects).join(',');
        this.submit();
    });

    // Initialize state
    toggleForms(true);
});
</script>
@endsection