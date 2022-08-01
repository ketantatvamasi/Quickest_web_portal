@extends('layouts.app')
@section('title','Proposal')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('quotes.index')}}">Proposal</a></li>
                        <li class="breadcrumb-item active">list</li>
                    </ol>
                </div>
                <h4 class="page-title">Proposal list</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        @if(!empty($data) && $data->count())
            @foreach($data as $key => $value)
                <div class="col-md-6 col-xxl-3">
                    <!-- project card -->
                    <div class="card d-block">
                        <div class="card-body">
                            <div class="dropdown card-widgets">
                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="dripicons-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    <!-- item-->
                                    <a href="{{route('proposal.new-create')}}" class="dropdown-item"><i
                                            class="mdi mdi-pencil me-1"></i>Edit</a>
                                    <!-- item-->
                                    <!-- item-->
                                    <a href="{{route('proposal.pdf-preview')}}" class="dropdown-item" target="_blank"><i class="uil uil-web-grid-alt"></i> Preview</a>
                                    <!-- item-->
                                   {{-- <a href="javascript:void(0);" class="dropdown-item"><i
                                            class="mdi mdi-delete me-1"></i>Delete</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                            class="mdi mdi-email-outline me-1"></i>Invite</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                            class="mdi mdi-exit-to-app me-1"></i>Leave</a>--}}
                                </div>
                            </div>
                            <!-- project title-->
                            <h4 class="mt-0">
                                <a href="{{route('proposal.new-create')}}" class="text-title">{{ $value->template_name }}</a>
                            </h4>
{{--                                <div class="badge bg-success">Finished</div>--}}

                            <img class="card-img-top" src="{{Storage::url('template/proposal-template.png')}}" alt="Proposal Template">

                            <!-- project detail-->
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            @endforeach
        @endif
            {{ $data->links('vendor.pagination.custom') }}

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <!-- third party js ends -->

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endpush
