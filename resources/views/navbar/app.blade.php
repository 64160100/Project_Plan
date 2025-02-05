



<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ระบบติดตามแผนงาน | Lib Buu</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

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
                    <!-- conten in sidebar -->
                    <ul class="nav nav-secondary">

                        <!-- การจัดการโครงการ -->
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการโครงการ</h4>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">

                                <i class='bx bxs-grid-alt'></i>
                                <p>แดชบอร์ด</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('project') }}">
                                <i class='bx bx-folder bx-flip-horizontal'></i>
                                <p>รายการโครงการ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class='bx bx-time-five bx-flip-horizontal'></i>
                                <p>ติดตามสถานะ</p>
                            </a>
                        </li>

                        <!-- การอนุมัติ -->
                        <li class="nav-section">
                            <h4 class="text-section">การอนุมัติ</h4>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class='bx bx-file bx-flip-horizontal'></i>
                                <p>รวบรวมเอกสารโครงการ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class='bx bx-task'></i>
                                <p>รายงานผล</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class='bx bxs-calculator bx-flip-horizontal' undefined></i>
                                <p>ตรวจสอบงบประมาณ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route ('requestApproval') }}">
                                <i class='bx bx-select-multiple'></i>
                                <p>อนุมัติโครงการ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class='bx bx-food-menu'></i>
                                <p>เสนอโครงการเพื่อพิจารณา</p>
                            </a>
                        </li>

                        <!-- การจัดการระบบ -->
                        <li class="nav-section">
                            <h4 class="text-section">การจัดการระบบ</h4>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.user') }}">
                                <i class='bx bx-group'></i>
                                <p>จัดการผู้ใช้งาน</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('account.employee') }}">
                                <i class='bx bx-group'></i>
                                <p>ข้อมูลพนักงาน</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('setting') }}">
                                <i class='bx bx-cog'></i>
                                <p>ตั้งค่าระบบ</p>
                            </a>
                        </li>
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
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <i class='bx bx-bell'></i>
                                </a>
                            </li>

                            <!-- โปรไฟล์ -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{asset('images/profile.jpg')}}" alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">Hizrian</span>
                                    </span>
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
                                            <a class="dropdown-item" href="#">Logout</a>
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