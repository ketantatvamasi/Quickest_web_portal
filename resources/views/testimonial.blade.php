@extends('layouts.app')
@section('title','Testimonial')
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
                    @if(in_array('add-testimonial', $user_perm) || auth()->user()->company_id==null)
                        <a href="javascript:void(0);" class="btn btn-info btn-sm mb-2"
                       onclick="openModal('#testimonial-modal','Create Testimonial','#testimonial-form','.modal-title',id=0,flag=2)"><i
                            class="mdi mdi-plus-circle"></i> New</a>
                    @endif
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
                    {{--<h4 class="page-title">Testimonial</h4>--}}
                    <select class="form-select" id="fil_status" name="fil_status"
                            style="width: 230px;background-color: #fff0 !important;border: 0px solid #fff !important;font-size: 18px;margin: 0;white-space: nowrap;font-weight: 700;padding: 0.0rem 0.0rem 0rem 0.5rem;">
                        <option value="">All Testimonials</option>
                        <option value="0">Active Testimonials</option>
                        <option value="1">Deactive Testimonials</option>
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
                    <table id="testimonial-datatable"
                           class="table table-centered table-sm w-100 nowrap">
                        <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="select_all"></th>
                            {{--                                <th>Image</th>--}}
                            <th>Name</th>
                            {{--                                <th>Rating</th>--}}
                            {{--                                <th>Description</th>--}}
                            <th>Status</th>
                            <th>Is Default</th>
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
    <div id="testimonial-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-right" style="width: 100%;">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header border-1 bg-light">
                    <h4 class="modal-title">Create Testimonial</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="ps-3 pe-3 testimonial-form" id="testimonial-form" action="#"
                          enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="name" name="name" required=""
                                   placeholder="Enter name" autofocus>
                            <input class="form-control" type="hidden" id="id" name="id" value="0">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                            <label class="form-check-label" for="is_default">Set as Default</label>
                        </div>

                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                            <th>Client Name<span class="text-danger">*</span></th>
                            <th>Description<span class="text-danger">*</span></th>
                            <th>Rating<span class="text-danger">*</span></th>
                            <th>Picture<span class="text-danger">*</span></th>
                            </thead>
                            <tbody class="testimonialRow">
                            <tr id="1">
                                <td>
                                    <input class="form-control" type="text" id="client_name_one" name="client_name_one"
                                           required="" placeholder="Enter name" autofocus>
                                </td>
                                <td>
                                    <textarea class="form-control" id="description_one" name="description_one"
                                              placeholder="Enter description" maxlength="225" data-toggle="maxlength"
                                              required=""></textarea>
                                </td>
                                <td>
                                    <select class="form-select" id="rating_one" name="rating_one" required="">
                                        <option value="">Choose</option>
                                        <option value="1">1.0</option>
                                        {{--                                    <option value="1.5">1.5</option>--}}
                                        <option value="2">2.0</option>
                                        {{--                                    <option value="2.5">2.5</option>--}}
                                        <option value="3">3.0</option>
                                        {{--                                    <option value="3.5">3.5</option>--}}
                                        <option value="4">4.0</option>
                                        {{--                                    <option value="4.5">4.5</option>--}}
                                        <option value="5">5.0</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" class="form-control" data-parsley-trigger="change"
                                           name="image_one"
                                           id="image_one" data-parsley-required="false"
                                           accept="'image/jpg,image/jpeg,image/png,image/PNG,image/Png"
                                           data-parsley-fileextension="jpg,png,jpeg"
                                           data-parsley-max-file-size="1024"
                                           required>
                                </td>
                            </tr>
                            <tr id="2">
                                <td>
                                    <input class="form-control" type="text" id="client_name_two" name="client_name_two"
                                           required="" placeholder="Enter name" autofocus>

                                </td>
                                <td>
                                        <textarea class="form-control" id="description_two" name="description_two"
                                                  placeholder="Enter description" maxlength="225"
                                                  data-toggle="maxlength" required=""></textarea>

                                </td>
                                <td>
                                    <select class="form-select" id="rating_two" name="rating_two" required="">
                                        <option value="">Choose</option>
                                        <option value="1">1.0</option>
                                        {{--                                    <option value="1.5">1.5</option>--}}
                                        <option value="2">2.0</option>
                                        {{--                                    <option value="2.5">2.5</option>--}}
                                        <option value="3">3.0</option>
                                        {{--                                    <option value="3.5">3.5</option>--}}
                                        <option value="4">4.0</option>
                                        {{--                                    <option value="4.5">4.5</option>--}}
                                        <option value="5">5.0</option>
                                    </select>

                                </td>
                                <td>
                                    <input type="file" class="form-control" data-parsley-trigger="change"
                                           name="image_two"
                                           id="image_two" data-parsley-required="false"
                                           accept="'image/jpg,image/jpeg,image/png,image/PNG,image/Png"
                                           data-parsley-fileextension="jpg,png,jpeg"
                                           data-parsley-max-file-size="1024"
                                           required>
                                </td>
                            </tr>
                            <tr id="3">
                                <td>
                                    <input class="form-control" type="text" id="client_name_three"
                                           name="client_name_three"
                                           required=""
                                           placeholder="Enter name" autofocus>

                                </td>
                                <td>
                                        <textarea class="form-control" id="description_three" name="description_three"
                                                  placeholder="Enter description" maxlength="225"
                                                  data-toggle="maxlength" required=""></textarea>

                                </td>
                                <td>
                                    <select class="form-select" id="rating_three" name="rating_three" required="">
                                        <option value="">Choose</option>
                                        <option value="1">1.0</option>
                                        {{--                                    <option value="1.5">1.5</option>--}}
                                        <option value="2">2.0</option>
                                        {{--                                    <option value="2.5">2.5</option>--}}
                                        <option value="3">3.0</option>
                                        {{--                                    <option value="3.5">3.5</option>--}}
                                        <option value="4">4.0</option>
                                        {{--                                    <option value="4.5">4.5</option>--}}
                                        <option value="5">5.0</option>
                                    </select>

                                </td>
                                <td>
                                    <input type="file" class="form-control" data-parsley-trigger="change"
                                           name="image_three"
                                           id="image_three" data-parsley-required="false"
                                           accept="'image/jpg,image/jpeg,image/png,image/PNG,image/Png"
                                           data-parsley-fileextension="jpg,png,jpeg"
                                           data-parsley-max-file-size="1024"
                                           required>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="mb-3 mt-3 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-secondary" id="unit_button" type="submit"><i
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
            var table = $("#testimonial-datatable").DataTable({
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
                        title: 'Testimonial List',
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
                        title: 'Testimonial List',
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
                        title: 'Testimonial List',
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
                    url: "{{ route('testimonial.index') }}",
                    data: function (d) {
                        d.status = $('#fil_status').val(),
                            d.name = $('#fil_name').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                "order": [[2, "asc"]],
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox form-check-input" data-id="' + row.action + '">';
                        }
                    },


                    // {
                    //     data: 'image_one', name: 'image_one', orderable: false,
                    //     render: function (data, type, row) {
                    //         return '<div id="tooltip-container"> <img src="' + row.image_one + '" class="rounded-circle avatar-xs zoom" alt="friend"> </div>';
                    //     }
                    // },
                    {data: 'name', name: 'name', orderable: true},
                    // {data: 'rating', name: 'rating'},
                    // {data: 'description', name: 'description'},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            var fun_status = "change_status('" + row.action + "', 1,'{{route('testimonial.edit-status')}}','#testimonial-datatable')";
                            if (data == 0)
                                return '<span class="badge badge-success-lighten" onclick="' + fun_status + '">Active</span>';
                            else {
                                fun_status = "change_status('" + row.action + "', 0,'{{route('testimonial.edit-status')}}','#testimonial-datatable')";
                                return '<span class="badge badge-danger-lighten" onclick="' + fun_status + '">Deactive</span>';
                            }
                        }
                    },
                    {
                        data: 'is_default', name: 'is_default', orderable: false,
                        render: function (data, type, row) {
                            return (row.is_default) ? '<span class="badge badge-primary-lighten">Yes</span>' : '-';
                        }
                    },
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {
                            var edit_fun = "edit_id('" + row.action + "')";
                            var delete_fun = "remove_id('" + row.action + "','{{route('testimonial.delete')}}','#testimonial-datatable')";
                            return '<div class="invoice-action">' +
                                @if(in_array("edit-testimonial", $user_perm) || auth()->user()->company_id==null)
                                    '<a href="javascript:void(0)" class="action-icon mr-1" id="edit_' + row.action + '" onclick="' + edit_fun + '">' +
                                    '<i class="mdi mdi-square-edit-outline"></i>' +
                                    '</a>'+
                                @endif
                                @if(in_array("remove-testimonial", $user_perm) || auth()->user()->company_id==null)
                                    '<a href="javascript:void(0)" class="action-icon" id="remove_' + row.action + '"  onclick="' + delete_fun + '">' +
                                    '<i class="mdi mdi-delete"></i>' +
                                    '</a>'+
                                @endif
                                    '<div>';
                        }
                    },
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });
            table.buttons().container().appendTo("#testimonial-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
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


            formValition('#testimonial-form');
            $('.testimonial-form').on('submit', function (e) {
                e.preventDefault();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: '{{route('testimonial.store')}}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        // data: $('.category-form').serialize(),
                        dataType: "json",
                        beforeSend: function () {
                            $("#unit_button").prop('disabled', true);
                            $("#unit_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success: function (data) {
                            toastrSuccess('Successfully saved...', 'Success');
                            $('#testimonial-modal').modal('toggle');
                            table.ajax.reload();
                            $("#unit_button").prop('disabled', false);
                            $("#unit_button").html('<i class="uil-arrow-circle-right"></i> Save');
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
                            $("#unit_button").prop('disabled', false);
                            $("#unit_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete: function (data) {
                            $("#unit_button").html('Save');
                            $("#unit_button").prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                        }
                    });
                }
            });
        });

        function edit_id(id) {
            $.ajax({
                async: false,
                type: "GET",
                url: "{{route('testimonial.show')}}",
                data: {id: id},
                dataType: "json",
                success: function (res) {
                    $("#image_one").val(null);
                    $("#image_two").val(null);
                    $("#image_three").val(null);
                    $("input[name=is_default]").prop('checked', false);
                    resetFormValidation("#testimonial-form");
                    $('#image_one').prop('required', false);
                    $('#image_two').prop('required', false);
                    $('#image_three').prop('required', false);
                    $('#id').val(res.data.id);
                    $('#name').val(res.data.name);
                    $('#client_name_one').val(res.data.client_name_one);
                    $('#rating_one').val(res.data.rating_one);
                    $('#description_one').val(res.data.description_one);
                    $('#client_name_two').val(res.data.client_name_two);
                    $('#rating_two').val(res.data.rating_two);
                    $('#description_two').val(res.data.description_two);
                    $('#client_name_three').val(res.data.client_name_three);
                    $('#rating_three').val(res.data.rating_three);
                    $('#description_three').val(res.data.description_three);
                    $("input[name=is_default][value=" + res.data.is_default + "]").prop('checked', true);
                    $('.modal-title').text('Edit Testimonial');
                    $('#testimonial-modal').modal('toggle');
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
            remove_id(join_selected_values, '{{route('testimonial.delete')}}', '#testimonial-datatable');
        });

        $('.active_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 0, '{{route('testimonial.edit-status')}}', '#testimonial-datatable');
        });

        $('.deactive_status_all').on('click', function (e) {
            var allVals = [];
            $(".single_checkbox:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });
            var join_selected_values = allVals.join(",");
            change_status(join_selected_values, 1, '{{route('testimonial.edit-status')}}', '#testimonial-datatable');
        });
    </script>
@endpush
