@extends('layouts.app')
@section('title','Permissions')
@push('styles')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/select.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <x-breadcrumbs pagename="USERS" pagetitle="VIEW_PERMISSIONS_LIST"/>
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
                                    <option value="0">Active</option>
                                    <option value="1">Deactive</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4">
                    <div class="text-xl-end mt-xl-0 mt-2">
                        <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2" id="resetFilter">
                            <i class="mdi mdi-filter"></i> Reset Filters
                        </button>
                        <a href="{{route('admin.permission-create')}}" class="btn btn-info mb-2"  ><i class="mdi mdi-plus-circle"></i> Add Permission</a>

                        <div class="dropdown btn-group mb-2">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="select_count">0</span>Bulk Action
                                <!-- <span class="badge badge-success-lighten" id="select_count">0</span> -->
                            </button>
                            <div class="dropdown-menu dropdown-menu-animated">
                                <a href="javascript:void(0);" class="dropdown-item active_status_all"><i class="mdi mdi-update"></i> Active All</a>
                                <a href="javascript:void(0);" class="dropdown-item deactive_status_all"><i class="mdi mdi-update"></i> Deactive All</a>
                                <a href="javascript:void(0);" class="dropdown-item delete_all"><i class="mdi mdi-delete-circle"></i> Delete All</a>
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
                    <table id="permission-datatable" class="table dt-responsive table-sm nowrap w-100">
                        <thead class="table-ghtli">
                        <tr>
                            <th><input type="checkbox" id="select_all"></th>
                            <th>Permission</th>
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
    <script src="{{ asset('assets/js/vendor/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.bootstrap5.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/responsive.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.keyTable.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/dataTables.select.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js')}}"></script>
    <!-- third party js ends -->

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            "use strict";
            var table = $("#permission-datatable").DataTable({
                dom: 'Bfrtip',
                responsive: false,
                scrollX:!0,
                processing: true,
                serverSide: true,
                stateSave: true,
                lengthChange: !1,
                buttons: [
                    {
                        extend: 'print',
                        title: 'Permission List',
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="http://192.168.5.103:8080/assets/images/logo-dark.png" style="position:absolute; top:0; left:0;" />'
                                );

                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'Permission List',
                    },
                    {
                        extend: 'excel',
                        title: 'Permission List',
                    },
                    {
                        extend: 'csv',
                        title: 'Permission List',
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
                    url: "{{ route('admin.permissions.index') }}",
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
                            return '<input type="checkbox" class="single_checkbox" data-id="'+ row.action +'">';
                        }
                    },
                    {data: 'permissions_name', name: 'permissions_name'},
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
        $('.delete_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values,'{{route('unit.delete')}}','#permission-datatable');
        });

        $('.active_status_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values,0,'{{route('unit.edit-status')}}','#permission-datatable');
        });

        $('.deactive_status_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values,1,'{{route('unit.edit-status')}}','#permission-datatable');
        });
    </script>
@endpush
