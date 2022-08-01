@extends('layouts.app')
@section('title','Follow up history')
@push('styles')
    <link href="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.css"
          rel="stylesheet" type="text/css">
    <style>
        .clockpicker-popover {
            z-index: 9999999999 !important;
        }
    </style>
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Follow Up</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-12 col-xxl-12">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title mb-0">History</h4>
                        <div>
                            <div class="col-12">
                                <!-- Predefined Date Ranges -->
                                    <div id="reportranges" class="form-control" data-toggle="date-picker-range" data-target-display="#selectedValue"  data-cancel-class="btn-light">
                                        <i class="mdi mdi-calendar"></i>&nbsp;
                                        <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body py-0" data-simplebar style="max-height: 492px;">
                    <div class="inbox-widget follow-up-list"></div>
                    <div class="text-center loader">
                        <i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div id="follow-up-modal" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="scrollableModalTitle" aria-hidden="true">
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
                                    <input type="radio" id="item_type_yes" name="next_follow_up" class="form-check-input" value="Yes" checked="">
                                    <label class="form-check-label" for="item_type_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="item_type_no" name="next_follow_up" class="form-check-input" value="No">
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
                                    <input type="hidden" name="estimate_id" class="form-control" id="estimate_id" value="">
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

                                    <div class="card-body timeline-list py-0" data-simplebar="init" style="max-height: 292px;overflow-y: auto;">

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
    <script async src="{{ asset('assets/js/app.min.js')}}"></script>
    <!-- third party js -->

    <!-- end demo js-->
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#followup_date').datepicker({
                startDate: new Date(),
                format: "dd/mm/yyyy",
                autoclose: true,
                daysOfWeekDisabled: [0,7]
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#followup_time').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                default: 'now',
            });

            var start = moment().subtract(89, 'days');
            var end = moment().subtract(1, 'days');

            if (localStorage.hasOwnProperty("start")) {
                start = moment(localStorage.getItem('start'));
            }
            if (localStorage.hasOwnProperty("end")) {
                end = moment(localStorage.getItem('end'));
            }
            function cb(start, end) {
                $('#reportranges span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                let date_range = start.format('YYYY-MM-DD')+'_'+end.format('YYYY-MM-DD');
                localStorage.setItem('start', start.format('YYYY-MM-DD'));
                localStorage.setItem('end', end.format('YYYY-MM-DD'));
                getDateWiseFollowUpList('{{route('event.get-date-wise-follow-up-list')}}',date_range,'{{route('event.index')}}');
            }
            $('#reportranges').daterangepicker({
                startDate: start,
                endDate: end,
                // "drops": "up",
                ranges: {
                    'Next 90 Days': [moment(),moment().add(89, 'days')],
                    'Next 30 Days': [moment(),moment().add(29, 'days')],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Past Quarter': [moment().subtract(89, 'days'), moment().subtract(1, 'days')]
                }
            }, cb);
            cb(start, end);
            $("#follow-up-form").parsley({
                errorsContainer: function (el) {
                    return el.$element.closest('.form-error');
                },
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
                            var t = $(".btn-follow-up-group .active").attr('data-id');
                            toastrSuccess('Successfully saved...', 'Success');
                            $("#follow_up_button").prop('disabled', false);
                            $("#follow_up_button").html('<i class="uil-arrow-circle-right"></i> Save');
                            $('#follow-up-modal').modal('toggle');
                            cb(moment(localStorage.getItem('start')), moment(localStorage.getItem('end')));
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

    </script>
@endpush
