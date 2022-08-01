
@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <x-breadcrumbs pagename="USERS" pagetitle="EDIT_USER"/>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('user-create') }}">
                @csrf
                <div class="row"> 
                    <div class="col-auto">
                        <div class="mb-3">
                            <label for="formName" class="form-label">From Name</label>
                            <input type="text" name="formName" id="formName" class="form-control">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="mb-3">
                            <label for="field_type" class="form-label">Select field type</label>
                            <select class="form-select" id="field_type" name="field_type" required="">
                                <option value="">Choose</option>
                                <option value="textInput">Text Input</option>
                                <option value="dateInput">Date Input</option>
                                <option value="passwordInput">Password Input</option>
                                <option value="dropDownInput">DropDown Input</option>
                                <option value="textAreaInput">TextArea Input</option>
                                <option value="checkBoxInput">CheckBox Input</option>
                                <option value="radioButtonInput">RadioButton Input</option>
                                <option value="buttonInput">Button Input</option>
                                <option value="fileUploadInput">FileUpload Input</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <div class="textInput dynamicFields hideIntakeField" data-fieldName="textInput">
                            <div class="col-auto">
                                <b><span>Text Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dateInput dynamicFields hideIntakeField" data-fieldName="dateInput">
                            <div class="col-auto">
                                <b><span>Date Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="passwordInput dynamicFields hideIntakeField" data-fieldName="passwordInput">
                            <div class="col-auto">
                                <b><span>Password Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropDownInput dynamicFields hideIntakeField" data-fieldName="dropDownInput">
                            <div class="col-auto">
                                <b><span>Drop-Down Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <label for="placeholder" class="form-label">Option</label>
                                        <input type="text" name="placeholder" id="placeholder" class="form-control">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="mb-3">
                                        <label for="placeholder" class="form-label">Option Value</label>
                                        <input type="text" name="placeholder" id="placeholder" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="textAreaInput dynamicFields hideIntakeField" data-fieldName="textAreaInput">
                            <div class="col-auto">
                                <b><span>Textarea Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkBoxInput dynamicFields hideIntakeField" data-fieldName="checkBoxInput">
                            <div class="col-auto">
                                <b><span>Check-box Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="radioButtonInput dynamicFields hideIntakeField" data-fieldName="radioButtonInput">
                            <div class="col-auto">
                                <b><span>Radio-button Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="buttonInput dynamicFields hideIntakeField" data-fieldName="buttonInput">
                            <div class="col-auto">
                                <b><span>Button</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fileUploadInput dynamicFields hideIntakeField" data-fieldName="fileUploadInput">
                            <div class="col-auto">
                                <b><span>File Upload Input</span></b>
                                <hr>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="idName" class="form-label">Id / Name</label>
                                    <input type="text" name="idName" id="idName" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" name="label" id="label" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mb-3">
                                    <label for="placeholder" class="form-label">Placeholder</label>
                                    <input type="text" name="placeholder" id="placeholder" class="form-control">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">Required</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary ">{{ __('Submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>
    <!-- third party js -->
    <script async src="{{ asset('assets/js/vendor/apexcharts.min.js')}}"></script>
    <script async src="{{ asset('assets/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script async src="{{ asset('assets/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script async src="{{ asset('assets/js/pages/demo.dashboard.js')}}"></script>
    <!-- end demo js-->
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            "use strict";
            $('#field_type').on('change', function(e) {
                e.preventDefault();
                var field_type = jQuery(this).val();
                $(".dynamicFields").each(function(){
                    data_field = $(this).attr('data-fieldName');
                    if(field_type!=data_field){
                        $(this).addClass('hideIntakeField');
                    }
                });
                if(field_type=="textInput"){
                    $(".textInput").removeClass('hideIntakeField');
                }else if(field_type=="dateInput"){
                    $(".dateInput").removeClass('hideIntakeField');
                }else if(field_type=="passwordInput"){
                    $(".passwordInput").removeClass('hideIntakeField');
                }else if(field_type=="dropDownInput"){
                    $(".dropDownInput").removeClass('hideIntakeField');
                }else if(field_type=="textAreaInput"){
                    $(".textAreaInput").removeClass('hideIntakeField');
                }else if(field_type=="checkBoxInput"){
                    $(".checkBoxInput").removeClass('hideIntakeField');
                }else if(field_type=="radioButtonInput"){
                    $(".radioButtonInput").removeClass('hideIntakeField');
                }else if(field_type=="buttonInput"){
                    $(".buttonInput").removeClass('hideIntakeField');
                }else if(field_type=="fileUploadInput"){
                    $(".fileUploadInput").removeClass('hideIntakeField');
                }
            });
        });

    </script>
@endpush