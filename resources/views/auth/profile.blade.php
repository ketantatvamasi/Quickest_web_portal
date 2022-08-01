@extends('layouts.app')
@section('title','Register')
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="profile-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <div class="modal-body p-3">
                    @if ($message = Session::get('new') || $user->status=='New')
                    <div class="alert alert-warning" role="alert">
                        <i class="dripicons-warning me-2"></i> Please fill below field
                    </div>
                    @endif
                        @if ($message = Session::get('pending') || $user->status=='Pending')
                            <div class="alert alert-success" role="alert">
                                <i class="dripicons-checkmark me-2"></i> You have registered successfully. Your account will we actived soon.
                            </div>
                        @endif

                    <form method="POST" id="profile-form" class="profile-form" action="{{ route('profile') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{$user->name }}" required autofocus
                                           placeholder="Enter your name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input id="company_name" type="text"
                                           class="form-control" name="company_name"
                                           value="{{$user->company_name }}" required
                                           placeholder="Enter your company name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mobile_no" class="form-label">Mobile no <span class="text-danger">*</span></label>
                                    <input id="mobile_no" type="text"
                                           class="form-control"
                                           name="mobile_no" value="{{$user->mobile_no }}" required
                                           placeholder="Enter your mobile no">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">Email address <span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{$user->email }}" required readonly
                                           placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" type="text"
                                              class="form-control" name="address"
                                              placeholder="Enter your address">{{$user->address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode</label>
                                    <input id="pincode" type="text"
                                           class="form-control"
                                           name="pincode" value="{{$user->pincode }}"
                                           placeholder="Enter your pincode">
                                </div>
                            </div>

                            <div class="col-md-6">
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

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                                    <select class="form-select" id="state_id" name="state_id" required="">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City <span class="text-danger">*</span></label>
                                    <select class="form-select" id="city_id" name="city_id" required="">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_category" class="form-label">Business Category</label>
                                    <select class="form-select" id="company_category" name="company_category">
                                        <option value="0">Choose</option>
                                        @foreach($company_categories as $bus_category)
                                            <option value="{{$bus_category->id}}" {{($bus_category->id==$user->company_category)?'selected' :''}}>{{$bus_category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website_link" class="form-label">Website link</label>
                                    <input id="website_link" type="text"
                                           class="form-control"
                                           name="website_link" value="{{$user->website_link }}"
                                           placeholder="Enter your website link">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="gst_no" class="form-label">Tax (GSTIN)</label>
                                    <input id="gst_no" type="text"
                                           class="form-control"
                                           name="gst_no" value="{{$user->gst_no }}"
                                           placeholder="Enter your tax number">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="profile_icon" class="form-label">Profile</label>
                                <input type="file" class="form-control" data-parsley-trigger="change" name="profile_icon"
                                       id="profile_icon" data-parsley-required="false"
                                       accept="'image/jpg,image/jpeg,image/png,image/PNG,image/Png"
                                       data-parsley-fileextension="jpg,png,jpeg" data-parsley-max-file-size="1024">
                            </div>

                        </div>

                    </form>
                </div>
                <div class="modal-footer pe-3">
                    {{--                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}

                    <button class="btn btn-primary" form="profile-form" type="submit" id="profile_button" @if (Session::get('pending') || $user->status=='Pending' || $user->status=='Approved') disabled @endif> Save</button>
                </div> <!-- end modal footer -->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->

{{--
--}}

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
            window.Parsley.addValidator('maxFileSize', {
                validateString: function (_value, maxSize, parsleyInstance) {
                    if (!window.FormData) {
                        alert('You are making all developpers in the world cringe. Upgrade your browser!');
                        return true;
                    }
                    var files = parsleyInstance.$element[0].files;
                    return files.length != 1 || files[0].size <= maxSize * 1024;
                },
                requirementType: 'integer',
                messages: {
                    en: 'This file should not be larger than %s Kb',
                    fr: 'Ce fichier est plus grand que %s Kb.'
                }
            });
            window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
                var tagslistarr = requirement.split(',');
                var fileExtension = value.split('.').pop();
                var arr = [];
                $.each(tagslistarr, function (i, val) {
                    arr.push(val);
                });
                if (jQuery.inArray(fileExtension, arr) != '-1') {
                    //console.log("is in array");
                    return true;
                } else {
                    //console.log("is NOT in array");
                    return false;
                }
            }, 32)
                .addMessage('en', 'fileextension', 'The extension should be jpeg, jpg, png allowed');

            formValition('#profile-form');
            $('.profile-form').on('submit',function(e) {
                e.preventDefault();
                if ( $(this).parsley().isValid() ) {
                    $.ajax({
                        async:false,
                        type : 'POST',
                        url  : '{{route('profile')}}',
                        contentType: false,
                        cache: false,
                        processData:false,
                        data : new FormData(this),
                        // data: $('.category-form').serialize(),
                        dataType: "json",
                        beforeSend: function(){
                            $("#profile_button").prop('disabled',true);
                            $("#profile_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success : function(data){
                            toastrSuccess('Successfully saved...','Success');
                            // $('#profile-modal').modal('toggle');
                            $("#profile_button").prop('disabled',true);
                            $("#profile_button").html('<i class="uil-arrow-circle-right"></i> Save');
                            window.location.reload();
                        },
                        error: function(xhr, status, error){
                            var errorMessage = xhr.status + ': ' + xhr.statusText
                            switch(xhr.status) {
                                case 401:
                                    toastrError('Error in saving...','Error');
                                    break;
                                case 422:
                                    toastrInfo('The category is invalid.','Info');
                                    break;
                                case 409:
                                    toastrInfo('Name already exist.','Warning');
                                    break;
                                default:
                                    toastrError('Error - ' + errorMessage,'Error');
                            }
                            $("#profile_button").prop('disabled',false);
                            $("#profile_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete:function(data){
                            $("#profile_button").html('Save');
                            $("#profile_button").prop('<i class="uil-arrow-circle-right"></i> disabled',false);
                        }
                    });
                }
            });

            openModal('#profile-modal', 'Update Profile', '#profile-form', '.modal-title', id = 0);
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
        })

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
