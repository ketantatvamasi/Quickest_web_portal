@extends('layouts.app')
@section('title','Estimate')
@push('styles')
    <style>
        .container-fluid {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
        #div-to-update{
            height:100px;
            overflow-y:scroll;
        }
    </style>

@endpush
@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('quotes.index')}}">Estimate</a></li>
                        <li class="breadcrumb-item active">Print</li>
                    </ol>
                </div>
                <h4 class="page-title">Estimate</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row d-print-none">
        <div class="col-xl-12 col-lg-12 order-lg-1">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-2">Recent Activity</h4>

                    <div id="div-to-update">
                        <div class="auto-load"></div>
                        <div class="timeline-alt recent-activities-list pb-0">


                        </div>
                        <!-- end timeline -->
                    </div> <!-- end slimscroll -->
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card-->
        </div>
        <!-- end col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card ribbon-box" style="margin-bottom: 0px;">
                <div class="card-body">
                    <div
                        class="ribbon-two d-print-none ribbon-two-{{$estimate->status=='Draft'? 'secondary' : ($estimate->status=='Sent'? 'info' : ($estimate->status=='Inprogress'? 'warning' : ($estimate->status=='Accept'? 'success' : ($estimate->status=='Decline'? 'danger' : ''))))}}">
                        <span>{{$estimate->status}}</span></div>
                    <!-- Invoice Logo-->
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <img src="{{asset('assets/images/logo.png')}}" alt="" height="50">
                        </div>
                        <div class="float-end">
                            <h2 class="m-0" style="color:{{$proposal_template->theme_color_one}}">{{($proposal_template->est_title)?$proposal_template->est_title : 'Estimate' }}</h2>
                        </div>
                    </div>

                    <!-- Invoice Detail-->

                    <div class="row">
                        <div class="col-4">
                            <h4 style="color:{{$proposal_template->theme_color_one}}">{{$company_data->company_name}}</h4>
                            <address>
                                {{$company_data->name}}<br>
                                {{$company_data->address.', '.$company_data->city_name}}<br>
                                {{$company_data->state_name.', '.$company_data->pincode}}<br>
                                {{$company_data->country_name}}<br>
                                {{'Mobile :'.$company_data->mobile_no}}
                            </address>
                        </div> <!-- end col-->

                        <div class="col-4">
                            <h6 style="color:{{$proposal_template->theme_color_one}}">Bill To</h6>
                            <h4>{{$estimate->customer_name}}</h4>
                            <address>
                                {!! nl2br(e($estimate->customer_address)) !!}
                            </address>
                        </div> <!-- end col-->

                        <div class="col-4">
                            <div class="mt-3 float-sm-end">
                                <p class="font-13">
                                    <strong style="color:{{$proposal_template->theme_color_one}}">Date: </strong> {{\Carbon\Carbon::parse($estimate->estimate_date)->format('d M, Y')}}
                                </p>
                                <p class="font-13"><strong style="color:{{$proposal_template->theme_color_one}}">Expiry
                                        Date: </strong> {{\Carbon\Carbon::parse($estimate->expiry_date)->format('d M, Y')}}
                                </p>
                                <p class="font-13"><strong style="color:{{$proposal_template->theme_color_one}}">Estimate#: </strong> {{$estimate->estimate_no}}</p>
                            </div>
                        </div> <!-- end col-->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm mt-4">
                                    <thead>
                                    <tr>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_no)?$proposal_template->item_table_no : '#'!!}
                                        </th>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_item)?$proposal_template->item_table_item : 'Item & Description'!!}
                                        </th>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_hsn)?$proposal_template->item_table_hsn : 'HSN/SAC'!!}
                                        </th>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_qty)?$proposal_template->item_table_qty : 'Qty'!!}
                                        </th>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_rate)?$proposal_template->item_table_rate : 'Rate'!!}
                                        </th>
                                        <th style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_discount)?$proposal_template->item_table_discount : 'Discount'!!}
                                        </th>
                                        <th class="item_table_cgst {{($company_data->state_id == $estimate->customer_state_id)?'':'d-none'}}" style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_cgst)?$proposal_template->item_table_cgst : 'CGST'!!}
                                        </th>
                                        <th class="item_table_sgst {{($company_data->state_id == $estimate->customer_state_id)?'':'d-none'}}" style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_sgst)?$proposal_template->item_table_sgst : 'SGST'!!}
                                        </th>
                                        <th class="item_table_igst {{($company_data->state_id == $estimate->customer_state_id)?'d-none':''}}" style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_igst)?$proposal_template->item_table_igst : 'IGST'!!}
                                        </th>
                                        <th class="text-end item_table_total" style="color:{{$proposal_template->theme_color_one}}">
                                            {!!($proposal_template->item_table_total)?$proposal_template->item_table_total : 'Total'!!}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($estimate_items as $key =>$estimate_item)

                                        <tr id="{{ ++$key }}">
                                            <td>{{ $key }}</td>
                                            <td>
                                                <b>{{$estimate_item->item_name}}</b>
                                                {!!($estimate_item->item_name)?'<br>'.nl2br(e($estimate_item->item_description)):''!!}
                                            </td>
                                            <td>
                                                {{$estimate_item->hsn_code}}
                                            </td>
                                            <td>
                                                {{$estimate_item->quantity}}
                                            </td>
                                            <td>
                                                {{'₹ '.$estimate_item->price}}
                                            </td>
                                            <td>
                                                {{$estimate_item->discount}}{{($estimate_item->discount_flag==1)?' %': ' ₹'}}

                                            </td>
                                            <td class="{{($company_data->state_id == $estimate->customer_state_id)?'':'d-none'}}">{{'₹ '.$estimate_item->sgst_amount}}
                                                <br><small>{{($estimate_item->gst_per/2).' %'}}</small></td>
                                            <td class="{{($company_data->state_id == $estimate->customer_state_id)?'':'d-none'}}">{{'₹ '.$estimate_item->cgst_amount}}
                                                <br><small>{{($estimate_item->gst_per/2).' %'}}</small></td>
                                            <td class="{{($company_data->state_id == $estimate->customer_state_id)?'d-none':''}}">{{'₹ '.$estimate_item->igst_amount}}
                                                <br><small>{{$estimate_item->gst_per.' %'}}</small></td>
                                            <td class="text-end">{{'₹ '.$estimate_item->total}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end table-responsive-->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-6 fs-5 text-center align-self-center text-muted">
                            <b>{{ \App\Helpers\LogActivity::convertToIndianCurrency($estimate->net_amount) }}</b>

                        </div> <!-- end col -->
                        <div class="col-6">
                            <div class="float-end mt-2 mt-sm-0">
                                <p><b style="color:{{$proposal_template->theme_color_one}}">Sub-total:</b> <span class="float-end">{{'₹ '.$estimate->subtotal}}</span></p>
                                <p><b style="color:{{$proposal_template->theme_color_one}}">TAX:</b> <span class="float-end">{{'₹ '.$estimate->total_igst_amount}}</span></p>
                                @if($estimate->addless_amount)
                                    <p><b style="color:{{$proposal_template->theme_color_one}}">{{$estimate->addless_title}}:</b> <span
                                            class="float-end">{{'₹ '.$estimate->addless_amount}}</span></p>
                                @endif
                                <h3 style="color:{{$proposal_template->theme_color_one}}">{{'₹ '.  $estimate->net_amount}} INR</h3>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="pt-2">
                                <h6 style="color:{{$proposal_template->theme_color_one}}">Notes:</h6>
                                <small>
                                    {!! nl2br(e($estimate->customer_notes)) !!}
                                </small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="pt-2">
                                <h4 style="color:{{$proposal_template->theme_color_one}}"> {!! ($proposal_template->est_bank_label)? html_entity_decode($proposal_template->est_bank_label, ENT_QUOTES, 'UTF-8') : html_entity_decode('Bank Detail :', ENT_QUOTES, 'UTF-8')!!}</h4>
                                <small>
                                    {!! ($proposal_template->est_bank_details)? html_entity_decode($proposal_template->est_bank_details, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p>Bank Name:- ICICI BANK LTD</p><p>Account Name.:- HEAVEN DESIGNS PRIVATE LIMITED</p><p>Account No.:- 183605002858</p><p>ISFC :- ICIC0001836</p><p>Banch:- KATARGAM - SURAT</p>', ENT_QUOTES, 'UTF-8')!!}
                                </small>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end col -->
                    <!-- end row-->

                    <div class="row pt-2">
                        <div class="col-6">
                            <div class="pt-2">
                                <h6 style="color:{{$proposal_template->theme_color_one}}">{!! ($proposal_template->est_term_condition_lable)? html_entity_decode($proposal_template->est_term_condition_lable, ENT_QUOTES, 'UTF-8') : html_entity_decode('Terms &amp; Conditions:', ENT_QUOTES, 'UTF-8')!!}</h6>
                                <small>
                                    {!! nl2br(e($estimate->term_condition)) !!}
                                </small>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-lg-6 align-self-end">
                            <div class="w-25 float-end">
                                <img src="{{Storage::url(trim($proposal_template->est_signature_img))}}" alt="" class="img-fluid">
                                <p class="border-top" style="color:{{$proposal_template->theme_color_one}}">{!! ($proposal_template->est_signature_lable)? html_entity_decode($proposal_template->est_signature_lable, ENT_QUOTES, 'UTF-8') : html_entity_decode('Authorized Signature', ENT_QUOTES, 'UTF-8')!!}</p>
                            </div>
                        </div>
                    </div> <!-- end col -->
                    <!-- end row-->

                    <div class="d-print-none mt-4">
                        <div class="text-end">
                            <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i>
                                Print</a>

                        </div>
                    </div>
                    <!-- end buttons -->

                </div> <!-- end card-body-->
            </div> <!-- end card -->
        </div> <!-- end col-->
    </div>



@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>


    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js')}}"></script>

    <!-- third party js ends -->

    <script>
        var page = 1;




        function infinteLoadMore(page) {
            $.ajax({
                url: SITEURL + "/get-recent-activities?page=" + page,
                data: {id: '{{$estimate->id}}'},
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $(".auto-load").html('<div class="align-items-center text-center"><div class="spinner-border ms-auto" role="status" aria-hidden="true"></div></div>').show();
                }
            }).done(function (response) {

                if (response.length == 0) {
                    $('.auto-load').html("We don't have more data to display :(");
                    return ;
                }
                $('.auto-load').hide();
                $(".recent-activities-list").append(response);
            })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
        }
        $('#div-to-update').on('scroll', function() {


            // if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            if(($(this).height() <= $(window).scrollTop() + $(window).height())) {
                page++;
                infinteLoadMore(page);
            }
        })
        $(document).ready(function () {


            // $(window).scroll(function () {
            //
            //     var position = $(window).scrollTop();
            //     var bottom = $(document).height() - $(window).height();
            //     if (position == bottom) {
            //         page++;
            //         infinteLoadMore(page);
            //     }
            // });

            infinteLoadMore(page);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

            });
        });
    </script>
@endpush
