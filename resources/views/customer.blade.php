@extends('layouts.app')
@section('title','Customer')
@push('styles')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/select.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <?php
    $user_perm = PermissionCheck::check_permission('role-list');
    ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <?php if(in_array('add-customer', $user_perm) || auth()->user()->company_id==null){  ?>
                        <a href="javascript:void(0);" class="btn btn-info btn-sm mb-2"
                       onclick="openModal('#customer-modal','Create Customer','#customer-form','.modal-title',id=0,flag=3)"><i
                            class="mdi mdi-plus-circle"></i> New</a>
                    <?php } ?>
                    <div class="dropdown btn-group mb-2">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="select_count">0</span>Bulk Action
                            {{--                                    <span class="badge badge-success-lighten" id="select_count">0</span> --}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-animated">
                            <a href="javascript:void(0);" class="dropdown-item active_status_all"><i
                                    class="mdi mdi-update"></i> Active All</a>
                            <a href="javascript:void(0);" class="dropdown-item deactive_status_all"><i
                                    class="mdi mdi-update"></i> Deactive All</a>
                            <a href="javascript:void(0);" class="dropdown-item delete_all"><i
                                    class="mdi mdi-delete-circle"></i> Delete All</a>
                        </div>
                    </div>
                </div>
                <div class="page-title-left pt-2">
                    {{--<h4 class="page-title">City</h4>--}}
                    <select class="form-select" id="fil_status" name="fil_status"
                            style="width: 200px;background-color: #fff0 !important;border: 0px solid #fff !important;font-size: 18px;margin: 0;white-space: nowrap;font-weight: 700;padding: 0.0rem 0.0rem 0rem 0.5rem;">
                        <option value="">All Customers</option>
                        <option value="0">Active Customers</option>
                        <option value="1">Deactive Customers</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                   {{-- <div class="row mb-2">
                        <div class="col-xl-7">
                            <div
                                class=" row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_name" class="visually-hidden">Name</label>
                                        <input type="text" class="form-control" id="fil_name" name="fil_name"
                                               placeholder="name...">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_type" class="me-2">Type</label>
                                        <select class="form-select" id="fil_type" name="fil_type">
                                            <option value="">Choose...</option>
                                            <option>Business</option>
                                            <option>Individual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_status" class="me-2">Status</label>
                                        <select class="form-select" id="fil_status" name="fil_status">
                                            <option value="">Choose...</option>
                                            <option value="0">Active</option>
                                            <option value="1">Deactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-5">
                            <div class="text-xl-end mt-xl-0 mt-2">
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2"
                                        id="resetFilter">
                                    <i class="mdi mdi-filter"></i> Reset Filters
                                </button>
                                <a href="javascript:void(0);" class="btn btn-info mb-2"
                                   onclick="openModal('#customer-modal','Create Customer','#customer-form','.modal-title',id=0,flag=3)"><i
                                        class="mdi mdi-plus-circle"></i> Add Customer</a>

                                <div class="dropdown btn-group mb-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="select_count">0</span>Bulk Action
                                        --}}{{--                                    <span class="badge badge-success-lighten" id="select_count">0</span> --}}{{--
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-animated">
                                        <a href="javascript:void(0);" class="dropdown-item active_status_all"><i
                                                class="mdi mdi-update"></i> Active All</a>
                                        <a href="javascript:void(0);" class="dropdown-item deactive_status_all"><i
                                                class="mdi mdi-update"></i> Deactive All</a>
                                        <a href="javascript:void(0);" class="dropdown-item delete_all"><i
                                                class="mdi mdi-delete-circle"></i> Delete All</a>
                                    </div>
                                </div>

                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->--}}
                    <table id="customer-datatable" class="table table-centered table-sm w-100 nowrap">
                        <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="select_all"></th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Pincode</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
    <div id="customer-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-center">
            <div class="modal-content">
                <div class="modal-header border-1 bg-light">
                    <h4 class="modal-title">Create Customer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ps-3 pe-3 customer-form" id="customer-form" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="form-label font-14">Type <span class="text-danger">*</span></h6>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="customer_type_business" name="customer_type"
                                               class="form-check-input" value="Business">
                                        <label class="form-check-label"
                                               for="customer_type_business">Business</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="customer_type_individual" name="customer_type"
                                               class="form-check-input" value="Individual" checked>
                                        <label class="form-check-label"
                                               for="customer_type_individual">Individual</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="name" name="name" required=""
                                           placeholder="Enter name" autofocus>
                                    <input class="form-control" type="hidden" id="id" name="id" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                           placeholder="Enter email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_no" class="form-label">Phone no <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="phone_no" name="phone_no"
                                           required="" placeholder="Enter phone no">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address"
                                              placeholder="Enter address"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pincode" class="form-label">Pincode</label>
                                    <input class="form-control" type="text" id="pincode" name="pincode"
                                           placeholder="Enter pincode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="country_id" name="country_id" required="">
                                        <option value="">Choose</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"
                                                    @if($country->id==101) selected @endif>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="state_id" name="state_id" required="">
                                        <option value="">Choose</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City</label>
                                    <select class="form-select" id="city_id" name="city_id">
                                        <option value="0">Choose</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="gst_no" class="form-label">GSTIN</label>
                                    <input class="form-control" type="text" id="gst_no" name="gst_no"
                                           placeholder="Enter gstin">
                                </div>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description"></textarea>
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close
                            </button>
                            <button class="btn btn-secondary" id="customer_button" type="submit"><i
                                    class="uil-arrow-circle-right"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>

    <!-- third party js -->
    @include('layouts.partials.datatable-script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js')}}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    {{--    <script src="{{ asset('assets/js/pages/demo.datatable-init.js')}}"></script>--}}
    <!-- end demo js-->
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            "use strict";
            var table = $("#customer-datatable").DataTable({
                // dom: 'Bfrtip',
                dom:
                    "<'row'<'col-sm-12 col-md-6 text-left'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                responsive: false,
                scrollX: !0,
                processing: true,
                serverSide: true,
                stateSave: true,
                lengthChange: !1,
                buttons: [
                    {
                        extend: 'pageLength',
                        attr: {
                            class: 'btn btn-light buttons-collection dropdown-toggle buttons-page-length',
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="mdi mdi-file-pdf-box fs-4"></i>',
                        attr: {
                            title: 'PDF',
                            class: 'btn btn-light buttons-html5 buttons-pdf',
                        },
                        title: 'Customer List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="mdi mdi-microsoft-excel fs-4"></i>',
                        attr: {
                            title: 'Excel',
                            class: 'btn btn-light buttons-html5 buttons-excel',
                        },
                        title: 'Customer List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="mdi mdi-format-list-bulleted fs-4"></i>',
                        attr: {
                            title: 'Column visibility',
                            class: 'btn btn-light buttons-collection dropdown-toggle buttons-colvis',
                        },
                        title: 'Customer List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                stateSaveParams: function (settings, data) {
                    data.fil_status = $('#fil_status').val();
                    data.fil_type = $('#fil_type').val();
                    data.fil_name = $('#fil_name').val();
                },
                stateLoadParams: function (settings, data) {
                    $('#fil_status').val(data.fil_status);
                    $('#fil_type').val(data.fil_type);
                    $('#fil_name').val(data.fil_name);
                },
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem(settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem(settings.sInstance))
                },
                ajax: {
                    url: "{{ route('customer.index') }}",
                    data: function (d) {
                        d.status = $('#fil_status').val(),
                            d.customer_type = $('#fil_type').val(),
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                "order": [[1, "asc"]],
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox form-check-input" data-id="' + row.action + '">';
                        }
                    },
                    {data: 'customer_type', name: 'customer_type', orderable: true},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email', 'visible': false},
                    {data: 'phone_no', name: 'phone_no'},
                    {data: 'address', name: 'address'},
                    {data: 'pincode', name: 'pincode'},
                    {data: 'country_name', name: 'country_name'},
                    {data: 'state_name', name: 'state_name'},
                    {data: 'city_name', name: 'city_name', 'visible': false},
                    {data: 'description', name: 'description', 'visible': false},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            var fun_status = "change_status('" + row.action + "', 1,'{{route('customer.edit-status')}}','#customer-datatable')";
                            if (data == 0)
                                return '<span class="badge badge-success-lighten" onclick="' + fun_status + '">Active</span>';
                            else {
                                fun_status = "change_status('" + row.action + "', 0,'{{route('customer.edit-status')}}','#customer-datatable')";
                                return '<span class="badge badge-danger-lighten" onclick="' + fun_status + '">Deactive</span>';
                            }

                        }
                    },
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {

                            var edit_fun = "edit_id('" + row.action + "')";
                            var delete_fun = "remove_id('" + row.action + "','{{route('customer.delete')}}','#customer-datatable')";
                            return '<div class="invoice-action">' +
                                '<a href="javascript:void(0)" class="action-icon mr-1" id="edit_' + row.action + '" onclick="' + edit_fun + '">' +
                                '<i class="mdi mdi-square-edit-outline"></i>' +
                                '</a>' +
                                '<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="' + delete_fun + '">' +
                                '<i class="mdi mdi-delete"></i>' +
                                '</a>' +
                                '</div>';
                        }
                    },
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            table.buttons().container().appendTo("#customer-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
                pagingType: "full_numbers",
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            })

            $('#fil_status,#fil_name,#fil_type').change(function () {
                table.draw();
            });

            $('#resetFilter').click(function () {
                $('input[type=text]').val('');
                $('#fil_status').val('');
                $('#fil_type').val('');
                table
                    .search('')
                    .columns().search('')
                    .draw();
            });

            formValition('#customer-form');
            $('.customer-form').on('submit', function (e) {
                e.preventDefault();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: '{{route('customer.store')}}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        dataType: "json",
                        beforeSend: function () {
                            $("#customer_button").prop('disabled', true);
                            $("#customer_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success: function (data) {
                            toastrSuccess('Successfully saved...', 'Success');
                            $('#customer-modal').modal('toggle');
                            table.ajax.reload();
                            $("#customer_button").prop('disabled', false);
                            $("#customer_button").html('<i class="uil-arrow-circle-right"></i> Save');
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
                                    toastrInfo('Phone no already exist.', 'Warning');
                                    break;
                                default:
                                    toastrError('Error - ' + errorMessage, 'Error');
                            }
                            $("#customer_button").prop('disabled', false);
                            $("#customer_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete: function (data) {
                            $("#customer_button").html('Save');
                            $("#customer_button").prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                        }
                    });
                }
            });
        });

        $(function () { //DOM Ready
            getStatesList(101);
        });

        function edit_id(id) {
            $.ajax({
                async: false,
                type: "GET",
                url: "{{route('customer.show')}}",
                data: {id: id},
                dataType: "json",
                success: function (res) {
                    resetFormValidation("#customer-form");
                    resetForm("#customer-form");
                    $('#id').val(res.data.id);
                    $('#name').val(res.data.name);
                    $('#email').val(res.data.email);
                    $('#phone_no').val(res.data.phone_no);
                    $('#address').val(res.data.address);
                    $('#pincode').val(res.data.pincode);
                    $('#country_id').val(res.data.country_id);
                    $('#gst_no').val(res.data.gst_no);
                    getStatesList(res.data.country_id, res.data.state_id);
                    getCityList(res.data.state_id, res.data.city_id);
                    $("input[name=customer_type][value=" + res.data.customer_type + "]").prop('checked', true);

                    $('#description').val(res.data.description);

                    $('.modal-title').text('Edit Customer');
                    $('#customer-modal').modal('toggle');
                }
            });
        }

        //Remove multiple record
        $('.delete_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values, '{{route('customer.delete')}}', '#customer-datatable');
        });

        $('.active_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 0, '{{route('customer.edit-status')}}', '#customer-datatable');
        });

        $('.deactive_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 1, '{{route('customer.edit-status')}}', '#customer-datatable');
        });

        $('#country_id').on('change', function (e) {
            e.preventDefault();
            var country_id = jQuery(this).val();
            getStatesList(country_id);

        });

        $('#state_id').on('change', function (e) {
            e.preventDefault();
            var state_id = jQuery(this).val();
            getCityList(state_id);

        });

        // function get All States
        function getStatesList(country_id, seleted_id = 0) {
            $.ajax({
                async: false,
                url: "{{url('get-states-by-country')}}",
                type: "POST",
                data: {
                    country_id: country_id
                },
                dataType: 'json',
                beforeSend: function () {
                    jQuery('select#state_id').find("option:eq(0)").html("Please wait..");
                },
                success: function (result) {
                    var options = '';
                    options += '<option value="">Choose</option>';
                    $.each(result.states, function (key, value) {
                        var selected = "";
                        if (value.id == seleted_id)
                            selected = 'selected';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';
                    });
                    $("#state_id").html(options);
                    $('#city_id').html('<option value="0">Choose</option>');
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
        function getCityList(state_id, seleted_id = 0) {
            $.ajax({
                async: false,
                url: "{{url('get-cities-by-state')}}",
                type: "POST",
                data: {
                    state_id: state_id
                },
                dataType: 'json',
                beforeSend: function () {
                    jQuery('select#city_id').find("option:eq(0)").html("Please wait..");
                },
                success: function (result) {
                    var options = '';
                    options += '<option value="0">Choose</option>';
                    $.each(result.cities, function (key, value) {
                        var selected = "";
                        if (value.id == seleted_id)
                            selected = 'selected';
                        options += '<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>';

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
