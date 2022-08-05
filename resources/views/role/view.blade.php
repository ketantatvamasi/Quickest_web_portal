@extends('layouts.app')
@section('title','Role')
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
                @if(in_array('add-role', $user_perm) || auth()->user()->company_id==null)
                    <a href="{{route('role-create')}}" class="btn btn-info btn-sm mb-2"><i
                        class="mdi mdi-plus-circle"></i> New</a>
                @endif
                <div class="dropdown btn-group mb-2">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            id="select_count">0</span>Bulk Action
                        <!-- <span class="badge badge-success-lighten" id="select_count">0</span> -->
                    </button>
                    <div class="dropdown-menu dropdown-menu-animated">
                        <a href="javascript:void(0);" class="dropdown-item delete_all"><i
                                class="mdi mdi-delete-circle"></i> Delete All</a>
                    </div>
                </div>
            </div>
            <div class="page-title-left pt-2 fw-bold">
                Role
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
                            <form class="row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                <div class="col-auto">
                                    <div class="d-flex align-items-center">
                                        <label for="fil_name" class="visually-hidden">Name</label>
                                        <input type="text" class="form-control" id="fil_name" name="fil_name"
                                               placeholder="name...">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-xl-end mt-xl-0 mt-2">
                                <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2" id="resetFilter">
                                    <i class="mdi mdi-filter"></i> Reset Filters
                                </button>
                                <a href="{{route('role-create')}}" class="btn btn-info mb-2"  ><i class="mdi mdi-plus-circle"></i> Add Role</a>

                                <div class="dropdown btn-group mb-2">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="select_count">0</span>Bulk Action
                                        <!-- <span class="badge badge-success-lighten" id="select_count">0</span> -->
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-animated">
                                        <a href="javascript:void(0);" class="dropdown-item delete_all"><i class="mdi mdi-delete-circle"></i> Delete All</a>
                                    </div>
                                </div>

                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->--}}
                    <table id="role-datatable" class="table dt-responsive table-sm nowrap w-100">
                        <thead class="table-ghtli">
                        <tr>
                            <th><input type="checkbox" id="select_all" class="form-check-input"></th>
                            <th>Role</th>
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
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            "use strict";
            var table = $("#role-datatable").DataTable({
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
                        title: 'Role List',
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
                        title: 'Role List',
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
                        title: 'Role List',
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
                    data.fil_name = $('#fil_name').val();
                },
                stateLoadParams: function (settings, data) {
                    $('#fil_name').val(data.fil_name);
                },
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem(settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem(settings.sInstance))
                },
                ajax: {
                    url: "{{ route('role.index') }}",
                    data: function (d) {
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                "order": [[ 1, "asc" ]],
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox form-check-input" data-id="'+ row.action +'">';
                        }
                    },
                    {data: 'name', name: 'name'},
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {
                            var edit_fun = '{{url('role-edit')}}/'+ row.action;
                            var delete_fun = "remove_id('" + row.action + "','{{route('role.delete')}}','#role-datatable')";
                            var delete_action='';
                            @if(in_array("remove-role", $user_perm) || auth()->user()->company_id==null)
                                var delete_action = '<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="' + delete_fun + '"><i class="mdi mdi-delete"></i></a>';
                            @endif
                            if(row.display_flag==1){
                                delete_action='';
                            }
                            return '<div class="invoice-action">' +
                                @if(in_array("edit-role", $user_perm) || auth()->user()->company_id==null)
                                    '<a href="javascript:void(0)" class="action-icon mr-1" id="edit_' + row.action + '" onclick="' + edit_fun + '">' +
                                '<i class="mdi mdi-square-edit-outline"></i>' +
                                '</a>'+
                                @endif
                                delete_action+
                            '<div>';
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

            $('#fil_name').change(function () {
                table.draw();
            });

            $('#resetFilter').click(function () {
                $('input[type=text]').val('');
                table
                    .search('')
                    .columns().search('')
                    .draw();
            });
        });

        //Remove multiple record
        $('.delete_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values,'{{route('role.delete')}}','#role-datatable');
        });
    </script>
@endpush
