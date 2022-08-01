@extends('layouts.admin')
@section('title','Client')
@push('styles')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/select.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Client List</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Client</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-8">
                        <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <label for="fil_name" class="visually-hidden">Name</label>
                                    <input type="text" class="form-control" id="fil_name" name="fil_name"
                                           placeholder="name...">
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <label for="fil_status" class="me-2">Status</label>
                                    <select class="form-select" id="fil_status" name="fil_status">
                                        <option value="">Choose...</option>
                                        <option value="Pending">Pending</option>
                                        <option value="New">New</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Reject">Reject</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-xl-end mt-xl-0 mt-2">
                            <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2"
                                    id="resetFilter">
                                <i class="mdi mdi-filter"></i> Reset Filters
                            </button>
{{--                            <a href="javascript:void(0);" class="btn btn-info mb-2"--}}
{{--                               onclick="openModal('#client-modal','Create Client','#client-form','.modal-title',id=0)"><i--}}
{{--                                    class="mdi mdi-plus-circle"></i> Add Client</a>--}}

                            <div class="dropdown btn-group mb-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="select_count">0</span>Bulk Action

                                </button>
                                <div class="dropdown-menu dropdown-menu-animated">
{{--                                    <a href="javascript:void(0);" class="dropdown-item active_status_all"><i--}}
{{--                                            class="mdi mdi-update"></i> Active All</a>--}}
    {{--                                    <a href="javascript:void(0);" class="dropdown-item deactive_status_all"><i--}}
    {{--                                            class="mdi mdi-update"></i> Deactive All</a>--}}
                                        <a href="javascript:void(0);" class="dropdown-item delete_all"><i
                                                class="mdi mdi-delete-circle"></i> Delete All</a>
                                </div>
                            </div>

                        </div>
                    </div><!-- end col-->
                </div> <!-- end row -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
{{--                        <div class="table-responsive">--}}
                            <table id="client-datatable" class="table table-centered table-striped table-sm nowrap w-100">
                                <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" class="form-check-input" id="select_all"></th>
                                    <th>Name</th>
                                    <th>Company name</th>
                                    <th>Mobile no</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Pincode</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Business Category</th>
                                    <th>Website</th>
                                    <th>Tax</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
{{--                        </div>--}}
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
        <div id="client-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-right" style="width: 100%;">
                <div class="modal-content" style="height: 100%;">
                    <div class="modal-header border-1">
                        <h4 class="modal-title">Create Client</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="ps-3 pe-3 client-form" id="client-form" action="#">

                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="name" name="name" required=""
                                       placeholder="Enter name" autofocus>
                                <input class="form-control" type="hidden" id="id" name="id" value="0">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                          placeholder="Enter description"></textarea>
                            </div>

                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" id="client_button" type="submit"><i
                                        class="uil-arrow-circle-right"></i> Save
                                </button>
                            </div>

                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>


@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
        <script src="{{ asset('assets/js/app.min.js')}}"></script>

    <!-- third party js -->
    @include('layouts.partials.admin-datatable-script')

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
            var table = $("#client-datatable").DataTable({
                // dom: 'Bfrtip',
                dom:
                    "<'row'<'col-sm-12 col-md-6 text-left'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                responsive: false,
                scrollX:!0,
                processing: true,
                serverSide: true,
                stateSave: true,
                lengthChange: !1,
                buttons: [
                    {
                        extend: 'print',
                        title: 'Client List',
                        // customize: function (win) {
                        //     $(win.document.body)
                        //         .css('font-size', '10pt')
                        //         .prepend(
                        //             '<img src="http://127.0.0.1:8000/assets/images/logo-dark.png" style="position:absolute; top:0; left:0;" />'
                        //         );
                        //
                        //     $(win.document.body).find('table')
                        //         .addClass('compact')
                        //         .css('font-size', 'inherit');
                        // },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'Client List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'Client List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'Client List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        title: 'Client List',
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
                    data.fil_name = $('#fil_name').val();
                },
                stateLoadParams: function (settings, data) {
                    $('#fil_status').val(data.fil_status);
                    $('#fil_name').val(data.fil_name);
                },
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem(settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem(settings.sInstance))
                },
                ajax: {
                    url: "{{ route('admin.client.index') }}",
                    data: function (d) {
                        d.status = $('#fil_status').val(),
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox form-check-input" data-id="' + row.action + '">';
                        }
                    },
                    {data: 'name', name: 'name', orderable: true},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'pincode', name: 'pincode'},
                    {data: 'country_name', name: 'country_name'},
                    {data: 'state_name', name: 'state_name'},
                    {data: 'city_name', name: 'city_name'},
                    {data: 'business_category_name', name: 'business_category_name'},
                    {data: 'website_link', name: 'website_link'},
                    {data: 'gst_no', name: 'gst_no'},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            var fun_status = "client_status('" + row.action + "','{{route('admin.client.edit-status')}}','#client-datatable')";
                            if (data == 'Pending')
                                return '<span class="badge badge-info-lighten" onclick="' + fun_status + '">Pending</span>';
                            else if (data == 'New')
                                return '<span class="badge badge-primary-lighten" onclick="javascript:void(0);">New</span>';
                            else if (data == 'Approved')
                                return '<span class="badge badge-success-lighten" onclick="javascript:void(0);">Approved</span>';
                            else {
                                return '<span class="badge badge-danger-lighten" onclick="javascript:void(0);">Rejected</span>';
                            }

                        }
                    },
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {

                            var edit_fun = "edit_id('" + row.action + "')";
                            var delete_fun = "remove_id('" + row.action + "','{{route('admin.client.delete')}}','#client-datatable')";

                            return '<div class="invoice-action">' +
                                // '<a href="javascript:void(0)" class="action-icon mr-1" id="edit_' + row.action + '" onclick="' + edit_fun + '">' +
                                // '<i class="mdi mdi-square-edit-outline"></i>' +
                                // '</a>' +
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
            table.buttons().container().appendTo("#client-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
                pagingType: "full_numbers",
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            })

            $('#fil_status,#fil_name').change(function () {
                table.draw();
            });

            $('#resetFilter').click(function () {
                $('input[type=text]').val('');
                $('#fil_status').val('');
                table
                    .search('')
                    .columns().search('')
                    .draw();
            });


            formValition('#client-form');
            {{--$('.client-form').on('submit',function(e) {--}}
            {{--    e.preventDefault();--}}
            {{--    if ( $(this).parsley().isValid() ) {--}}
            {{--        $.ajax({--}}
            {{--            type : 'POST',--}}
            {{--            url  : '{{route('client.store')}}',--}}
            {{--            contentType: false,--}}
            {{--            cache: false,--}}
            {{--            processData:false,--}}
            {{--            data : new FormData(this),--}}
            {{--            // data: $('.category-form').serialize(),--}}
            {{--            dataType: "json",--}}
            {{--            beforeSend: function(){--}}
            {{--                $("#client_button").prop('disabled',true);--}}
            {{--                $("#client_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');--}}
            {{--            },--}}
            {{--            success : function(data){--}}
            {{--                    toastrSuccess('Successfully saved...','Success');--}}
            {{--                    $('#client-modal').modal('toggle');--}}
            {{--                    table.ajax.reload();--}}
            {{--                $("#client_button").prop('disabled',false);--}}
            {{--                $("#client_button").html('<i class="uil-arrow-circle-right"></i> Save');--}}
            {{--            },--}}
            {{--            error: function(xhr, status, error){--}}
            {{--                var errorMessage = xhr.status + ': ' + xhr.statusText--}}
            {{--                switch(xhr.status) {--}}
            {{--                    case 401:--}}
            {{--                        toastrError('Error in saving...','Error');--}}
            {{--                        break;--}}
            {{--                    case 422:--}}
            {{--                        toastrInfo('The category is invalid.','Info');--}}
            {{--                        break;--}}
            {{--                    case 409:--}}
            {{--                        toastrInfo('Name already exist.','Warning');--}}
            {{--                        break;--}}
            {{--                    default:--}}
            {{--                        toastrError('Error - ' + errorMessage,'Error');--}}
            {{--                }--}}
            {{--                $("#client_button").prop('disabled',false);--}}
            {{--                $("#client_button").html('<i class="uil-arrow-circle-right"></i> Save');--}}
            {{--            },--}}
            {{--            complete:function(data){--}}
            {{--                $("#client_button").html('Save');--}}
            {{--                $("#client_button").prop('<i class="uil-arrow-circle-right"></i> disabled',false);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
        });
        function client_status(id, url, tableName) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure want to",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085D6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Approved",
                cancelButtonText: "Rejected",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1,
            }).then((function (t) {
                if (t.value) {
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: url,
                        data: {id: id, status: "Approved"},
                        dataType: "json",
                        success: function (data, textStatus, jqXHR) {
                            toastrSuccess('Successfully updated');
                            $(tableName).DataTable().ajax.reload();
                        }
                    });
                } else if (t.dismiss === Swal.DismissReason.cancel) {
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: url,
                        data: {id: id, status: "Rejected"},
                        dataType: "json",
                        success: function (data, textStatus, jqXHR) {
                            toastrSuccess('Successfully updated');
                            $(tableName).DataTable().ajax.reload();
                        }
                    });
                }

            }))
        }

        {{--function edit_id(id) {--}}
        {{--    $.ajax({--}}
        {{--        type: "GET",--}}
        {{--        url: "{{route('client.show')}}",--}}
        {{--        data: {id:id},--}}
        {{--        dataType:"json",--}}
        {{--        success: function(res) {--}}
        {{--            resetFormValidation("#client-form");--}}
        {{--            $('#id').val(res.data.id);--}}
        {{--            $('#name').val(res.data.name);--}}
        {{--            $('#description').val(res.data.description);--}}
        {{--            $('.modal-title').text('Edit Client');--}}
        {{--            $('#client-modal').modal('toggle');--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

        //Remove multiple record
        $('.delete_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values,'{{route('admin.client.delete')}}','#client-datatable');
        });

        {{--$('.active_status_all').on('click', function(e) {--}}
        {{--    var allVals = [];--}}
        {{--    $(".single_checkbox:checked").each(function() {--}}
        {{--        allVals.push($(this).attr('data-id'));--}}
        {{--    });--}}
        {{--    var join_selected_values = allVals.join(",");--}}
        {{--    change_status(join_selected_values,0,'{{route('client.edit-status')}}','#client-datatable');--}}
        {{--});--}}

        {{--$('.deactive_status_all').on('click', function(e) {--}}
        {{--    var allVals = [];--}}
        {{--    $(".single_checkbox:checked").each(function() {--}}
        {{--        allVals.push($(this).attr('data-id'));--}}
        {{--    });--}}
        {{--    var join_selected_values = allVals.join(",");--}}
        {{--    change_status(join_selected_values,1,'{{route('client.edit-status')}}','#client-datatable');--}}
        {{--});--}}
    </script>
@endpush
