<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>travelo</title>
    <link rel="stylesheet" href="/travelo/assets/css/mdb.rtl.min.css">
    <link rel="stylesheet" href="/travelo/assets/fontawesome/css/all.min.css">
</head>

<body>
    <!-- Start your project here-->
    <style>
        @font-face {
            font-family: Hacen;
            src: url('/travelo/assets/Hacen Promoter Lt.ttf');
        }

        * {
            font-family: Hacen;
            font-size: 1rem;
        }

        body {
            background-color: #fbfbfb;
        }

        @media (min-width: 991.98px) {
            main {
                padding-right: 240px;
            }
        }

        @media print {
            main {
                width: 100%;
                padding: 20px;
            }
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            padding: 58px 0 0;
            /* Height of navbar */
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
            width: 240px;
            z-index: 600;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
            }
        }

        .sidebar .active {
            border-radius: 5px;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
            /* Scrollable contents if viewport is shorter than content. */
        }
    </style>
    <!--Main Navigation-->
    <header class="d-print-none">

        <!-- Navbar -->
        <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">

            <!-- Container wrapper -->
            <div class="container-fluid">
                <!-- Toggle button -->
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Collapsible wrapper -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Navbar brand -->
                    <a class="navbar-brand" href="#">
                        <img src="/travelo/assets/img/logo.png" height="30" alt="MDB Logo" loading="lazy" />
                        <h4 class="pt-2 px-1">لوحة التحكم</h4>
                    </a>

                </div>
                <!-- Collapsible wrapper -->

                <!-- Right elements -->
                <div class="d-flex align-items-center">

                    <!-- Avatar -->
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown" aria-expanded="false" class="text-center">
                            <i class="fas mx-1 fa-user-cog fa-fw me-3 text-dark" title="<?php echo $_SESSION['user']['name'] ? $_SESSION['user']['name'] : 'userFullName'; ?>" loading="lazy"></i>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                            <li>
                                <a class="dropdown-item" href="/travelo/index.php">
                                    <i class="fas fa-door-closed fa-fw me-3 mx-1 text-danger"></i><span>تسجيل خروج</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Right elements -->
            </div>
            <!-- Container wrapper -->
        </nav>
        <!-- Navbar -->

        <!-- Sidebar -->
        <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
            <div class="position-sticky">
                <div class="list-group list-group-flush mx-3 mt-4">
                    <a href="/travelo/dashboard.php" class="list-group-item list-group-item-action py-2 ripple bg-dark text-white" aria-current="true">
                        <i class="fas mx-1 fa-tachometer-alt fa-fw me-3"></i><span>التقارير</span>
                    </a>
                    <a href="/travelo/citys" class="list-group-item list-group-item-action py-2 ripple">
                        <i class="fas mx-1 fa-city fa-fw me-3"></i><span>المدن</span>
                    </a>
                    <a href="/travelo/seats" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-chair fa-fw me-3"></i><span>المقاعد</span></a>
                    <a href="/travelo/company" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-building fa-fw me-3"></i><span>الشركات</span></a>
                    <a href="/travelo/bus" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-bus fa-fw me-3"></i><span>البصات</span></a>
                    <a href="/travelo/trips" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-calendar fa-fw me-3"></i><span>الرحلات</span></a>
                    <a href="/travelo/bookings" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-money-bill fa-fw me-3"></i><span>الحجوزات</span></a>
                    <a href="/travelo/users" class="list-group-item list-group-item-action py-2 ripple"><i class="fas mx-1 fa-users fa-fw me-3"></i><span>المستخدمين</span></a>
                </div>
            </div>
        </nav>
        <!-- Sidebar -->

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main style="margin-top: 58px;">
        <div class="container pt-4">

            <script src="/travelo/assets/js/mdb.min.js"></script>