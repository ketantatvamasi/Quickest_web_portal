<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="" height="64">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm.png')}}" alt="" height="16">
        </span>
    </a>

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="" height="64">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm_dark.png')}}" alt="" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">Navigation</li>

            <li class="side-nav-item">
                <a href="{{route('admin.dashboard')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span class="badge bg-success float-end">4</span>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('admin.client.index')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Client </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{route('admin.business-category.index')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Business Category </span>
                </a>
            </li>
{{--            <li class="side-nav-item">--}}
{{--                <a href="{{route('admin.user.index')}}" class="side-nav-link">--}}
{{--                    <i class="uil-calender"></i>--}}
{{--                    <span> Users </span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                    <i class="uil-user-square"></i>
                    <span> Permissions </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarEcommerce">
                    <ul class="side-nav-second-level">
{{--                        <li>--}}
{{--                            <a href="{{route('admin.role.index')}}">Roles</a>--}}
{{--                        </li>--}}
                        <li>
                            <a href="{{route('admin.permission.index')}}">Permission</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#setting" aria-expanded="false" aria-controls="setting" class="side-nav-link">
                    <i class="uil-user-square"></i>
                    <span> Settings </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="setting">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{route('admin.form-customize.index')}}">Intake Form builder</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
