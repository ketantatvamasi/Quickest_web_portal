@extends('layouts.app')
@section('title','User')
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
    $company_id = auth()->user()->company_id;
    ?>
    {{--    <x-breadcrumbs pagename="USERS" pagetitle="VIEW_USER_LIST"/>--}}
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <?php if(in_array('add-user', $user_perm) || auth()->user()->company_id==null){  ?>
                        <a href="{{route('user-create')}}" class="btn btn-info btn-sm mb-2"><i
                            class="mdi mdi-plus-circle"></i> New</a>
                    <?php } ?>
                    <div class="dropdown btn-group mb-2">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            id="select_count">0</span>Bulk Action
                            <!-- <span class="badge badge-success-lighten" id="select_count">0</span> -->
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
                <div class="page-title-left pt-2 fw-bold">
                    User
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{--<div class="row mb-2">
                        <div class="col-xl-8">
                            <form
                                class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_name" class="visually-hidden">Name</label>
                                        <input type="text" class="form-control" id="fil_name" name="fil_name"
                                               placeholder="name...">
                                    </div>
                                </div>
                                --}}{{--<div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_status" class="me-2">Status</label>
                                        <select class="form-select" id="fil_status" name="fil_status">
                                            <option value="">Choose...</option>
                                            <option value="0">Active</option>
                                            <option value="1">Deactive</option>
                                        </select>
                                    </div>
                                </div>--}}{{--
                            </form>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-xl-end mt-xl-0 mt-2">
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2"
                                        id="resetFilter">
                                    <i class="mdi mdi-filter"></i> Reset Filters
                                </button>
                                <a href="{{route('user-create')}}" class="btn btn-info mb-2"><i
                                        class="mdi mdi-plus-circle"></i> Add User</a>
                                <div class="dropdown btn-group mb-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            id="select_count">0</span>Bulk Action
                                        <!-- <span class="badge badge-success-lighten" id="select_count">0</span> -->
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
                    <table id="user-datatable" class="table dt-responsive table-sm nowrap w-100">
                        <thead class="table-ghtli">
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>Role</th>
                            <th>Assign User</th>
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Mobile</th>
                            {{--                            <th>Status</th>--}}
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
        var user_perm = <?php echo json_encode($user_perm); ?>;
        var company_id = '<?php echo $company_id; ?>';
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            "use strict";
            var table = $("#user-datatable").DataTable({
                dom: 'Bfrtp',
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
                        title: 'User List',
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
                        title: 'User List',
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
                        title: 'User List',
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
                    url: "{{ route('user.index') }}",
                    data: function (d) {
                        d.status = $('#fil_status').val(),
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    {
                        data: 'id', name: 'u1.id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox" data-id="' + row.action + '">';
                        }
                    },
                    {data: 'user_role', name: 'u1.user_role', orderable: true},
                    {data: 'assign_user', name: 'u1.assign_user'},
                    {data: 'name', name: 'u1.name'},
                    {data: 'email', name: 'u1.email'},
                    {data: 'mobile_no', name: 'u1.mobile_no'},
                    /* {
                         data: 'status', name: 'status',
                         render: function (data, type, row) {
                             var fun_status = "change_status('" + row.action + "', 1,'{{route('user.edit-status')}}','#user-datatable')";
                            if (data == 0)
                                return '<span class="badge badge-success-lighten" onclick="'+fun_status+'">Active</span>';
                            else{
                                fun_status = "change_status('" + row.action + "', 0,'{{route('user.edit-status')}}','#user-datatable')";
                                return '<span class="badge badge-danger-lighten" onclick="'+fun_status+'">Deactive</span>';
                            }

                        }
                    },*/
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {
                            var edit_fun = "edit_id('" + row.action + "')";
                            var delete_fun = "remove_id('" + row.action + "','{{route('user.delete')}}','#user-datatable')";
                            var editlink = "{{url('user-edit')}}/" + row.action;
                            if($.inArray('edit-user', user_perm) != -1 || company_id==''){
                                var edit ='<a href="' + editlink + '" class="action-icon mr-1" id="edit_' + row.action + '" >' +
                                    '<i class="mdi mdi-square-edit-outline"></i>' +
                                    '</a>' ;
                            }else{
                                var edit = '';
                            }
                            if($.inArray('remove-user', user_perm) != -1 || company_id==''){
                                var del ='<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="' + delete_fun + '">' +
                                '<i class="mdi mdi-delete"></i>' +
                                '</a>';
                            }else{
                                var del = '';
                            }
                            return '<div class="invoice-action">' +
                                // '<a href="' + editlink + '" class="action-icon mr-1" id="edit_' + row.action + '" >' +
                                // '<i class="mdi mdi-square-edit-outline"></i>' +
                                // '</a>'
                               edit +
                                // '<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="' + delete_fun + '">' +
                                // '<i class="mdi mdi-delete"></i>' +
                                // '</a>'
                               del +
                                '</div>';
                        }
                    },
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            table.buttons().container().appendTo("#unit-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
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
        });

        //Remove multiple record
        $('.delete_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values, '{{route('unit.delete')}}', '#user-datatable');
        });

        $('.active_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 0, '{{route('unit.edit-status')}}', '#user-datatable');
        });

        $('.deactive_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 1, '{{route('unit.edit-status')}}', '#user-datatable');
        });
    </script>
@endpush
