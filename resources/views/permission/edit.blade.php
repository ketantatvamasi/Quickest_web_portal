
@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')   
    <x-breadcrumbs pagename="PERMISSIONS" pagetitle="EDIT_PERMISSIONS"/>                   
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{ route('role-update') }}">
	                @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="tab-content">
                                    <div class="mb-3">
                                        <input type="hidden" name="id" value="{{$role->id}}" required>
                                        <label for="role" class="form-label">Role Name</label>
                                        <input type="text" id="role" name="role" value="{{ $role->name }}" class="form-control form-control-sm" placeholder="Role Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content">
                                    <div class="row">                                        
                                        @foreach($permissions as $key => $permission)
                                        <div class="col-lg-3">
                                            <div class="mt-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]" class="form-check-input" id="customCheck_{{$key}}" value="{{$key}}" @if(in_array($key, $role_permission)) checked @endif>
                                                    <label class="form-check-label" for="customCheck_{{$key}}">{{ $permission}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-11"></div>
                            <div class="col-lg-1  pull-right">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script async src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <!-- third party js -->
    <script async src="{{ asset('assets/js/vendor/apexcharts.min.js')}}"></script>
    <script async src="{{ asset('assets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script async src="{{ asset('assets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script async src="{{ asset('assets/js/pages/demo.dashboard.js')}}"></script>
    <!-- end demo js-->
    <script src="{{ asset('assets/js/vendor/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.print.min.js')}}"></script>
@endpush