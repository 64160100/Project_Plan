@extends('navbar.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>statusTracking</title>
    <link rel="stylesheet" href="{{ asset('css/statusTracking.css') }}">
</head>

<body>
@section('content')
    <h2>
        <a href="{{ url()->previous() }}" style="color: inherit; text-decoration: none;">
            <i class='bx bx-left-arrow-alt' style="cursor: pointer;"></i>
        </a>ติดตามสถานะโครงการ
    </h2>

    <div class="container">
        @foreach($projects as $project)
        <div class="project-card">
            <div class="project-header">
                <div>
                    <h2 class="project-title">{{ $project->Name_Project }}</h2>
                    <p class="project-subtitle">{{ $project->departmentName }}</p>
                </div>
                <div class="status-badge">{{ $project->status }}</div>
            </div>

            <div class="project-info">
                <div class="info-item">
                    <span class="info-icon">📅</span>
                    <div>
                        <div class="info-label">วันที่เริ่ม</div>
                        <div class="info-value">{{ $project->formattedFirstTime }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">👤</span>
                    <div>
                        <div class="info-label">ผู้รับผิดชอบ</div>
                        <div class="info-value">{{ $project->employeeName }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-icon">💰</span>
                    <div>
                        <div class="info-label">งบประมาณ</div>
                        <div class="info-value">{{ $project->budget }} บาท</div>
                    </div>
                </div>
            </div>

            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-label">ความคืบหน้า</span>
                    <span class="progress-value">{{ round(($project->Count_Steps / 9) * 100) }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ ($project->Count_Steps / 9) * 100 }}%;"></div>
                </div>
            </div>

            <div class="details-link">
                <span>สถานะปัจจุบัน : {{ $project->current_status }}</span>
                <a href="{{ route('project.details', ['Id_Project' => $project->Id_Project]) }}">
                    <span>รายละเอียด →</span>
                </a>
            </div>

        </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
@endsection
</body>