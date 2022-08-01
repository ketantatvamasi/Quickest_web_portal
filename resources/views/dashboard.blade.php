@extends('layouts.app')
@section('title','Dashboard')
@push('styles')
    <link href="{{ asset('assets/css/vendor/fullcalendar.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.css"
          rel="stylesheet" type="text/css">
    <style>
        .modal-backdrop {
            z-index: 999999999 !important;
        }

        .modal {
            z-index: 9999999999 !important;

        }

        .clockpicker-popover {
            z-index: 9999999999 !important;
        }

        .fc-scrollgrid-sync-table {
            width: 100% !important;
        }

        .fc-daygrid-body {
            width: 100% !important;
        }

        .card.card-fullscreen {
            display: block;
            z-index: 9999;
            position: fixed;
            width: 100% !important;
            height: 100% !important;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            overflow: auto;

        .fc-daygrid-event-dot {

            border: calc(var(--fc-daygrid-event-dot-width, 8px) / 2) solid #fff;

        }

        #calendar {
            /*width: 200px;*/
            margin: 0 auto;
            font-size: 10px;
        }

        .fc-toolbar {
            font-size: .9em;
        }

        .fc-toolbar h2 {
            font-size: 12px;
            white-space: normal !important;
        }

        /* click +2 more for popup */
        .fc-more-cell a {
            display: block;
            width: 85%;
            margin: 1px auto 0 auto;
            border-radius: 3px;
            background: grey;
            color: transparent;
            overflow: hidden;
            height: 4px;
        }

        .fc-more-popover {
            width: 100px;
        }

        .fc-view-month .fc-event, .fc-view-agendaWeek .fc-event, .fc-content {
            font-size: 0;
            overflow: hidden;
            height: 2px;
        }

        .fc-view-agendaWeek .fc-event-vert {
            font-size: 0;
            overflow: hidden;
            width: 2px !important;
        }

        .fc-agenda-axis {
            width: 20px !important;
            font-size: .7em;
        }

        .fc-button-content {
            padding: 0;
        }
    </style>
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    {{--                    <form class="d-flex">--}}
                    {{--                        <div class="input-group">--}}
                    {{--                            <input type="text" class="form-control form-control-light" id="dash-daterange">--}}
                    {{--                            <span class="input-group-text bg-primary border-primary text-white">--}}
                    {{--                                                    <i class="mdi mdi-calendar-range font-13"></i>--}}
                    {{--                                                </span>--}}
                    {{--                        </div>--}}
                    {{--                        <a href="javascript: void(0);" class="btn btn-primary ms-2">--}}
                    {{--                            <i class="mdi mdi-autorenew"></i>--}}
                    {{--                        </a>--}}
                    {{--                        <a href="javascript: void(0);" class="btn btn-primary ms-1">--}}
                    {{--                            <i class="mdi mdi-filter-variant"></i>--}}
                    {{--                        </a>--}}
                    {{--                    </form>--}}
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6 col-lg-6">

            <div class="row">
                @foreach($widgets as $widget)
                    <div class="col-sm-6">
                        <a class="text-muted" href="{{url('quotes?status='.$widget['status'])}}">
                        <div class="card widget-flat">
                            <div class="card-body p-2">
                                <div class="float-end">
                                    <i class="mdi mdi-checkbox-marked-circle-outline widget-icon rounded-circle"></i>
{{--                                    <span class="widget-icon rounded-circle">{!! ($total)?number_format(($widget['widget_total'] *100)/ $total,2):number_format(0.00,2) !!}%</span>--}}
                                </div>
                                <h5 class="text-muted fw-normal mt-0"
                                    title="Number of Customers">{{$widget['status']}}</h5>
                                <h3 class="mt-2 mb-2">{{$widget['widget_total']}}</h3>
                                <p class="mb-0 text-muted text-start">
                                    <span class="text-primary me-2">{!! ($total)?number_format(($widget['widget_total'] *100)/ $total,2):number_format(0.00,2) !!}%</span>
{{--                                                                    <span class="text-nowrap">Since last month</span>--}}
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                        </a>
                    </div> <!-- end col-->
                @endforeach
            </div> <!-- end row -->


        </div> <!-- end col -->

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title mb-0">Today Follow Up</h4>
                    </div>
                </div>

                <div class="card-body py-0" data-simplebar style="max-height: 247px;min-height: 247px;">
                    <div class="inbox-widget follow-up-list">

                    </div>
                    <input type="hidden" id="start" value="0">
                    <input type="hidden" id="rowperpage" value="4">
                    <div class="text-center loader">
                        <i class="mdi mdi-dots-circle mdi-spin font-20 text-muted"></i>
                    </div>
                </div>
                <div class="justify-content-between align-items-center mb-3">
                    <div class="mt-1 text-center">
                        <a href="javascript:void(0);"
                           onclick="fn_follow_up_history('{{route('event.follow-up-history')}}')">View More<i
                                class="uil uil-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>

    {{-- <div class="row">
         @foreach($widgets as $widget)
             <div class="col-lg-6 col-xl-3">
                 <div class="card">
                     <div class="card-body">
                         <div class="row align-items-center">
                             <div class="col-6">

                                 <h5 class="text-muted fw-normal mt-0 text-truncate"
                                     title="Campaign Sent">{{$widget['status']}}</h5>
                                 <h3 class="my-2 py-1">{{$widget['widget_total']}}</h3>
                                 --}}{{--                            <p class="mb-0 text-muted">--}}{{--
                                 --}}{{--                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 3.27%</span>--}}{{--
                                 --}}{{--                            </p>--}}{{--
                             </div>
                             <div class="col-6">
                                 <div class="float-end">
                                     <i class="mdi mdi-pulse widget-icon"></i>
                                 </div>
                             </div>
                         </div> <!-- end row-->
                     </div> <!-- end card-body -->
                 </div> <!-- end card -->
             </div> <!-- end col -->
         @endforeach
     </div>--}}
    <!-- end row -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Estimate</h4>
                        {{-- <div id="bar_chart_date_range" class="form-control" data-toggle="date-picker-range"
                              data-target-display="#selectedValue" data-cancel-class="btn-light" style="max-width:50%;">
                             <i class="mdi mdi-calendar"></i>&nbsp;
                             <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                         </div>--}}
                        <div>
                            <select class="form-select form-select-sm" aria-label=".form-select-sm example" onchange="barChart(this.value)">
                                <option selected value="{{$bar_chart_filter['current_fiscal_year']}}">This Fiscal Year</option>
                                <option value="{{$bar_chart_filter['previous_fiscal_year']}}">Previous Fiscal Year</option>
                                <option value="{{$bar_chart_filter['last_twelve_month']}}">Last 12 Months</option>
                            </select>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart" class="apex-charts" data-colors="#ced1ff,#727cf5"></div>
                        {{--                        <div class="mt-3 chartjs-chart" style="height: 419px;">--}}
                        {{--                            <canvas id="bar-chart-example" data-colors="#fa5c7c,#727cf5"></canvas>--}}
                        {{--                        </div>--}}
                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title">Sales Performance</h4>
                        <div id="sales_performance_date_range" class="form-control" data-toggle="date-picker-range"
                             data-target-display="#selectedValue" data-cancel-class="btn-light" style="max-width:50%;">
                            <i class="mdi mdi-calendar"></i>&nbsp;
                            <span id="selectedValue"></span> <i class="mdi mdi-menu-down"></i>
                        </div>
                    </div>

                    <div id="sales-performance-chart" class="apex-charts mt-3"
                         data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>

                    <div class="row text-center mt-2">
                        <div class="col-6">
                            <h4 class="fw-normal">
                                <span id="total_task_span">0</span>
                            </h4>
                            <p class="text-muted mb-0">Total Task</p>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-normal">
                                <span id="completed_task_span">0</span>
                            </h4>
                            <p class="text-muted mb-0">Complated Task</p>
                        </div>
                    </div>
                    {{--                    <h4 class="header-title mb-4">Donut Chart</h4>--}}

                    {{--                    <div dir="ltr">--}}
                    {{--                        <div class="mt-3 chartjs-chart" style="height: 320px;">--}}
                    {{--                            <canvas id="donut-chart-example"--}}
                    {{--                                    data-colors="#6c757d,#39afd1,#ffbc00,#0acf97,#fa5c7c"></canvas>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">

            <div class="card">
                <div class="card-body" data-simplebar>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="header-title mb-3">Todo Task</h4>
                        <a data-action="expand" href="javascript:void(0);" class="btn btn-sm btn-link"><i
                                class="feather mdi mdi-arrow-expand-all icon-maximize"></i></a>
                    </div>
                    <div class="mt-4 mt-lg-0">
                        <div id="calendar"></div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->

        </div> <!-- end col -->

    {{--  <div class="col-xl-5 col-lg-6">

          <div class="card">
              <div class="card-body">
                  <div class="btn-group btn-group-sm float-end btn-follow-up-group">
                      <input type="button" title="All" aria-pressed="true"
                             class="fc-dayGridMonth-button btn btn-primary btn-follow-up" data-id="0" value="All">

                      <input type="button" title="Today" aria-pressed="true"
                             class="fc-dayGridMonth-button btn btn-primary btn-follow-up" data-id="1" value="Today">

                      <input type="button" title="Upcoming" aria-pressed="false"
                             class="fc-timeGridWeek-button btn btn-primary btn-follow-up" data-id="2"
                             value="Upcoming">

                      <input type="button" title="History" aria-pressed="false"
                             class="fc-timeGridDay-button btn btn-primary btn-follow-up" data-id="3" value="History">

                  </div>
                  <h4 class="header-title mb-2">Follow Up</h4>

                  <div data-simplebar="" style="max-height: 419px;">
                      <div data-simplebar="" style="max-height: 419px;">
                          <div class="timeline-alt timeline-list pb-0">

                          </div>
                          <!-- end timeline -->
                      </div> <!-- end slimscroll -->
                      <!-- end timeline -->
                  </div> <!-- end slimscroll -->
              </div>
              <!-- end card-body -->
          </div>
          <!-- end card-->
      </div>--}} <!-- end col -->
    </div>
    <!-- end row -->

    <!-- Add New Event MODAL -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="needs-validation" name="event-form" id="form-event" novalidate="" autocomplete="off">
                    <div class="modal-header py-3 px-4 border-bottom-0">
                        <h5 class="modal-title" id="modal-title">Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 pb-4 pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Event Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" placeholder="Insert Event Name" type="text" name="title"
                                           id="event-title" required="">
                                    <input class="form-control" placeholder="Insert Event Id" type="hidden" name="id"
                                           id="event-id" value="0" required="">
                                    <div class="invalid-feedback">Please provide a valid event name</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="followup_time" class="form-label">Time <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group clockpicker" data-placement="bottom" data-align="bottom"
                                         data-autoclose="true" data-default='now'>
                                        <input type="text" class="form-control" name="event_time" id="event-time"
                                               value="" required>
                                        <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="control-label form-label">Category</label>
                                    <select class="form-select" name="category" id="event-category" required="">
                                        <option value="bg-danger" selected="">Danger</option>
                                        <option value="bg-success">Success</option>
                                        <option value="bg-primary">Primary</option>
                                        <option value="bg-info">Info</option>
                                        <option value="bg-dark">Dark</option>
                                        <option value="bg-warning">Warning</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a valid event category</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> <!-- end modal-content-->
        </div> <!-- end modal dialog-->
    </div>
    <!-- end modal-->

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
{{--                                           data-provide="datepicker" data-single-date-picker="true"--}}
{{--                                           data-date-autoclose="true" data-date-format="d/m/yyyy"--}}
                                           value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" required data-start
                                           -date="now">
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
    <script async src="{{ asset('assets/js/app.min.js')}}"></script>
    <!-- third party js -->

    <!-- third party js ends -->
    <script src="{{ asset('assets/js/vendor/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor/Chart.bundle.min.js')}}"></script>
    <!-- demo app -->
    <script src="{{ asset('assets/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{ asset('assets/js/pages/demo.calendar.js')}}"></script>
    <script src="{{ asset('assets/js/pages/demo.chartjs.js')}}"></script>
    <script src="{{ asset('assets/js/pages/demo.dashboard-analytics.js')}}"></script>
    <!-- end demo js-->
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="https://coderthemes.com/ubold/layouts/default/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#followup_date').datepicker({
                startDate: new Date(),
                format: "dd/mm/yyyy",
                autoclose: true,
                daysOfWeekDisabled: [0,7]
            });
            var fil_sp_chart_start = moment();
            var fil_sp_chart_end = moment();

            if (localStorage.hasOwnProperty("fil_sp_chart_start")) {
                fil_sp_chart_start = moment(localStorage.getItem('fil_sp_chart_start'));
            }
            if (localStorage.hasOwnProperty("fil_sp_chart_end")) {
                fil_sp_chart_end = moment(localStorage.getItem('fil_sp_chart_end'));
            }

            var fil_bar_chart_start = '{{$bar_chart_filter['fd']}}';
            var fil_bar_chart_end = '{{$bar_chart_filter['ed']}}';

            if (localStorage.hasOwnProperty("fil_bar_chart_start")) {
                fil_bar_chart_start = moment(localStorage.getItem('fil_bar_chart_start'));
            } else{
                // localStorage.setItem('fil_bar_chart_start', fil_bar_chart_start.format('YYYY-MM-DD'));
                localStorage.setItem('fil_bar_chart_start', moment(fil_bar_chart_start, 'YYYY-MM-DD').format("YYYY-MM-DD"));
            }
            if (localStorage.hasOwnProperty("fil_bar_chart_end")) {
                fil_bar_chart_end = moment(localStorage.getItem('fil_bar_chart_end'));
            }else{
                localStorage.setItem('fil_bar_chart_end', moment(fil_bar_chart_end, 'YYYY-MM-DD').format("YYYY-MM-DD"));
            }

            function cb(fil_sp_chart_start, fil_sp_chart_end, flg = 0) {
                $('#sales_performance_date_range span').html(fil_sp_chart_start.format('MMMM D, YYYY') + ' - ' + fil_sp_chart_end.format('MMMM D, YYYY'));
                let date_range = fil_sp_chart_start.format('YYYY-MM-DD') + '_' + fil_sp_chart_end.format('YYYY-MM-DD');
                localStorage.setItem('fil_sp_chart_start', fil_sp_chart_start.format('YYYY-MM-DD'));
                localStorage.setItem('fil_sp_chart_end', fil_sp_chart_end.format('YYYY-MM-DD'));
                salesPerformanceChart(date_range, '{{route('chart.salesPerformanceChart')}}');
            }

            // function cbBar(fil_bar_chart_start, fil_bar_chart_end) {
            //     let date_range = fil_bar_chart_start.format('YYYY-MM-DD') + '_' + fil_bar_chart_end.format('YYYY-MM-DD');
            //     localStorage.setItem('fil_bar_chart_start', fil_bar_chart_start.format('YYYY-MM-DD'));
            //     localStorage.setItem('fil_bar_chart_end', fil_bar_chart_end.format('YYYY-MM-DD'));
            //     barChart(date_range);
            // }
             barChart(moment(fil_bar_chart_start, 'YYYY-MM-DD').format("YYYY-MM-DD") + '_' + moment(fil_bar_chart_end, 'YYYY-MM-DD').format("YYYY-MM-DD"));

            $('#sales_performance_date_range').daterangepicker({
                startDate: fil_bar_chart_start,
                endDate: fil_bar_chart_end,
                // "drops": "up",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(fil_sp_chart_start, fil_sp_chart_end);

            $('a[data-action="collapse"]').on("click", (function (e) {
                e.preventDefault(),
                    $(this).closest(".card").children(".card-body").collapse("toggle"),
                    $(this).closest(".card").find('[data-action="collapse"] i').toggleClass("icon-plus icon-minus")
            }));
            $('a[data-action="expand"]').on("click", (function (e) {
                e.preventDefault(),
                    $(this).closest(".card").find('[data-action="expand"] i').toggleClass("icon-maximize icon-minimize"),
                    $(this).closest(".card").toggleClass("card-fullscreen"),
                    $(this).closest(".card").find('.calendar .fc-daygrid-body').css({"width": "100% !important"})
            }));

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

            getDateWiseFollowUpList("{{route('event.get-date-wise-follow-up-list')}}", "{{date('Y-m-d')}}", "{{route('event.index')}}")

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
                            getDateWiseFollowUpList("{{route('event.get-date-wise-follow-up-list')}}", "{{date('Y-m-d')}}", "{{route('event.index')}}")
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
            $(".clockpicker,#followup_time").clockpicker();

        });

    </script>
@endpush
