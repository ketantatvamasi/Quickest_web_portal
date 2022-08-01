@extends('layouts.app')
@section('title','Estimate')
@push('styles')
    <link href="{{ asset('assets/css/vendor/dataTables.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/responsive.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/buttons.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/vendor/select.bootstrap5.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.css"
          rel="stylesheet" type="text/css">
@endpush
@section('content')
    <?php
    $user_perm = PermissionCheck::check_permission('role-list');
    $company_id = auth()->user()->company_id;
    ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <?php if(in_array('add-estimate', $user_perm) || auth()->user()->company_id==null){  ?>
                        <a href="{{route('quotes.new')}}" class="btn btn-info btn-sm mb-2"><i class="mdi mdi-plus-circle"></i> New</a>
                    <?php } ?>
                </div>
                <div class="page-title-left pt-2">

                    {{--                        <h4 class="page-title">Estimate</h4>--}}

                    <select class="form-select" id="fil_status" name="fil_status"
                            style="width: 250px;background-color: #fff0 !important;border: 0px solid #fff !important;font-size: 18px;margin: 0;white-space: nowrap;font-weight: 700;padding: 0.0rem 0.0rem 0rem 0.5rem;">
                        <option value="" @if(request()->get('status')=="Total") {{'selected'}} @endif>All Estimates</option>
                        <option value="Draft" @if(request()->get('status')=="Draft") {{'selected'}} @endif>Draft
                            Estimates
                        </option>
                        <option value="Sent" @if(request()->get('status')=="Sent") {{'selected'}} @endif>Sent
                            Estimates
                        </option>
                        <option value="Inprogress" @if(request()->get('status')=="Inprogress") {{'selected'}} @endif>
                            Inprogress Estimates
                        </option>
                        <option value="Accept" @if(request()->get('status')=="Accept") {{'selected'}} @endif>Accept
                            Estimates
                        </option>
                        <option value="Decline" @if(request()->get('status')=="Decline") {{'selected'}} @endif>Decline
                            Estimates
                        </option>
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
                         <div class="col-xl-8">
                             <div class=" row gy-2 gx-2 align-items-center justify-content-xl-start justify-content-between">
                                 <div class="col-auto">
                                     <div class="d-flex align-items-center">
                                         <label for="fil_name" class="visually-hidden">Name</label>
                                         <input type="text" class="form-control" id="fil_name" name="fil_name"
                                                placeholder="name...">
                                     </div>
                                 </div>
                                 <div class="col-auto">
                                     <div class="d-flex align-items-center">
                                         <label for="fil_name" class="visually-hidden">Estimate No</label>
                                         <input type="text" class="form-control" id="fil_estimate_no" name="fil_estimate_no"
                                                placeholder="estimate number...">
                                     </div>
                                 </div>
                                 <div class="col-auto">
                                     <div class="d-flex align-items-center">
                                         <label for="fil_status" class="me-2">Status</label>
                                         <select class="form-select" id="fil_status" name="fil_status">
                                             <option value="">Choose...</option>
                                             <option>Draft</option>
                                             <option>Sent</option>
                                             <option>Inprogress</option>
                                             <option>Accept</option>
                                             <option>Decline</option>
                                         </select>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-xl-4">
                             <div class="text-xl-end mt-xl-0 mt-2">
                                 <button type="button" class="btn btn-secondary waves-effect waves-light mr-1 mb-2"
                                         id="resetFilter">
                                     <i class="mdi mdi-filter"></i> Reset Filters
                                 </button>
                                 <a href="{{route('quotes.new')}}" class="btn btn-info mb-2"><i
                                         class="mdi mdi-plus-circle"></i> Add Estimate</a>

                             </div>
                         </div><!-- end col-->
                     </div>--}}

                    <table id="estimate-datatable" class="table table-centered table-sm w-100">
                        <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="select_all"></th>
                            <th>Date</th>
                            <th>Estimate Number</th>
                            <th>Reference#</th>
                            <th>Customer Name</th>
                            <th>Expiry Date</th>
                            <th>Sub Total</th>
                            <th>Total</th>
                            <th>Salesman</th>
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


    <div id="follow-up-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-center" role="document"><!--  modal-lg modal-center-->
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title">Next Follow Up</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-uppercase bg-light pt-3 ps-2 pb-1 pe-2">
                        <p class="font-13"><strong>Customer/Mobile: </strong> <span
                                class="float-end estimate_customer_span"></span></p>
                        <p class="font-13"><strong>Estimate: </strong> <span
                                class="float-end estimate_span"></span></p>
                    </h5>
                    <form class="follow-up-form" id="follow-up-form" action="#" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-2 form-error">
                                <label for="notes" class="form-label">Estimate Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="estimate_status" name="estimate_status" required="">
                                    <option value="">Choose</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Sent">Sent</option>
                                    <option value="Inprogress">Inprogress</option>
                                    <option value="Accept">Accept</option>
                                    <option value="Decline">Decline</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2 form-error">
                                <h6 class="form-label font-14">Next follow up <span class="text-danger">*</span></h6>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="item_type_yes" name="next_follow_up"
                                           class="form-check-input" value="Yes" checked="">
                                    <label class="form-check-label" for="item_type_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="item_type_no" name="next_follow_up" class="form-check-input"
                                           value="No">
                                    <label class="form-check-label" for="item_type_no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="row follow-up-div">
                            <div class="col-md-6 mb-2 form-error">
                                <label for="name" class="form-label">Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="followup_date"
                                           class="form-control form-control-light" id="followup_date"
                                           value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" required>
                                    <input type="hidden" name="estimate_id" class="form-control" id="estimate_id"
                                           value="">
                                    <input type="hidden" name="id" class="form-control" id="id" value="">
                                    <input type="hidden" name="sp_flag" class="form-control" id="sp_flag" value="u">
                                    <input type="hidden" name="event_id" class="form-control" id="event_id" value="0">
                                    <span class="input-group-text bg-secondary border-secondary text-white">
                                            <i class="mdi mdi-calendar-range font-13"></i>
                                        </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2 form-error">
                                <label for="followup_time" class="form-label">Time <span
                                        class="text-danger">*</span></label>
                                <div class="input-group" data-placement="bottom" data-align="bottom"
                                     data-autoclose="true" data-default='now'>
                                    <input id="followup_time" name="followup_time" type="text" class="form-control"
                                           required value="">
                                    <span class="input-group-text bg-secondary border-secondary text-white"><i
                                            class="dripicons-clock"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-2 form-error">
                                <label for="notes" class="form-label">Notes <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="notes" name="notes"
                                          placeholder="Enter note" maxlength="225" data-toggle="maxlength"
                                          required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body pb-0">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="header-title mb-0">Follow up</h4>
                                        </div>
                                    </div>

                                    <div class="card-body timeline-list py-0" data-simplebar="init"
                                         style="max-height: 292px;overflow-y: auto;">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close
                    </button>
                    <button class="btn btn-secondary" id="follow_up_button" type="submit" form="follow-up-form"><i
                            class="uil-arrow-circle-right"></i> Save
                    </button>
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
    <script
        src="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js')}}"></script>

    <!-- third party js ends -->
    <script>
        var user_perm = <?php echo json_encode($user_perm); ?>;
        var company_id = '<?php echo $company_id; ?>';
        $(document).ready(function () {
            $('#followup_date').datepicker({
                startDate: new Date(),
                format: "dd/mm/yyyy",
                autoclose: true,
                daysOfWeekDisabled: [0, 7]
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#follow-up-form").parsley({
                errorsContainer: function (el) {
                    return el.$element.closest('.form-error');
                },
            });

            $('#followup_time').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                'default': 'now'
            });

            "use strict";

            var table = $("#estimate-datatable").DataTable({
                // dom: 'Bfrtip',

                dom:
                    "<'row'<'col-sm-12 col-md-6 text-left'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10', '25', '50', 'Show all']
                ],
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
                        title: 'Estimate List',
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
                        title: 'Estimate List',
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
                        title: 'Estimate List',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                    /*  {
                          text: '<i class="mdi mdi-plus-circle"></i> New',
                          attr: {
                              id: 'btn-add-estimate',
                              // class:'btn-info',
                          },
                          action: function ( e, dt, node, config ) {
                              window.location.href = '{{route('quotes.new')}}';
                        }
                    },*/
                    /* {
                         extend: 'print',
                         title: 'Estimate List',
                         customize: function (win) {
                             $(win.document.body)
                                 .css('font-size', '10pt')
                                 .prepend(
                                     '<img src="http://192.168.5.103:8080/assets/images/logo-dark.png" style="position:absolute; top:0; left:0;" />'
                                 );

                             $(win.document.body).find('table')
                                 .addClass('compact')
                                 .css('font-size', 'inherit');
                         },
                         exportOptions: {
                             columns: ':visible'
                         }
                     },*/

                    /* {
                         extend: 'csv',
                         title: 'Estimate List',
                         exportOptions: {
                             columns: ':visible'
                         }
                     },*/
                ],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                stateSaveParams: function (settings, data) {
                    @if(request()->get('status'))
                        data.fil_status = '{{(request()->get('status')!="Total")? request()->get('status'):''}}';
                    @else
                        data.fil_status = $('#fil_status').val();
                    @endif
                    data.fil_name = $('#fil_name').val();
                    data.fil_estimate_no = $('#fil_estimate_no').val();
                },
                stateLoadParams: function (settings, data) {
                    @if(request()->get('status'))
                        $('#fil_status').val('{{(request()->get('status')!="Total")? request()->get('status'):''}}');
                    @else
                        $('#fil_status').val(data.fil_status);
                    @endif

                    $('#fil_name').val(data.fil_name);
                    $('#fil_estimate_no').val(data.fil_estimate_no);
                },
                stateSaveCallback: function (settings, data) {
                    localStorage.setItem(settings.sInstance, JSON.stringify(data))
                },
                stateLoadCallback: function (settings) {
                    return JSON.parse(localStorage.getItem(settings.sInstance))
                },
                ajax: {
                    url: "{{ route('quotes.index') }}",
                    data: function (d) {
                        d.status = $('#fil_status').val(),
                            d.name = $('#fil_name').val(),
                            d.estimate_no = $('#fil_estimate_no').val(),
                            d.search = $('input[type="search"]').val()
                    }
                },
                "order": [
                    [0, 'desc'],

                ],
                // "order": [[ 1, "asc" ]],
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false,
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="single_checkbox form-check-input" data-id="' + row.action + '">';
                        },
                        "visible": false,
                    },
                    {data: 'estimate_date', name: 'estimate_date'},
                    {
                        data: 'estimate_no', name: 'estimate_no',
                        render: function (data, type, row) {
                            var edit_fun = "{{url('quotes/edit')}}/" + row.action;
                            return '<a class="fw-bold" href="' + edit_fun + '" id="edit_' + row.action + '">' + row.estimate_no + '</a>'
                        }
                    },
                    {data: 'reference', name: 'reference', 'visible': false},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'expiry_date', name: 'expiry_date', 'visible': false},
                    {data: 'subtotal', name: 'subtotal', 'visible': false},
                    {data: 'net_amount', name: 'total'},
                    {data: 'sales_person_name', name: 'sales_person_name'},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            var fun_status = "'" + row.action + "'";
                            var old_status = "'" + row.status + "'";
                            var sel_draft = '';
                            var sel_sent = '';
                            var sel_inprogress = '';
                            var sel_accept = '';
                            var sel_decline = '';
                            var sel_bg_color = '';
                            if (row.status == 'Draft') {
                                sel_draft = 'selected';
                                sel_bg_color = 'badge-outline-warning bg-warning-lighten';
                            }
                            if (row.status == 'Sent') {
                                sel_sent = 'selected';
                                sel_bg_color = 'badge-outline-info bg-info-lighten';
                            }

                            if (row.status == 'Inprogress') {
                                sel_inprogress = 'selected';
                                sel_bg_color = 'badge-outline-success bg-success-lighten';
                            }

                            if (row.status == 'Accept') {
                                sel_accept = 'selected';
                                sel_bg_color = 'badge-outline-danger bg-danger-lighten';
                            }

                            if (row.status == 'Decline') {
                                sel_decline = 'selected';
                                sel_bg_color = 'badge-outline-secondary bg-secondary-lighten';
                            }
                            let follow_action = "'" + row.action + "','" + row.estimate_no + "','" + row.customer_name + "','" + row.mobile_no + "'";
                            return '<select class=" badge ' + sel_bg_color + '" id="example-select_' + row.id + '" onchange="getval(this,' + follow_action + ')"><option ' + sel_draft + '>Draft</option><option ' + sel_sent + '>Sent</option><option ' + sel_inprogress + '>Inprogress</option><option ' + sel_accept + '>Accept</option> <option ' + sel_decline + '>Decline</option></select>';
                        }
                    },
                    {
                        data: 'action', name: 'action', orderable: false,
                        render: function (data, type, row) {

                            var edit_fun = "{{url('quotes/edit')}}/" + row.action;
                            var delete_fun = "remove_id('" + row.action + "','{{route('quotes.delete')}}','#estimate-datatable')";
                            {{--var follow_up_fun = "follow_up_list('" + row.action + "','{{route('event.index')}}','enc',0,1)";--}}
                            var duplicate_est_fun = "estimate_duplicate('" + row.action + "')";
                            var follow_up_fun = " openFollowUpModal('#follow-up-modal','Next Follow Up','#follow-up-form','.modal-title','" + row.action + "','0','" + row.estimate_no + "','" + row.status + "','{{route('event.index')}}',1,'" + row.customer_name + "','" + row.mobile_no + "')";
                            var view_fun = "{{url('quotes/show')}}/" + row.action;
                            if($.inArray('edit-estimate', user_perm) != -1 || company_id==''){
                                var edit ='<a href="' + edit_fun + '" title="Edit" class="action-icon me-1" id="edit_' + row.action + '">' +
                                    '<i class="mdi mdi-square-edit-outline fs-4"></i>' +
                                    '</a>';
                            }else{
                                var edit = '';
                            }
                            if($.inArray('remove-estimate', user_perm) != -1 || company_id==''){
                                var del = '<a href="javascript:void(0)" title="Delete" class="action-icon" id="remove_' + row.action + '" onclick="' + delete_fun + '">' +
                                    '<i class="mdi mdi-delete fs-4"></i>' +
                                    '</a>' ;
                            }else{
                                var del = '';
                            }
                            return '<div class="invoice-action">' +
                                // '<a href="' + edit_fun + '" title="Edit" class="action-icon me-1" id="edit_' + row.action + '">' +
                                // '<i class="mdi mdi-square-edit-outline fs-4"></i>' +
                                // '</a>'
                                edit +

                                '<a href="' + row.download_action + '" title="Download" class="action-icon me-1" download>' +
                                '<i class="mdi mdi-download fs-4"></i>' +
                                '</a>' +
                                /* '<a href="'+view_fun+'" title="View" class="anctio-icon me-1 text-muted" id="view_' + row.action + '">' +
                                 '<i class="mdi mdi-eye fs-4"></i>' +
                                 '</a>' +*/
                                ' <a href="javascript:void(0)" title="Follow up" id="follow_up_' + row.action + '"  onclick="' + follow_up_fun + '"><i class="mdi mdi mdi-av-timer me-1 text-muted fs-4"></i></a>' +
                                '<a href="javascript:void(0)" title="Share" class="copy_text" data-url="{!! url('/quotes/generate-link')!!}/' + row.action + '"><i class="mdi mdi-share-variant me-1 text-muted fs-4"></i></a>' +
                                ' <a href="javascript:void(0)" title="Duplicate" class="action-icon" id="duplicate_' + row.action + '" onclick="' + duplicate_est_fun + '">' +
                                '<i class="mdi mdi-content-copy fs-4"></i>' +
                                '</a>' +
                                // '<a href="javascript:void(0)" title="Delete" class="action-icon" id="remove_' + row.action + '" onclick="' + delete_fun + '">' +
                                // '<i class="mdi mdi-delete fs-4"></i>' +
                                // '</a>'
                                del +
                                '</div>';

                            /*  return '<div class="btn-group dropdown btn-group-sm">' +
                                  '<a href="#" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-xs" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>' +
                                  ' <div class="dropdown-menu dropdown-menu-end" style="">' +
                                  ' <a class="dropdown-item" href="javascript:void(0)" id="follow_up_' + row.action + '"  onclick="' + follow_up_fun + '"><i class="mdi mdi-timeline-clock me-2 text-muted vertical-middle"></i>Follow up</a>' +
                                  ' <a class="dropdown-item" href="' + view_fun + '" id="view_' + row.action + '"><i class="mdi mdi-eye me-2 text-muted vertical-middle"></i>View</a>' +
                                  ' <a class="dropdown-item" href="' + edit_fun + '" id="edit_' + row.action + '"><i class="mdi mdi-square-edit-outline me-2 text-muted vertical-middle"></i>Edit</a>' +
                                  ' <a class="dropdown-item" href="javascript:void(0)" id="remove_' + row.action + '" onclick="' + delete_fun + '"><i class="mdi mdi-delete me-2 text-muted vertical-middle"></i>Remove</a>' +
                                  ' <a class="dropdown-item" href="javascript:void(0)" id="remove_' + row.action + '" onclick="' + delete_fun + '"><i class="mdi mdi-content-copy me-2 text-muted vertical-middle"></i>Remove</a>' +
                                  ' </div>' +
                                  ' </div>'*/
                        }
                    },
                ],
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                },
                "fnCreatedRow": function (nRow, data, iDataIndex) {
                    $(nRow).attr('id', iDataIndex + 1);
                }
            });
            table.buttons().container().appendTo("#estimate-datatable_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
                pagingType: "full_numbers",
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            })

            table.on('click', 'tr a.copy_text', function (e) {
                e.preventDefault();
                var copyText = $(this).attr('data-url');

                document.addEventListener('copy', function (e) {
                    e.clipboardData.setData('text/plain', copyText);
                    e.preventDefault();
                }, true);
                document.execCommand('copy');
                toastrSuccess('Successfully copied url');
            });

            $('#fil_status,#fil_name,#fil_estimate_no').change(function () {
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

            $('.follow-up-form').on('submit', function (e) {
                e.preventDefault();
                if ($(this).parsley().isValid()) {
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: '{{route('event.store')}}',
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        dataType: "json",
                        beforeSend: function () {
                            $("#follow_up_button").prop('disabled', true);
                            $("#follow_up_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                        },
                        success: function (data) {
                            toastrSuccess('Successfully saved...', 'Success');
                            $("#follow_up_button").prop('disabled', false);
                            $("#follow_up_button").html('<i class="uil-arrow-circle-right"></i> Save');
                            $('#follow-up-modal').modal('toggle');
                            $("#estimate-datatable").DataTable().ajax.reload();
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
                            $("#follow_up_button").prop('disabled', false);
                            $("#follow_up_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        },
                        complete: function (data) {
                            $("#follow_up_button").html('Save');
                            $("#follow_up_button").prop('disabled', false);
                        }
                    });
                }
            });
        });

        function getval(sel, action, estimate_no, customer_name, mobile_no) {
            openFollowUpModal('#follow-up-modal', 'Next Follow Up', '#follow-up-form', '.modal-title', action, 0, estimate_no, sel.value, '{{route('event.index')}}', 1, customer_name, mobile_no)
        }
    </script>
@endpush
