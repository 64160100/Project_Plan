<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ระบบติดตามแผนงาน | Lib Buu</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('Bootslander/assets/vendor/bootstrap/css/custom.css')); ?>">

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
            urls: ["<?php echo e(asset('kaiadmin/assets/css/fonts.min.css')); ?>"],
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

        .noit-detail {
            padding: 2px;
            padding-left: 8px;
            margin: 4px;
            width: 293px;
            font-size: 13px
        }

        [id] {
        scroll-margin-top: 80px;
        }

        .link-project {
            color: black;
            text-decoration: none;
            font-size: 14px;
        }

        .link-project:hover {
            color:rgb(128, 18, 223);;
            text-decoration: none;
            font-size: 14px;
        }

    </style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo e(asset('kaiadmin/assets/css/bootstrap.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('kaiadmin/assets/css/plugins.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('kaiadmin/assets/css/kaiadmin.min.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('kaiadmin/assets/css/kaiadmin.css')); ?>" />
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
                        <img src="<?php echo e(asset('images/logo_BUU_LIB.png')); ?>" alt="navbar brand" class="navbar-brand"
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
                        <?php
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
                        ?>

                        <!-- Check for project management permissions -->
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Dashborad === 'Y' || $permission->List_Project === 'Y' ||
                        $permission->Track_Status === 'Y'): ?>
                        <?php $projectManagement = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($projectManagement): ?>
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการโครงการ</h4>
                        </li>
                        <?php endif; ?>

                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Dashborad === 'Y' && !$renderedItems['dashboard']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                                <i class='bx bxs-grid-alt'></i>
                                <p>แดชบอร์ด</p>
                            </a>
                        </li>
                        <?php $renderedItems['dashboard'] = true; ?>
                        <?php endif; ?>
                        <?php if($permission->List_Project === 'Y' && !$renderedItems['list_project']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('project')); ?>">
                                <i class='bx bx-folder bx-flip-horizontal'></i>
                                <p>รายการโครงการ</p>
                            </a>
                        </li>
                        <?php $renderedItems['list_project'] = true; ?>
                        <?php endif; ?>
                        <?php if($permission->Track_Status === 'Y' && !$renderedItems['track_status']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('status.tracking')); ?>">
                                <i class='bx bx-time-five bx-flip-horizontal'></i>
                                <p>ติดตามสถานะ</p>
                            </a>
                        </li>
                        <?php $renderedItems['track_status'] = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Check for approval permissions -->
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Documents_Project === 'Y' || $permission->Report_results === 'Y' ||
                        $permission->Check_Budget === 'Y' || $permission->Approval_Project === 'Y'): ?>
                        <?php $approval = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($approval): ?>
                        <li class="nav-section">
                            <h4 class="text-section">การอนุมัติ</h4>
                        </li>
                        <?php endif; ?>

                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Documents_Project === 'Y' && empty($renderedItems['documents_project'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('PlanDLC.allProject')); ?>">
                                <i class='bx bx-file bx-flip-horizontal'></i>
                                <p>รวบรวมเอกสารโครงการ</p>
                            </a>
                        </li>
                        <?php $renderedItems['documents_project'] = true; ?>
                        <?php endif; ?>

                        <?php if($permission->Report_results === 'Y' && empty($renderedItems['report_results'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('PlanDLC.report')); ?>">
                                <i class='bx bx-task'></i>
                                <p>รายงานผล</p>
                            </a>
                        </li>
                        <?php $renderedItems['report_results'] = true; ?>
                        <?php endif; ?>

                        <?php if($permission->Check_Budget === 'Y' && empty($renderedItems['check_budget'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('PlanDLC.checkBudget')); ?>">
                                <i class='bx bxs-calculator bx-flip-horizontal'></i>
                                <p>ตรวจสอบงบประมาณ</p>
                            </a>
                        </li>
                        <?php $renderedItems['check_budget'] = true; ?>
                        <?php endif; ?>

                        <?php if($permission->Approval_Project === 'Y' && empty($renderedItems['approval_project']) && session('employee')->IsAdmin != 'Y'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('requestApproval')); ?>">
                                <i class='bx bx-select-multiple'></i>
                                <?php if(session('pendingApprovalsCount') > 0): ?>
                                <div class="red-dot"></div>
                                <?php endif; ?>
                                <p>อนุมัติโครงการ</p>
                            </a>
                        </li>
                        <?php $renderedItems['approval_project'] = true; ?>
                        <?php endif; ?>

                        <?php if($permission->Propose_Project === 'Y' && empty($renderedItems['propose_project'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('proposeProject')); ?>">
                                <i class='bx bx-food-menu'></i>
                                <?php if(session('statusNCount') > 0): ?>
                                <div class="red-dot"></div>
                                <?php endif; ?>
                                <p>เสนอโครงการเพื่อพิจารณา</p>
                            </a>
                        </li>
                        <?php $renderedItems['propose_project'] = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Check for system management permissions -->
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Manage_Users === 'Y' || $permission->Data_Employee === 'Y' ||
                        $permission->Setup_System === 'Y'): ?>
                        <?php $systemManagement = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($systemManagement): ?>
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการระบบ</h4>
                        </li>
                        <?php endif; ?>

                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($permission->Manage_Users === 'Y' && !$renderedItems['manage_users']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('account.user')); ?>">
                                <i class='bx bx-group'></i>
                                <p>จัดการผู้ใช้งาน</p>
                            </a>
                        </li>
                        <?php $renderedItems['manage_users'] = true; ?>
                        <?php endif; ?>
                        <?php if($permission->Data_Employee === 'Y' && !$renderedItems['data_employee']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('account.employee')); ?>">
                                <i class='bx bx-group'></i>
                                <p>ข้อมูลพนักงาน</p>
                            </a>
                        </li>
                        <?php $renderedItems['data_employee'] = true; ?>
                        <?php endif; ?>
                        <?php if($permission->Setup_System === 'Y' && !$renderedItems['setup_system']): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('setting')); ?>">
                                <i class='bx bx-cog'></i>
                                <p>ตั้งค่าระบบ</p>
                            </a>
                        </li>
                        <?php $renderedItems['setup_system'] = true; ?>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <img src="<?php echo e(asset('images/logo_BUU_LIB.png')); ?>" alt="navbar brand" class="navbar-brand"
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
                                    <i class="bx bx-search-alt-2"></i>
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
                              <!-- แจ้งเตือน -->
                              <?php if(session('employee')): ?>
                                <?php
                                    $isResponsibleOnly = session('employee')->IsManager === 'N' && 
                                                        session('employee')->IsDirector === 'N' && 
                                                        session('employee')->IsBoard === 'N' && 
                                                        session('employee')->IsFinance === 'N' && 
                                                        session('employee')->IsAdmin === 'N';
                                ?>
                                <?php if(session('employee')->IsAdmin === 'Y' || $isResponsibleOnly || session('employee')->IsDirector === 'Y'): ?>
                                <!-- แจ้งสถานะ(จดหมาย) -->
                                    <li class="nav-item topbar-icon dropdown hidden-caret">
                                        <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php
                                                $color = session('statusNCount') > 0 ? '#ff0000' : 'inherit';
                                            ?>
                                            <i class='bx bx-envelope me-1' style='color: <?php echo e($color); ?>'></i>
                                            <span style='color: <?php echo e($color); ?>'><?php echo e(session('statusNCount')); ?></span>
                                        </a>
                                        <ul class="dropdown-menu animated fadeIn" style="max-height: 500px; width: 300px; overflow-y: auto; overflow-x: hidden;">
                                            <?php
                                                \Carbon\Carbon::setLocale('th');
                                                $groupedHistories = collect(session('recordHistories', [])) ->groupBy(function($history) {
                                                        return \Carbon\Carbon::parse($history->Time_Record)->format('Y-m-d');
                                                    }) ->sortKeysDesc();
                                            ?>
                                            <?php if(count($groupedHistories) > 0): ?>
                                                <?php $__currentLoopData = $groupedHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $histories): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="noitAppove" style="font-weight: bold; background-color:#e6faff;">
                                                    <?php echo e(\Carbon\Carbon::parse($date)->addYears(543)->translatedFormat('d F พ.ศ. Y')); ?>

                                                </li>
                                                    <?php
                                                        $groupedByProject = $histories
                                                            ->where('Status_Record', 'N')
                                                            ->where('approvals.Status', 'N')
                                                            ->groupBy('approvals.project.Id_Project')
                                                            ->map(function ($group) {
                                                                return $group->max('Time_Record');
                                                            });
                                                    ?>
                                                <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $latestTimeRecord = $groupedByProject[$history->approvals->project->Id_Project] ?? null;
                                                    ?>
                                                    <li class="noitAppove noitAppove-detail"
                                                        style="<?php echo e(($history->Status_Record === 'N' && $history->approvals->Status === 'N' && $history->Time_Record === $latestTimeRecord) ? 'background-color:#ffc4c4;' : ''); ?>">
                                                        <a>
                                                            <b style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; width: 260px;">
                                                                <?php echo e($history->approvals->project->Name_Project); ?>

                                                            </b>
                                                            <?php echo e($history->comment); ?> <br>(โดย:<?php echo e($history->Permission_Record); ?>) <br>
                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <li style="text-align: center;">
                                                    ไม่มีข้อมูล
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if(session('pendingApprovalsCount', 0) > 0 && !$isResponsibleOnly && session('employee')->IsAdmin != 'Y'): ?>
                                <!-- "มี" รายการรออนุมัติ -->
                                    <li class="nav-item topbar-icon dropdown hidden-caret">
                                        <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bxs-bell-ring me-1' style='color:#ff0000'></i>
                                            <span style='color:#ff0000'><?php echo e(session('pendingApprovalsCount')); ?></span>
                                        </a>
                                        <ul class="dropdown-menu animated fadeIn" style="max-height: 500px; width: 300px; overflow-y: auto; overflow-x: hidden;" >
                                            <li class="noit-detail" style="white-space: normal; width: 300px;">
                                                รายการโครงการรอการอนุมัติ (<?php echo e(session('pendingApprovalsCount')); ?>)
                                            </li>
                                            <ul >
                                                <?php $__currentLoopData = session('projectIds', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $projectId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li >
                                                        <a class="link-project" href="<?php echo e(route('requestApproval', ['projectId' => $projectId])); ?>#<?php echo e($projectId); ?>" 
                                                            style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; width: 260px;" >
                                                            <?php echo e(session('projectNames', [])[$index] ?? 'Unknown Project'); ?>

                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </ul>
                                    </li>
                                <?php elseif(session('employee')->IsAdmin === 'Y' || $isResponsibleOnly): ?>
                                    <!-- ไม่แสดง -->
                                <?php else: ?>
                                <!-- "ไม่มี" รายการรออนุมัติ -->
                                    <li class="nav-item topbar-icon dropdown hidden-caret">
                                        <a class="nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bxs-bell-ring me-1'></i><span>0</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- โปรไฟล์ -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="profile-container">
                                        <div class="avatar-sm">
                                            <img src="<?php echo e(asset('images/profile.jpg')); ?>" alt="..."
                                                class="avatar-img rounded-circle" />
                                        </div>
                                        <span class="profile-username">
                                            <span class="op-7">Hi,</span>
                                            <span class="fw-bold">
                                                <?php echo e(session('employee') ? session('employee')->Firstname_Employee . ' ' . session('employee')->Lastname_Employee : 'Guest'); ?><br>
                                                <?php if(session('permissions')): ?>
                                                (<?php echo e(implode(', ', session('permissions')->pluck('Name_Permission')->toArray())); ?>)
                                                <?php endif; ?>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="avatar-sm-only">
                                        <div class="avatar-sm">
                                            <img src="<?php echo e(asset('images/profile.jpg')); ?>" alt="..."
                                                class="avatar-img rounded-circle" />
                                        </div>
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
                                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                                style="display: none;">
                                                <?php echo csrf_field(); ?>
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
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>

        </div>
        <!-- End Navbar -->

    </div>

    <!--   Core JS Files   -->
    <script src="<?php echo e(asset('kaiadmin/assets/js/core/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('kaiadmin/assets/js/core/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('kaiadmin/assets/js/core/bootstrap.min.js')); ?>"></script>

    <!-- jQuery Scrollbar -->
    <script src="<?php echo e(asset('kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')); ?>"></script>

    <!-- Kaiadmin JS -->
    <script src="<?php echo e(asset('kaiadmin/assets/js/kaiadmin.min.js')); ?>"></script>
</body>

</html><?php /**PATH /var/www/resources/views/navbar/app.blade.php ENDPATH**/ ?>