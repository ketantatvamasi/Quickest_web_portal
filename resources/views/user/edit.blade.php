
@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
@endpush
@section('content')
    <x-breadcrumbs pagename="USERS" pagetitle="EDIT_USER"/>
    <div class="card">
        <div class="card-body">
            <form method="POST"class="user-form" id="user-form">
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                <div class="row">
                    @if(!empty($user_list))
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User assign</label>
                            <select class="form-select" id="user_id" name="user_id" >
                                <option value="">Choose</option>
                                @foreach($user_list as $user_list)
                                    <option value="{{$user_list->id}}" {{($user_list->id==$user->user_id)?'selected' :''}}>{{$user_list->name}} - ({{$user_list->email}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ $user->name, 'titles'}}" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ $user->email, 'titles'}}" id="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="mobile_no" class="form-label">Mobile no <span class="text-danger">*</span></label>
                            <input id="mobile_no" type="text"
                                    class="form-control"
                                    name="mobile_no" value="{{$user->mobile_no }}" required
                                    placeholder="Enter your mobile no">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" type="text"
                                        class="form-control" name="address"
                                        placeholder="Enter your address">{{$user->address}}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select" id="country_id" name="country_id" required="">
                                <option value="">Choose</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" {{($country->id==$user->country_id)?'selected' :''}}>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-select" id="state_id" name="state_id" required="">
                                <option value="">Choose</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="city_id" class="form-label">City <span class="text-danger">*</span></label>
                            <select class="form-select" id="city_id" name="city_id" required="">
                                <option value="">Choose</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                            <input id="pincode" type="text"
                                    class="form-control"
                                    name="pincode" value="{{$user->pincode }}"
                                    placeholder="Enter your pincode" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" name="role" id="roles" required>
                                <option value="" >Select Role</option>
                                @foreach($roles as $key => $role)
                                    @if(!empty($user->role_id))
                                        <option value="{{$role->id}}" <?php if($role->id==$user->role_id){echo "selected='selected'";}?>>{{$role->name}}</option>
                                    @else
                                    <option value="{{$role->id}}" >{{$role->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tab-content">
                            <div class="row permissions_check_box">
                                @foreach($permissions as $key => $permission)
                                    <div class="col-lg-3">
                                        <div class="mt-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="data[{{$key}}][permission_id]" value="{{$permission->id}}" <?php if(in_array($permission->id, $user_permissions)){ echo 'checked';} ?> value="{{$permission->id}}" class="form-check-input" id="customCheck{{$permission->name}}">
                                                <label class="form-check-label" for="customCheck{{$permission->name}}">{{$permission->name}}</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                        <button type="submit" class="btn btn-primary float-end user_button" id="user_button">{{ __('Submit') }}</button>

            </form>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            formValition('#user-form');
            $('.user-form').on('submit', function (e) {
                e.preventDefault();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        async:false,
                        type: 'POST',
                        url: '{{route('user-update')}}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        dataType: "json",
                        beforeSend: function () {
                            $("#user_button").prop('disabled', true);
                            $("#user_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success: function (data) {
                            toastrSuccess('Successfully saved...', 'Success');
                            $("#user_button").prop('disabled', false);
                            $("#user_button").html('<i class="uil-arrow-circle-right"></i> Save');
                            $(location).attr("href", "{{route('user.index')}}");
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
                            $("#user_button").prop('disabled', false);
                            $("#user_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete: function (data) {
                            $("#user_button").html('Save');
                            $("#user_button").prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                        }
                    });
                }
            });

            "use strict";
            $(document).on('change','#roles',function () {
                var id = $(this).val();
                if(id=='') {
                    $('.permissions_check_box input:checkbox').prop('checked', false);
                    return false;
                }
                $.ajax({
                    async:false,
                    type: "GET",
                    url: "{{route('roles.show')}}",
                    data: {id:id},
                    dataType:"json",
                    success: function(res) {
                        $('.permissions_check_box input:checkbox').prop('checked', false);
                        $.each(res['data'], function(i, val){
                            $("input[value='" + val + "']").prop('checked', true);
                        });
                    }
                });
            });
            $('#country_id').on('change', function(e) {
                e.preventDefault();
                var country_id = jQuery(this).val();
                getStatesList(country_id);

            });

            $('#state_id').on('change', function(e) {
                e.preventDefault();
                var state_id = jQuery(this).val();
                getCityList(state_id);

            });
            getStatesList({{$user->country_id }},{{$user->state_id }});
            getCityList({{$user->state_id }},{{$user->city_id }});
        });

        // function get All States
        function getStatesList(country_id,seleted_id=0) {
            $.ajax({
                async:false,
                url:"{{url('get-states-by-country')}}",
                type: "POST",
                data: {
                    country_id: country_id
                },
                dataType : 'json',
                beforeSend: function () {
                    jQuery('select#state_id').find("option:eq(0)").html("Please wait..");
                },
                success: function(result){
                    var options = '';
                    options +='<option value="">Choose</option>';
                    $.each(result.states,function(key,value){
                        var selected = "";
                        if(value.id == seleted_id)
                            selected = 'selected';
                        options += '<option value="'+value.id+'" '+selected+'>'+value.name+'</option>';
                    });
                    $("#state_id").html(options);
                    $('#city_id').html('<option value="">Choose</option>');
                },
                complete: function () {
                    // code
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }

        // function get All Cities
        function getCityList(state_id,seleted_id=0) {
            $.ajax({
                async:false,
                url:"{{url('get-cities-by-state')}}",
                type: "POST",
                data: {
                    state_id: state_id
                },
                dataType : 'json',
                beforeSend: function () {
                    jQuery('select#city_id').find("option:eq(0)").html("Please wait..");
                },
                success: function(result){
                    var options = '';
                    options +='<option value="">Choose</option>';
                    $.each(result.cities,function(key,value){
                        var selected = "";
                        if(value.id == seleted_id)
                            selected = 'selected';
                        options += '<option value="' + value.id + '" '+selected+'>' + value.name + '</option>';

                    });
                    $("#city_id").html(options);
                },
                complete: function () {
                    // code
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    </script>
@endpush
