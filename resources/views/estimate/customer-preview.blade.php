@extends('layouts.app-without-menu')
@section('title','Estimate')
@section('content')

    <div class="account-pages pt-0 pt-sm-2 pb-0 pb-sm-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-12 col-lg-12">
                    <div class="card">

                        <div class="p-4 card-header">
                            <h5 class="m-0 text-dark-50 pb-0 fw-bold text-dark">
                            <span class="float-end">
                                <a href="javascript: void(0);" class="text-dark">
                                    <span class="fs-5">Estimate #: {{$data['estimate_no']}}</span>
                                </a>
                                @if(!($data['status']=='Accept' || $data['status']=='Decline'))
                                    <div class="mt-3 mb-0 text-center action-button">
                                        <button class="btn btn-success btn-status" type="submit" data-type="Accept"><i
                                                class="mdi mdi-check"></i> Accept
                                        </button>
                                        <button class="btn btn-danger btn-status" type="submit" data-type="Decline"><i
                                                class="mdi mdi-close"></i> Decline
                                        </button>
                                    </div>
                                @else
                                    <div class="mt-2 mb-0 text-center action-button">
                                    {!! ($data['status']=='Accept')? "<span class='badge badge-outline-success bg-success-lighten'>Accepted":"<span class='badge badge-outline-secondary bg-secondary-lighten'>Declined</span>" !!}
                                    </div>
                                @endif
                            </span>
                                <p>{{$data['customer_name']}}<p>

                                <p><strong>₹{{$data['net_amount']}}</strong></p>
                            </h5>
                        </div>

                        <div class="card-body p-2 text-center">
                            <object
                                data='{{url('/'). Storage::url('document/'.$data['company_id'].'/' . $data['estimate_no'] . '.pdf')}}'
                                type="application/pdf"
                                style="width: 100%"
                                height="678"
                            >

                                <iframe
                                    src='{{url('/').Storage::url('document/'.$data['company_id'].'/' . $data['estimate_no'] . '.pdf')}}'

                                >
                                    <p>This browser does not support PDF!</p>
                                </iframe>
                            </object>
                            {{--<embed src="{{Storage::url('document/' . $data['estimate_no'] . '.pdf')}}" width="100%"
                                   height="1000" style="outline: none;"/>--}}
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->


                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
        $(document).on('click', '.btn-status', function (e) {
            let val = $(this).attr('data-type');
            $.ajax({
                async: false,
                type: 'POST',
                url: '{{route('quotes.update-status')}}',

                data: {val: val, id: '{{$data['id']}}'},
                dataType: "json",
                beforeSend: function () {
                    $(this).prop('disabled', true);
                    $(this).html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                },
                success: function (data) {
                    toastrSuccess('Successfully updated...');
                    $(".action-button").hide();
                    $(this).prop('disabled', false);
                    $(this).html('<i class="uil-arrow-circle-right"></i> ' + val);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    switch (xhr.status) {
                        case 401:
                            toastrError('Error in saving...');
                            break;
                        case 422:
                            toastrInfo('The category is invalid.');
                            break;
                        case 409:
                            toastrInfo('Name already exist.');
                            break;
                        default:
                            toastrError('Error - ' + errorMessage, 'Error');
                    }
                    $(this).prop('disabled', false);
                    $(this).html('<i class="uil-arrow-circle-right"></i> ' + val);
                },
                complete: function (data) {
                    $(this).html('Save');
                    $(this).prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                }
            });
        });</script>
@endpush
