<!-- ========== Left Sidebar Start ========== -->
<?php
$user_perm = PermissionCheck::check_permission('role-list');
?>
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="Quickest logo" height="64">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm.png')}}" alt="Quickest small logo" height="16">
        </span>
    </a>

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="Quickest logo" height="64">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm_dark.png')}}" alt="Quickest small logo" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title side-nav-item">Navigation</li>

            <li class="side-nav-item">
                <a href="{{url('dashboard')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    {{--<span class="badge bg-success float-end">4</span>--}}
                    <span> Dashboard </span>
                </a>
            </li>
            @if(in_array('follow-up-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{route('event.follow-up-history')}}" class="side-nav-link">
                    <i class="uil-stopwatch"></i>
                    {{--<span class="badge bg-success float-end">4</span>--}}
                    <span> Follow Up </span>
                </a>
            </li>
            @endif

            @if(in_array('unit-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('unit')}}" class="side-nav-link">
                    <i class="uil-cart"></i>
                    <span> Unit </span>
                </a>
            </li>
            @endif

            @if(in_array('gst-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('gst')}}" class="side-nav-link">
                    <i class="uil-cart"></i>
                    <span> GST </span>
                </a>
            </li>
            @endif

            @if(in_array('item-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('item')}}" class="side-nav-link">
                    <i class="uil-shopping-cart-alt"></i>
                    <span> Item </span>
                </a>
            </li>
            @endif

            @if(in_array('product-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('product')}}" class="side-nav-link">
                    <i class="uil-shopping-basket"></i>
                    <span> Product </span>
                </a>
            </li>
            @endif

            @if(in_array('testimonial-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('testimonial')}}" class="side-nav-link">
                    <i class="uil-shopping-basket"></i>
                    <span> Testimonial </span>
                </a>
            </li>
            @endif

            @if(in_array('country-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('country')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Country </span>
                </a>
            </li>
            @endif

            @if(in_array('state-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('state')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> State </span>
                </a>
            </li>
            @endif

            @if(in_array('city-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('city')}}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> City </span>
                </a>
            </li>
            @endif

            @if(in_array('customer-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{url('customer')}}" class="side-nav-link">
                    <i class="uil-user"></i>
                    <span> Customer </span>
                </a>
            </li>
            @endif

            @if(in_array('estimate-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{route('quotes.index')}}" class="side-nav-link">
                    <i class="uil-notebooks"></i>
                    <span> Estimates </span>
                </a>
            </li>
            @endif

            @if(in_array('user-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{route('user.index')}}" class="side-nav-link">
                    <i class="uil-user-square"></i>
                    <span> User </span>
                </a>
            </li>
            @endif

            @if(in_array('role-list', $user_perm) || auth()->user()->company_id==null)
            <li class="side-nav-item">
                <a href="{{route('role.index')}}" class="side-nav-link">
                    <i class="uil-user-square"></i>
                    <span> Roles </span>
                </a>
            </li>
            @endif

            <li class="side-nav-title side-nav-item mt-1">Settings</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#nav-settings" aria-expanded="false" aria-controls="nav-settings" class="side-nav-link collapsed">
                    <i class="uil-bright"></i>
                    <span> Templates </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="nav-settings" style="">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{route('proposal.index')}}">Proposal</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <!-- Help Box -->
{{--        <div class="help-box text-white text-center">--}}
{{--            <a href="javascript: void(0);" class="float-end close-btn text-white">--}}
{{--                <i class="mdi mdi-close"></i>--}}
{{--            </a>--}}
{{--            <img src="assets/images/help-icon.svg" height="90" alt="LogActivity Icon Image">--}}
{{--            <h5 class="mt-3">Unlimited Access</h5>--}}
{{--            <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>--}}
{{--            <a href="javascript: void(0);" class="btn btn-outline-light btn-sm">Upgrade</a>--}}
{{--        </div>--}}
        <!-- end Help Box -->
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
