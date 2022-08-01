<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{URL('/')}}">Dashbaord</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{$pagename}}</a></li>
                    <li class="breadcrumb-item active">{{$pagetitle}}</li>
                </ol>
            </div>
            <h4 class="page-title">{{$pagetitle}}</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="col-md-12">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
</div>
