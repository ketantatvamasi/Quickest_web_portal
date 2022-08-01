@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <x-breadcrumbs pagename="PERMISSIONS" pagetitle="ADD_PERMISSIONS"/>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{ route('admin.permission-create') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="permissions_name" class="form-label">Permission Name</label>
                            <input type="text" id="permissions_name" name="permissions_name"
                                   class="form-control" placeholder="Permission Name">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputEmail3">Assigned to Role</label>
                            <select class="select2 form-control select2-multiple" name="roles[]"
                                    data-toggle="select2" multiple="multiple"
                                    data-placeholder="Choose role...">
                                @foreach($roles as $key => $role)
                                    <option value="{{$role->id}}">{{$role->role_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script async src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>

@endpush
