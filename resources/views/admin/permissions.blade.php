@extends('layouts.app')
@section('title','Permission')
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
                            <li class="breadcrumb-item active">Permission List</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Permission</h4>
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
                        </form>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-xl-end mt-xl-0 mt-2">
                            <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2" id="resetFilter">
                                <i class="mdi mdi-filter"></i> Reset Filters
                            </button>
                            <a href="javascript:void(0);" class="btn btn-info mb-2"  onclick="openModal('#permission-modal','Create Permission','#permission-form','.modal-title',id=0)"><i class="mdi mdi-plus-circle"></i> Add Permission</a>

                            <div class="dropdown btn-group mb-2">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="select_count">0</span>Bulk Action
{{--                                    <span class="badge badge-success-lighten" id="select_count">0</span> --}}
                                </button>
                                <div class="dropdown-menu dropdown-menu-animated">
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

                        <table id="permission-datatable" class="table table-centered table-striped table-sm nowrap w-100">
                            <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="select_all"></th>
                                <th>Name</th>
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
        <div id="permission-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-right" style="width: 100%;">
                <div class="modal-content" style="height: 100%;">
                    <div class="modal-header border-1 bg-light">
                        <h4 class="modal-title">Create Permission</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="ps-3 pe-3 permission-form" id="permission-form" action="#">

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="name" name="name" required="" placeholder="Enter name" autofocus>
                                    <input class="form-control" type="hidden" id="id" name="id" value="0">
                                </div>

                                <div class="mb-3 text-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-secondary" id="permission_button" type="submit"><i class="uil-arrow-circle-right"></i> Save</button>
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
            var table = $("#permission-datatable").DataTable({
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
                    url: "{{ route('admin.permission.index') }}",
                    data: function (d) {
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
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

                            var edit_fun = "edit_id('" + row.action + "')";
                            var delete_fun = "remove_id('" + row.action + "','{{route('admin.permission.delete')}}','#permission-datatable')";
                            return '<div class="invoice-action">' +
                                '<a href="javascript:void(0)" class="action-icon mr-1" id="edit_' + row.action + '" onclick="'+edit_fun+'">' +
                                '<i class="mdi mdi-square-edit-outline"></i>' +
                                '</a>' +
                                '<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="'+delete_fun+'">' +
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
            table.buttons().container().appendTo("#permission-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
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

            formValition('#permission-form');
            $('.permission-form').on('submit',function(e) {
                e.preventDefault();
                if ( $(this).parsley().isValid() ) {
                    $.ajax({
                        async: false,
                        type : 'POST',
                        url  : '{{route('admin.permission.store')}}',
                        contentType: false,
                        cache: false,
                        processData:false,
                        data : new FormData(this),
                        // data: $('.permission-form').serialize(),
                        dataType: "json",
                        beforeSend: function(){
                            $("#permission_button").prop('disabled',true);
                            $("#permission_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success : function(data){
                                toastrSuccess('Successfully saved...','Success');
                                $('#permission-modal').modal('toggle');
                                table.ajax.reload();
                            $("#permission_button").prop('disabled',false);
                            $("#permission_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        error: function(xhr, status, error){
                            var errorMessage = xhr.status + ': ' + xhr.statusText
                            switch(xhr.status) {
                                case 401:
                                    toastrError('Error in saving...','Error');
                                    break;
                                case 422:
                                    toastrInfo('The permission is invalid.','Info');
                                    break;
                                case 409:
                                    toastrInfo('Name already exist.','Warning');
                                    break;
                                default:
                                    toastrError('Error - ' + errorMessage,'Error');
                            }
                            $("#permission_button").prop('disabled',false);
                            $("#permission_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete:function(data){
                            $("#permission_button").html('Save');
                            $("#permission_button").prop('<i class="uil-arrow-circle-right"></i> disabled',false);
                        }
                    });
                }
            });
        });

        function edit_id(id) {
            $.ajax({
                async: false,
                type: "GET",
                url: "{{route('admin.permission.show')}}",
                data: {id:id},
                dataType:"json",
                success: function(res) {
                    resetFormValidation("#permission-form");
                    $('#id').val(res.data.id);
                    $('#name').val(res.data.name);
                    $('.modal-title').text('Edit Permission');
                    $('#permission-modal').modal('toggle');
                }
            });
        }

        //Remove multiple record
        $('.delete_all').on('click', function(e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            remove_id(join_selected_values,'{{route('admin.permission.delete')}}','#permission-datatable');
        });


    </script>
@endpush
