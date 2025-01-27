<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ระบบติดตามแผนงาน | Lib Buu</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Fonts and icons -->
    <script>
    WebFont.load({
        google: {
            families: ["Sarabun:100,200,300,400,500,600,700,800,900"]
        },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons",
            ],
            urls: ["{{asset('kaiadmin/assets/css/fonts.min.css')}}"],
        },
        active: function() {
            sessionStorage.fonts = true;
        },
    });
    </script>

    <style>
    .nav-item .nav-link {
        position: relative;
    }

    .nav-item .nav-link .red-dot {
        position: absolute;
        top: 7px;
        left: 43px;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
    }

    .noitAppove {
        padding: 4px;
        padding-left: 16px;
        margin: 4px;
        width: 293px;
        font-size: 13px
    }

    .noitAppove-detail {
        white-space: normal;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('kaiadmin/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('kaiadmin/assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('kaiadmin/assets/css/kaiadmin.min.css')}}" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <div class="wrapper sidebar_minimize">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="white">
                    <a href="#" class="logo">
                        <img src="{{asset('images/logo_BUU_LIB.png')}}" alt="navbar brand" class="navbar-brand"
                            height="50" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>

                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>

                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <!-- content in sidebar -->
                    <ul class="nav nav-secondary">
                        @php
                        $permissions = session('permissions', []);
                        $projectManagement = false;
                        $approval = false;
                        $systemManagement = false;
                        $renderedItems = [
                        'dashboard' => false,
                        'list_project' => false,
                        'track_status' => false,
                        'documents_project' => false,
                        'report_results' => false,
                        'check_budget' => false,
                        'approval_project' => false,
                        'manage_users' => false,
                        'data_employee' => false,
                        'setup_system' => false,
                        ];
                        @endphp

                        <!-- Check for project management permissions -->
                        @foreach($permissions as $permission)
                        @if($permission->Dashborad === 'Y' || $permission->List_Project === 'Y' ||
                        $permission->Track_Status === 'Y')
                        @php $projectManagement = true; @endphp
                        @endif
                        @endforeach

                        @if($projectManagement)
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการโครงการ</h4>
                        </li>
                        @endif

                        @foreach($permissions as $permission)
                        @if($permission->Dashborad === 'Y' && !$renderedItems['dashboard'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class='bx bxs-grid-alt'></i>
                                <p>แดชบอร์ด</p>
                            </a>
                        </li>
                        @php $renderedItems['dashboard'] = true; @endphp
                        @endif
                        @if($permission->List_Project === 'Y' && !$renderedItems['list_project'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('project') }}">
                                <i class='bx bx-folder bx-flip-horizontal'></i>
                                <p>รายการโครงการ</p>
                            </a>
                        </li>
                        @php $renderedItems['list_project'] = true; @endphp
                        @endif
                        @if($permission->Track_Status === 'Y' && !$renderedItems['track_status'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('status.tracking') }}">
                                <i class='bx bx-time-five bx-flip-horizontal'></i>
                                <p>ติดตามสถานะ</p>
                            </a>
                        </li>
                        @php $renderedItems['track_status'] = true; @endphp
                        @endif
                        @endforeach

                        <!-- Check for approval permissions -->
                        @foreach($permissions as $permission)
                        @if($permission->Documents_Project === 'Y' || $permission->Report_results === 'Y' ||
                        $permission->Check_Budget === 'Y' || $permission->Approval_Project === 'Y')
                        @php $approval = true; @endphp
                        @endif
                        @endforeach

                        @if($approval)
                        <li class="nav-section">
                            <h4 class="text-section">การอนุมัติ</h4>
                        </li>
                        @endif

                        @foreach($permissions as $permission)
                        @if($permission->Documents_Project === 'Y' && empty($renderedItems['documents_project']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('PlanDLC.allProject') }}">
                                <i class='bx bx-file bx-flip-horizontal'></i>
                                <p>รวบรวมเอกสารโครงการ</p>
                            </a>
                        </li>
                        @php $renderedItems['documents_project'] = true; @endphp
                        @endif

                        @if($permission->Report_results === 'Y' && empty($renderedItems['report_results']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('PlanDLC.report') }}">
                                <i class='bx bx-task'></i>
                                <p>รายงานผล</p>
                            </a>
                        </li>
                        @php $renderedItems['report_results'] = true; @endphp
                        @endif

                        @if($permission->Check_Budget === 'Y' && empty($renderedItems['check_budget']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('PlanDLC.checkBudget') }}">
                                <i class='bx bxs-calculator bx-flip-horizontal'></i>
                                <p>ตรวจสอบงบประมาณ</p>
                            </a>
                        </li>
                        @php $renderedItems['check_budget'] = true; @endphp
                        @endif

                        @if($permission->Approval_Project === 'Y' && empty($renderedItems['approval_project']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('requestApproval') }}">
                                <i class='bx bx-select-multiple'></i>
                                @if(session('pendingApprovalsCount') > 0)
                                <div class="red-dot"></div>
                                @endif
                                <p>อนุมัติโครงการ</p>
                            </a>
                        </li>
                        @php $renderedItems['approval_project'] = true; @endphp
                        @endif

                        @if($permission->Propose_Project === 'Y' && empty($renderedItems['propose_project']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('proposeProject') }}">
                                <i class='bx bx-food-menu'></i>
                                @if(session('statusNCount') > 0)
                                <div class="red-dot"></div>
                                @endif
                                <p>เสนอโครงการเพื่อพิจารณา</p>
                            </a>
                        </li>
                        @php $renderedItems['propose_project'] = true; @endphp
                        @endif
                        @endforeach

                        <!-- Check for system management permissions -->
                        @foreach($permissions as $permission)
                        @if($permission->Manage_Users === 'Y' || $permission->Data_Employee === 'Y' ||
                        $permission->Setup_System === 'Y')
                        @php $systemManagement = true; @endphp
                        @endif
                        @endforeach

                        @if($systemManagement)
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการระบบ</h4>
                        </li>
                        @endif

                        @foreach($permissions as $permission)
                        @if($permission->Manage_Users === 'Y' && !$renderedItems['manage_users'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.user') }}">
                                <i class='bx bx-group'></i>
                                <p>จัดการผู้ใช้งาน</p>
                            </a>
                        </li>
                        @php $renderedItems['manage_users'] = true; @endphp
                        @endif
                        @if($permission->Data_Employee === 'Y' && !$renderedItems['data_employee'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.employee') }}">
                                <i class='bx bx-group'></i>
                                <p>ข้อมูลพนักงาน</p>
                            </a>
                        </li>
                        @php $renderedItems['data_employee'] = true; @endphp
                        @endif
                        @if($permission->Setup_System === 'Y' && !$renderedItems['setup_system'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('setting') }}">
                                <i class='bx bx-cog'></i>
                                <p>ตั้งค่าระบบ</p>
                            </a>
                        </li>
                        @php $renderedItems['setup_system'] = true; @endphp
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <!-- Navbar -->
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="white">
                        <a href="#" class="logo">
                            <img src="{{asset('images/logo_BUU_LIB.png')}}" alt="navbar brand" class="navbar-brand"
                                height="50" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>

                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">

                                        <i class='bx bx-search-alt-2'></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                    aria-expanded="false" aria-haspopup="true">
                                    <i class="fa fa-search"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-search animated fadeIn">
                                    <form class="navbar-left navbar-form nav-search">
                                        <div class="input-group">
                                            <input type="text" placeholder="Search ..." class="form-control" />
                                        </div>
                                    </form>
                                </ul>
                            </li>

                            <!-- แจ้งเตือน -->
                            @if(session('employee'))
                            @if(session('employee')->IsAdmin === 'Y')
                            <!-- แจ้งสถานะ(จดหมาย)แอดมิน -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-envelope'></i>
                                </a>
                                <ul class="dropdown-menu animated fadeIn"
                                    style="max-height: 500px; width: 300px; overflow-y: auto; overflow-x: hidden;">
                                    @php
                                    \Carbon\Carbon::setLocale('th');
                                    $groupedHistories = collect(session('recordHistories', []))
                                    ->groupBy(function($history) {
                                    return \Carbon\Carbon::parse($history->Time_Record)->format('Y-m-d');
                                    }) ->sortKeysDesc();
                                    @endphp
                                    @if(count($groupedHistories) > 0)
                                    @foreach($groupedHistories as $date => $histories)
                                    <li class="noitAppove" style="font-weight: bold; background-color:#e6faff;">
                                        {{ \Carbon\Carbon::parse($date)->addYears(543)->translatedFormat('d F พ.ศ. Y') }}
                                    </li>
                                    @foreach($histories as $history)
                                    <!-- <li class="noitAppove noitAppove-detail"> -->
                                    <li class="noitAppove noitAppove-detail"
                                        style="{{ $history->Status_Record === 'N' && $history->approvals->Status === 'N' ? 'background-color:ffc4c4;' : '' }}">
                                        <b
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $history->approvals->project->Name_Project }}
                                        </b>
                                        {{ $history->comment }} <br>(โดย:{{ $history->Permission_Record }})
                                    </li>
                                    @endforeach
                                    @endforeach
                                    @else
                                    <li style="text-align: center;">
                                        ไม่มีข้อมูล
                                    </li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            <!-- แจ้งสถานะทั่วไป -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                @if(session('pendingApprovalsCountForEmployee', 0) > 0)
                                <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-envelope'></i>
                                </a>
                                <ul class="dropdown-menu animated fadeIn"
                                    style="max-height: 500px; width: 300px; overflow-y: auto; overflow-x: hidden;">
                                    @php
                                    \Carbon\Carbon::setLocale('th');
                                    $groupedHistories = collect(session('recordHistories', []))
                                    ->groupBy(function($history) {
                                    return \Carbon\Carbon::parse($history->Time_Record)->format('Y-m-d');
                                    }) ->sortKeysDesc();
                                    @endphp
                                    @if(count($groupedHistories) > 0)
                                    @foreach($groupedHistories as $date => $histories)
                                    <li class="noitAppove" style="font-weight: bold; background-color:#e6faff;">
                                        {{ \Carbon\Carbon::parse($date)->addYears(543)->translatedFormat('d F พ.ศ. Y') }}
                                    </li>
                                    @foreach($histories as $history)
                                    <li class="noitAppove noitAppove-detail">
                                        <b
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $history->approvals->project->Name_Project }}
                                        </b>
                                        {{ $history->comment }} <br>(โดย:{{ $history->Permission_Record }})
                                    </li>
                                    @endforeach
                                    @endforeach
                                    @else
                                    <li style="text-align: center;">
                                        ไม่มีข้อมูล
                                    </li>
                                    @endif
                                </ul>
                                @else
                                @if(session('pendingApprovalsCount', 0) > 0 )
                                <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bxs-bell-ring me-1' style='color:#ff0000'></i>
                                    <span style='color:#ff0000'>{{ session('pendingApprovalsCount') }}</span>
                                </a>
                                <ul class="dropdown-menu animated fadeIn">
                                    <li class="dropdown-item" style="white-space: normal; width: 300px;">
                                        <a href="{{ route('requestApproval') }}" style="color:#000;">
                                            รายการโครงการรอการอนุมัติ ({{ session('pendingApprovalsCount') }})
                                        </a>
                                    </li>
                                </ul>
                                @else
                                <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bxs-bell-ring me-1'></i>
                                    <span>{{ session('pendingApprovalsCount', 0) }}</span>
                                </a>
                                @endif
                                @endif
                            </li>
                            @endif

                            <!-- โปรไฟล์ -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="profile-container"
                                        style="background: linear-gradient(180deg, #8729DA 0%, #AC2BDD 100%); border: 1px solid #ccc; padding: 10px 20px; border-radius: 5px; display: flex; align-items: center; color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 250px; margin-left: auto; margin-right: -20px;">
                                        <div class="avatar-sm" style="margin-right: 10px;">
                                            <img src="{{ asset('images/profile.jpg') }}" alt="..."
                                                class="avatar-img rounded-circle" />
                                        </div>
                                        <span class="profile-username">
                                            <span class="op-7">Hi,</span>
                                            <span class="fw-bold">
                                                {{ session('employee') ? session('employee')->Firstname_Employee . ' ' . session('employee')->Lastname_Employee : 'Guest' }}<br>
                                                @if(session('permissions'))
                                                ({{ implode(', ', session('permissions')->pluck('Name_Permission')->toArray()) }})
                                                @endif
                                            </span>
                                        </span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">My Profile</a>
                                            <a class="dropdown-item" href="#">My Balance</a>
                                            <a class="dropdown-item" href="#">Inbox</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Account Setting</a>
                                            <div class="dropdown-divider"></div>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <!-- Main Content -->
            <div class="main p-3">
                <main class="py-4 pt-5 mt-5">
                    @yield('content')
                </main>
            </div>

        </div>
        <!-- End Navbar -->

    </div>

    <!--   Core JS Files   -->
    <script src="{{asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('kaiadmin/assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('kaiadmin/assets/js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{asset('kaiadmin/assets/js/kaiadmin.min.js')}}"></script>
</body>

</html>