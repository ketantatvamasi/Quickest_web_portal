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

                                </a>

                                    <div class="mt-3 mb-0 text-center action-button">
                                        <button class="btn btn-success btn-status" type="submit" data-type="Accept"><i
                                                class="mdi mdi-check"></i> Accept
                                        </button>
                                        <button class="btn btn-danger btn-status" type="submit" data-type="Decline"><i
                                                class="mdi mdi-close"></i> Decline
                                        </button>
                                    </div>

                            </span>

                            </h5>
                        </div>

                        <div class="card-body p-2 text-center">
                            <form id="myform" method="post" action="{{route('test-post')}}">
                                @csrf
                                <input type="text" name="name" />
                                <input type="submit" form="myform" value="Update"/>
                            </form>
                            @if(session()->has('categoryNameSession'))
                                <ul>

                                @foreach(Session::get('categoryNameSession') as $value)
                                    <li>{{$value}}</li>
                                @endforeach
                                </ul>
                            @endif


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

@endpush
