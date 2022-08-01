
@extends('layouts.app')
@section('title',Config::get('constants.EDIT_ROLE'))
@push('styles')
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <x-breadcrumbs pagename="ROLE" pagetitle="EDIT_ROLE"/>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="role-form" id="role-form" method="POST">
	                @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="tab-content">
                                    <div class="mb-3">
                                        <input type="hidden" name="id" value="{{$role->id}}" required>
                                        <label for="name" class="form-label">Role Name</label>
                                        <input type="text" id="role" name="name" value="{{ $role->name }}" class="form-control" placeholder="Role Name">
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
                                                        <input type="checkbox" name="data[{{$key}}][permission_id]"
                                                               class="form-check-input"
                                                               id="customCheck{{$permission['name']}}" @if(in_array($permission['id'], $role_permissions)) checked @endif
                                                               value="{{$permission['id']}}">
                                                        <label class="form-check-label"
                                                               for="customCheck{{$permission['name']}}">{{ $permission['name']}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                                <button type="submit" class="btn btn-primary float-end role_button" id="role_button">{{ __('Submit') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script>
        $(document).ready(function () {
            formValition('#role-form');
            $('.role-form').on('submit', function (e) {
                e.preventDefault();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: '{{route('role-update')}}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        dataType: "json",
                        beforeSend: function () {
                            $("#role_button").prop('disabled', true);
                            $("#role_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success: function (data) {
                            toastrSuccess('Successfully saved...', 'Success');
                            $("#role_button").prop('disabled', false);
                            $("#role_button").html('<i class="uil-arrow-circle-right"></i> Save');
                            $(location).attr("href", "{{route('role.index')}}");
                            // goBack();
                        },
                        error: function (xhr, status, error) {
                            var errorMessage = xhr.status + ': ' + xhr.statusText
                            switch (xhr.status) {
                                case 401:
                                    toastrError('Error in saving...', 'Error');
                                    break;
                                case 422:
                                    toastrInfo('The category is invalid.', 'Info');
                                    break;
                                case 409:
                                    toastrInfo('Name already exist.', 'Warning');
                                    break;
                                default:
                                    toastrError('Error - ' + errorMessage, 'Error');
                            }
                            $("#role_button").prop('disabled', false);
                            $("#role_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete: function (data) {
                            $("#role_button").html('Save');
                            $("#role_button").prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
