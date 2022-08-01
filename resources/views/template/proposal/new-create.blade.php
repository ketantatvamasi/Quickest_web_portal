@extends('layouts.app')
@section('title','Proposal')
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css">
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
                            <li class="breadcrumb-item"><a href="{{route('quotes.index')}}">Proposal</a></li>
                            <li class="breadcrumb-item active">New</li>
                        </ol>
                    </div>
                    <h4 class="page-title">New Proposal</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form class="proposal-template-form" id="proposal-template-form" method="post">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name" class="form-label"> Template Name <span class="text-danger">*</span></label>
                                    <div class="mb-3">
                                        <input type="text" name="template_name" class="form-control" id="template_name" placeholder="Enter template name" required value="{{$proposal_template->template_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="customer_name" class="form-label">Theme color</label>
                                    <div class="mb-3">
                                        <input class="form-control form-control-color color_picker form-check-inline"
                                               id="color_picker_one" type="color" name="theme_color_one" type="color"
                                               value="{{$proposal_template->theme_color_one}}"
                                               pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"/>
                                        <input class="form-control form-control-color color_picker form-check-inline"
                                               id="color_picker_two" type="color" name="theme_color_two" type="color"
                                               value="{{$proposal_template->theme_color_two}}"
                                               pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$"/>
                                    </div>
                                </div>

                            </div> <!-- end row -->
                            <div class="accordion custom-accordion" id="custom-accordion-one">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingFour">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-block py-1"
                                               data-bs-toggle="collapse" href="#collapseFour"
                                               aria-expanded="true" aria-controls="collapseFour">
                                                Cover Page <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapseFour" class="collapse show"
                                         aria-labelledby="headingFour"
                                         data-bs-parent="#custom-accordion-one">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="card border border">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Image Setting</h5>
                                                            <ul class="nav nav-tabs nav-bordered mb-3">
                                                                <li class="nav-item">
                                                                    <a href="#cover-page-logo-b1" data-bs-toggle="tab"
                                                                       aria-expanded="false" class="nav-link active">
                                                                        <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Logo</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#cover-page-img-b1" data-bs-toggle="tab"
                                                                       aria-expanded="true" class="nav-link">
                                                                        <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Cover</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#page-setting-b1" data-bs-toggle="tab"
                                                                       aria-expanded="true" class="nav-link">
                                                                        <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                        <span
                                                                            class="d-none d-md-block">Page Setting</span>
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content">
                                                                <div class="tab-pane show active"
                                                                     id="cover-page-logo-b1">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <label class="form-label">Logo <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="file"
                                                                                       id="header_logo"
                                                                                       name="header_logo"
                                                                                       onchange="image_preview(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="mb-3">
                                                                                <img
                                                                                    src="{{Storage::url($proposal_template->header_logo)}}"
                                                                                    alt="image"
                                                                                    class="img-fluid avatar-xl rounded-circle"/>
                                                                            </div>
                                                                        </div>

                                                                        <h5 class="mb-3 text-uppercase bg-light p-2"><i
                                                                                class="mdi mdi-office-building me-1"></i>
                                                                            Logo Position</h5>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Left <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="text"
                                                                                       id="header_logo_left"
                                                                                       name="header_logo_left"
                                                                                       value="{{($proposal_template->header_logo_left)?$proposal_template->header_logo_left:164}}"
                                                                                >
                                                                            </div>
                                                                        </div>


                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Top <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="text"
                                                                                       id="header_logo_top"
                                                                                       name="header_logo_top"
                                                                                       value="{{($proposal_template->header_logo_top)?$proposal_template->header_logo_top:2}}"
                                                                                >
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Size <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="text"
                                                                                       id="header_logo_size"
                                                                                       name="header_logo_size"
                                                                                       value="{{($proposal_template->header_logo_size)?$proposal_template->header_logo_size:40}}"
                                                                                >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="tab-pane" id="cover-page-img-b1">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label class="form-label">Image <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="file"
                                                                                       id="cover_img" name="cover_img"
                                                                                       onchange="cover_preview(event)">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">


                                                                            <div class="mb-3">
                                                                                <img
                                                                                    src="{{Storage::url($proposal_template->cover_img)}}"
                                                                                    alt="image"
                                                                                    class="img-fluid avatar-xl rounded-circle"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="tab-pane" id="page-setting-b1">
                                                                    <div class="row">
                                                                        <h5 class="mb-3 text-uppercase bg-light p-2"><i
                                                                                class="mdi mdi-office-building me-1"></i>
                                                                            Page Margin</h5>
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Top <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="text"
                                                                                       id="page_top_margin"
                                                                                       name="page_top_margin"
                                                                                       value="{{($proposal_template->page_top_margin)?$proposal_template->page_top_margin:164}}"
                                                                                >
                                                                            </div>
                                                                        </div>


                                                                        {{--<div class="col-md-6">
                                                                            <label class="form-label">Dimension <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <select class="form-select"
                                                                                        id="logo-div-dimension"
                                                                                        name="logo_dimension_one"
                                                                                        onchange="getDivVal(this.value);">
                                                                                    <option>25</option>
                                                                                    <option>50</option>
                                                                                    <option>75</option>
                                                                                    <option>100</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>--}}

                                                                        {{--<div class="col-md-6">
                                                                            <label class="form-label">Image
                                                                                Dimension<span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <select class="form-select"
                                                                                        id="logo-img-dimension"
                                                                                        name="logo_dimension_img"
                                                                                        onchange="getImgVal(this.value);">
                                                                                    <option>25</option>
                                                                                    <option>50</option>
                                                                                    <option>75</option>
                                                                                    <option>100</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- end card-body-->
                                                    </div>

                                                    <div class="card border">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Content Setting</h5>
                                                            <ul class="nav nav-tabs nav-bordered mb-3">
                                                                <li class="nav-item">
                                                                    <a href="#cover-title-b1" data-bs-toggle="tab"
                                                                       aria-expanded="false" class="nav-link active">
                                                                        <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Title</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#cover-content-b1" data-bs-toggle="tab"
                                                                       aria-expanded="true" class="nav-link">
                                                                        <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Content</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#footer-one-b1" data-bs-toggle="tab"
                                                                       aria-expanded="false" class="nav-link">
                                                                        <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Footer 1</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="#footer-two-b1" data-bs-toggle="tab"
                                                                       aria-expanded="false" class="nav-link">
                                                                        <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                                        <span class="d-none d-md-block">Footer 2</span>
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content">
                                                                <div class="tab-pane show active" id="cover-title-b1">
                                                                    <label for="name" class="form-label"> Cover page
                                                                        title <span class="text-danger">*</span></label>
                                                                    <textarea id="editor" name="cover_title"
                                                                              data-toggle="maxlength"
                                                                              class="form-control" maxlength="225"
                                                                              rows="3"
                                                                              placeholder="This textarea has a limit of 225 chars.">{{($proposal_template->cover_title)? html_entity_decode($proposal_template->cover_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1><span style="color:#000000"><strong>Roof Top Solar Proposal</strong></span></h1>', ENT_QUOTES, 'UTF-8')}}</textarea>
                                                                </div>
                                                                <div class="tab-pane" id="cover-content-b1">
                                                                    <label for="name" class="form-label"> Cover main
                                                                        text <span class="text-danger">*</span></label>
                                                                    <div class="mb-3">
                                                                        <textarea id="cover_main_text"
                                                                                  name="cover_content"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->cover_content)? html_entity_decode($proposal_template->cover_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1 style="text-align:center"><span style="color:#ecf0f1"><span style="font-size:48px"><strong>Mr.&nbsp;${customers.name}&nbsp;</strong></span></span></h1>
<p style="text-align:center"><span style="color:#ecf0f1"><span style="font-size:22px">3.3KW Solar plant at&nbsp;${customers.address} ${customers.city_name}&nbsp;${customers.pincode}</span></span></p>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="footer-one-b1">
                                                                    <label for="name" class="form-label"> Footer 1 <span class="text-danger">*</span></label>
                                                                    <div class="mb-3">
                                                                        <textarea id="cover_footer_one"
                                                                                  name="cover_footer_one"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->cover_footer_one)? html_entity_decode($proposal_template->cover_footer_one, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><span style="color:#000000"><span style="font-size:36px"><strong>${companies.company_name}</strong></span></span></p>

<p><span style="color:#000000"><span style="font-size:20px">${companies.address}&nbsp;${companies.city_name} ${companies.pincode}</span></span></p>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="footer-two-b1">
                                                                    <label for="name" class="form-label"> Footer 2 <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="mb-3">
                                                                        <textarea id="cover_footer_two"
                                                                                  name="cover_footer_two"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->cover_footer_two)? html_entity_decode($proposal_template->cover_footer_two, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><span style="color:#000000"><em><span style="font-size:18px"><strong>ID :</strong> ${estimates.estimate_no}</span></em></span></p>
<p><span style="color:#000000"><em><span style="font-size:18px"><strong>Date :</strong> ${estimates.estimate_date}</span></em></span></p>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end card-body-->
                                                    </div>
                                                </div>
                                                <div class="col-md-3">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingFive">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseFive"
                                                   aria-expanded="false" aria-controls="collapseFive">
                                                    About Us <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="card border border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Image Setting</h5>
                                                                <div class="tab-content">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <label class="form-label">Image <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="file"
                                                                                       id="aboutas_img"
                                                                                       name="aboutas_img"
                                                                                       onchange="cover_preview_aboutus(event)">
                                                                            </div>
                                                                        </div>

                                                                        {{--<div class="col-md-4">
                                                                            <label class="form-label">Logo Dimension
                                                                                <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <select class="form-select"
                                                                                        id="logo-div-dimension-aboutus"
                                                                                        name="aboutas_logo_dimension"
                                                                                        onchange="getDivValAboutUs(this.value);">
                                                                                    <option value="">Choose..</option>
                                                                                    <option>5%</option>
                                                                                    <option>10%</option>
                                                                                    <option>15%</option>
                                                                                    <option>20%</option>
                                                                                    <option>25%</option>
                                                                                    <option>30%</option>
                                                                                    <option>35%</option>
                                                                                    <option>40%</option>
                                                                                    <option>45%</option>
                                                                                    <option>50%</option>
                                                                                    <option>55%</option>
                                                                                    <option>60%</option>
                                                                                    <option>65%</option>
                                                                                    <option>70%</option>
                                                                                    <option>75%</option>
                                                                                    <option>80%</option>
                                                                                    <option>85%</option>
                                                                                    <option>90%</option>
                                                                                    <option>95%</option>
                                                                                    <option>100%</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>--}}
                                                                    </div>
                                                                </div>
                                                            </div><!-- end card-body-->
                                                        </div>

                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#aboutus-title-b1" data-bs-toggle="tab"
                                                                           aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Title</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#aboutus-content-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Content</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="aboutus-title-b1">
                                                                        <label for="name" class="form-label"> Cover page
                                                                            title <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea id="editor_aboutus"
                                                                                  name="aboutas_title"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->aboutas_title)? html_entity_decode($proposal_template->aboutas_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><span style="color:#000000"><strong>Why choose us? What can we do for you? What do we offer?</strong></span></p>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                    <div class="tab-pane" id="aboutus-content-b1">
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="aboutus_main_text"
                                                                                      name="aboutas_content"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->aboutas_content)? html_entity_decode($proposal_template->aboutas_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1>About Heaven Solar</h1>
                                                                                <p><strong>What is Lorem Ipsum?</strong><br />
                                                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&quot;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br />
                                                                                <strong>Why do we use it?</strong><br />
                                                                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &quot;Content here, content here&quot;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &quot;lorem ipsum&quot; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingSix">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseSix"
                                                   aria-expanded="false" aria-controls="collapseSix">
                                                    Product <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#product-title-b1" data-bs-toggle="tab"
                                                                           aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Title</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#product-content-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Content</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="product-title-b1">
                                                                        <label for="name" class="form-label"> Cover page
                                                                            title <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea id="editor_product"
                                                                                  name="product_title"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->product_title)? html_entity_decode($proposal_template->product_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1>Your Solar Plant Design</h1>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                    <div class="tab-pane" id="product-content-b1">
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="product_main_text"
                                                                                      name="product_content"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->product_content)? html_entity_decode($proposal_template->product_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><strong>This is pre-design or reference photo of your solar power plant.</strong></p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingSeven">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseSeven"
                                                   aria-expanded="false" aria-controls="collapseSeven">
                                                    Estimate <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="card border border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Config</h5>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Title <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <input class="form-control" type="text"
                                                                                   id="estimate_title" name="est_title"
                                                                                   value="{{ ($proposal_template->est_title)?$proposal_template->est_title : 'Estimate' }}">
                                                                        </div>
                                                                    </div>

                                                                    {{--<div class="col-md-4">
                                                                        <label class="form-label">Logo Dimension <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <select class="form-select"
                                                                                    id="logo-div-dimension-estimate"
                                                                                    name="est_logo_dimension"
                                                                                    onchange="getDivValEstimate(this.value);">
                                                                                <option value="">Choose..</option>
                                                                                <option>5</option>
                                                                                <option>10</option>
                                                                                <option>15</option>
                                                                                <option>20</option>
                                                                                <option>25</option>
                                                                                <option>30</option>
                                                                                <option>35</option>
                                                                                <option>40</option>
                                                                                <option>45</option>
                                                                                <option>50</option>
                                                                                <option>55</option>
                                                                                <option>60</option>
                                                                                <option>65</option>
                                                                                <option>70</option>
                                                                                <option>75</option>
                                                                                <option>80</option>
                                                                                <option>85</option>
                                                                                <option>90</option>
                                                                                <option>95</option>
                                                                                <option>100</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>--}}

                                                                    {{--<div class="col-md-4">
                                                                        <label class="form-label">Theme Color <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <select class="form-select" id="theme-color"
                                                                                    onchange="getDivValThemeEstimate(this.value);">
                                                                                <option value="#000000">Black</option>
                                                                                <optgroup label="Vibrant">
                                                                                    <option value="#2F81B7">Blue
                                                                                    </option>
                                                                                    <option value="#30AD63">Green
                                                                                    </option>
                                                                                    <option value="#FA6F57">Orange
                                                                                    </option>
                                                                                    <option value="#BE3A31">Red</option>
                                                                                    <option value="#0B685C">Teal
                                                                                    </option>
                                                                                    <option value="#7A5A9E">Purple
                                                                                    </option>
                                                                                    <option value="#3B90EC">Light Blue
                                                                                    </option>
                                                                                    <option value="#134A9E">Indigo
                                                                                    </option>
                                                                                    <option value="#CE3B5A">Pink
                                                                                    </option>
                                                                                </optgroup>
                                                                                <optgroup label="Formal">
                                                                                    <option value="#8A5A49">Brown
                                                                                    </option>
                                                                                    <option value="#3F6167">Turquoise
                                                                                        Green
                                                                                    </option>
                                                                                    <option value="#4D5973">Blue Gray
                                                                                    </option>
                                                                                    <option value="#239F85">Grean Sea
                                                                                    </option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>--}}
                                                                </div>
                                                            </div><!-- end card-body-->
                                                        </div>

                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#estimate-item-table-b1"
                                                                           data-bs-toggle="tab" aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Item Table</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#estimate-bank-info-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Bank Info</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#estimate-term-condition-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Terms & Condition</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#estimate-signature-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Signature</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="estimate-item-table-b1">
                                                                        <table
                                                                            class="table table-centered table-borderless mb-0 table-sm">
                                                                            <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Label</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr>
                                                                                <th>Item Number</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_no"
                                                                                           name="item_table_no"
                                                                                           value="{!!($proposal_template->item_table_no)?$proposal_template->item_table_no : '#'!!}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Item</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_item"
                                                                                           name="item_table_item"
                                                                                           value="{{($proposal_template->item_table_item)?$proposal_template->item_table_item : 'Item & Description' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>HSN/SAC</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_hsn"
                                                                                           name="item_table_hsn"
                                                                                           value="{{($proposal_template->item_table_hsn)?$proposal_template->item_table_hsn : 'HSN/SAC' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Qty</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_qty"
                                                                                           name="item_table_qty"
                                                                                           value="{{($proposal_template->item_table_qty)?$proposal_template->item_table_qty : 'Qty' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Rate</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_rate"
                                                                                           name="item_table_rate"
                                                                                           value="{{($proposal_template->item_table_rate)?$proposal_template->item_table_rate : 'Rate' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Discount</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_discount"
                                                                                           name="item_table_discount"
                                                                                           value="{{($proposal_template->item_table_discount)?$proposal_template->item_table_discount : 'Discount' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>CGST</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_cgst"
                                                                                           name="item_table_cgst"
                                                                                           value="{{($proposal_template->item_table_cgst)?$proposal_template->item_table_cgst : 'CGST' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>SGST</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_sgst"
                                                                                           name="item_table_sgst"
                                                                                           value="{{($proposal_template->item_table_sgst)?$proposal_template->item_table_sgst : 'SGST' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>IGST</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_igst"
                                                                                           name="item_table_igst"
                                                                                           value="{{($proposal_template->item_table_igst)?$proposal_template->item_table_igst : 'IGST' }}">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Total</th>
                                                                                <td><input class="form-control"
                                                                                           type="text"
                                                                                           id="item_table_total"
                                                                                           name="item_table_total"
                                                                                           value="{{($proposal_template->item_table_total)?$proposal_template->item_table_total : 'Total' }}">
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="tab-pane" id="estimate-bank-info-b1">
                                                                        <label for="name" class="form-label"> Label
                                                                            <span class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <input class="form-control" type="text"
                                                                                   id="estimate_bank_label"
                                                                                   name="est_bank_label"
                                                                                   value="{!! ($proposal_template->est_bank_label)? $proposal_template->est_bank_label : 'Bank Detail :'!!}">
                                                                        </div>
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="estimate_cover_main_text"
                                                                                      name="est_bank_details"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->est_bank_details)? html_entity_decode($proposal_template->est_bank_details, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p>Bank Name:- ICICI BANK LTD</p><p>Account Name.:- HEAVEN DESIGNS PRIVATE LIMITED</p><p>Account No.:- 183605002858</p><p>ISFC :- ICIC0001836</p><p>Banch:- KATARGAM - SURAT</p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane"
                                                                         id="estimate-term-condition-b1">
                                                                        <label for="name" class="form-label"> Label<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <input class="form-control" type="text"
                                                                                   id="estimate_term_condition_label"
                                                                                   name="est_term_condition_lable"
                                                                                   value="{!! ($proposal_template->est_term_condition_lable)? html_entity_decode($proposal_template->est_term_condition_lable, ENT_QUOTES, 'UTF-8') : html_entity_decode('Terms &amp; Conditions:', ENT_QUOTES, 'UTF-8')!!}">
                                                                        </div>

                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea
                                                                                id="estimate_term_condition_main_text"
                                                                                name="est_term_condition_details"
                                                                                data-toggle="maxlength"
                                                                                class="form-control" maxlength="225"
                                                                                rows="3"
                                                                                placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->est_term_condition_details)? html_entity_decode($proposal_template->est_term_condition_details, ENT_QUOTES, 'UTF-8') : html_entity_decode('test', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-pane" id="estimate-signature-b1">
                                                                        <label for="name" class="form-label"> Label<span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <input class="form-control" type="text"
                                                                                   id="estimate_signature_label"
                                                                                   name="est_signature_lable"
                                                                                   value="{!! ($proposal_template->est_signature_lable)? html_entity_decode($proposal_template->est_signature_lable, ENT_QUOTES, 'UTF-8') : html_entity_decode('Authorized Signature', ENT_QUOTES, 'UTF-8')!!}">
                                                                        </div>
                                                                        <label class="form-label">Signature image <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <input class="form-control" type="file"
                                                                                   name="est_signature_img"
                                                                                   onchange="signature_image_preview(event)">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <img
                                                                                src="{{Storage::url($proposal_template->est_signature_img)}}"
                                                                                alt="image"
                                                                                class="img-fluid avatar-xl"/>
                                                                            {{--<p class="mb-0">
                                                                                <code>Remove</code>
                                                                            </p>--}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingNine">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseNine"
                                                   aria-expanded="false" aria-controls="collapseNine">
                                                    Testimonials <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#testimonials-title-b1"
                                                                           data-bs-toggle="tab"
                                                                           aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Title</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#testimonials-content-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Content</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="testimonials-title-b1">
                                                                        <label for="name" class="form-label"> Cover page
                                                                            title <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea id="editor_testimonials"
                                                                                  name="testimonials_title"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->testimonials_title)? html_entity_decode($proposal_template->testimonials_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1 style="text-align:center">Testimonials</h1>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                    <div class="tab-pane" id="testimonials-content-b1">
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="testimonials_main_text"
                                                                                      name="testimonials_content"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->testimonials_content)? html_entity_decode($proposal_template->testimonials_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><strong>This is pre-design or reference photo of your solar power plant.</strong></p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingEight">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseEight"
                                                   aria-expanded="false" aria-controls="collapseEight">
                                                    Term & Condition <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#terms-title-b1" data-bs-toggle="tab"
                                                                           aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Title</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#terms-content-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Content</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="terms-title-b1">
                                                                        <label for="name" class="form-label"> Cover page
                                                                            title <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea id="editor_terms"
                                                                                  name="terms_title"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->terms_title)? html_entity_decode($proposal_template->terms_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1>Terms & Condition</h1>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                    <div class="tab-pane" id="terms-content-b1">
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="terms_main_text"
                                                                                      name="terms_content"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->terms_content)? html_entity_decode($proposal_template->terms_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><strong>What is Lorem Ipsum?</strong><br />
                                                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&quot;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br />
                                                                                <strong>Why do we use it?</strong><br />
                                                                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &quot;Content here, content here&quot;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &quot;lorem ipsum&quot; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-0">
                                        <div class="card-header" id="headingTen">
                                            <h5 class="m-0">
                                                <a class="custom-accordion-title collapsed d-block py-1"
                                                   data-bs-toggle="collapse" href="#collapseTen"
                                                   aria-expanded="false" aria-controls="collapseTen">
                                                    Thank you <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen"
                                             data-bs-parent="#custom-accordion-one">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-6">
                                                        {{--<div class="card border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Content Setting</h5>
                                                                <ul class="nav nav-tabs nav-bordered mb-3">
                                                                    <li class="nav-item">
                                                                        <a href="#terms-title-b1" data-bs-toggle="tab"
                                                                           aria-expanded="false"
                                                                           class="nav-link active">
                                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                                            <span class="d-none d-md-block">Title</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#terms-content-b1"
                                                                           data-bs-toggle="tab" aria-expanded="true"
                                                                           class="nav-link">
                                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                                            <span
                                                                                class="d-none d-md-block">Content</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>

                                                                <div class="tab-content">
                                                                    <div class="tab-pane show active"
                                                                         id="terms-title-b1">
                                                                        <label for="name" class="form-label"> Cover page
                                                                            title <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea id="editor_terms"
                                                                                  name="terms_title"
                                                                                  data-toggle="maxlength"
                                                                                  class="form-control" maxlength="225"
                                                                                  rows="3"
                                                                                  placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->terms_title)? html_entity_decode($proposal_template->terms_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1>Terms & Condition</h1>', ENT_QUOTES, 'UTF-8')!!}</textarea>
                                                                    </div>
                                                                    <div class="tab-pane" id="terms-content-b1">
                                                                        <label for="name" class="form-label"> Cover main
                                                                            text <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="mb-3">
                                                                            <textarea id="terms_main_text"
                                                                                      name="terms_content"
                                                                                      data-toggle="maxlength"
                                                                                      class="form-control"
                                                                                      maxlength="225" rows="3"
                                                                                      placeholder="This textarea has a limit of 225 chars.">{!! ($proposal_template->terms_content)? html_entity_decode($proposal_template->terms_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><strong>What is Lorem Ipsum?</strong><br />
                                                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&quot;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br />
                                                                                <strong>Why do we use it?</strong><br />
                                                                                It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &quot;Content here, content here&quot;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &quot;lorem ipsum&quot; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', ENT_QUOTES, 'UTF-8')!!}

                                                                            </textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end card-body-->
                                                        </div>--}}

                                                        <div class="card border border">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Image Setting</h5>
                                                                <div class="tab-content">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <label class="form-label">Image <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="mb-3">
                                                                                <input class="form-control" type="file"
                                                                                       id="thank_you_img"
                                                                                       name="thank_you_img"
                                                                                       onchange="cover_preview_thankyou(event)">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-4">
                                                                            <img
                                                                                src="{{Storage::url($proposal_template->thank_you_img)}}"
                                                                                id="preview_image_container_thankyou"
                                                                                class="preview_image_container_thankyou"
                                                                                alt="Put logo Here"
                                                                                style="width: 100%;"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- end card-body-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card body-->
                                <div class="mt-3 text-end">
                                    <a href="{{route('proposal.pdf-preview')}}" class="btn btn-secondary me-2" target="_blank"><i class="uil uil-web-grid-alt"></i> Preview</a>
                                    <input type="submit" form="proposal-template-form" class="btn btn-primary proposal_template_button float-end" id="proposal_template_button" value="Save"/>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{ asset('assets/js/app.min.js')}}"></script>
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <!-- third party js ends -->

    <script>
        CKEDITOR.replace('editor', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('cover_main_text', {
            extraPlugins: 'editorplaceholder',
            placeholder_select: {
                placeholders: ['customers.name', 'customers.address', 'customers.pincode', 'customers.country_name', 'customers.state_name', 'customers.city_name'],
                format: '${%placeholder%}'
            }
        });

        CKEDITOR.replace('editor_aboutus', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('aboutus_main_text', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('editor_product', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('product_main_text', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('editor_testimonials', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('testimonials_main_text', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('editor_terms', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('terms_main_text', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('estimate_cover_main_text', {
            extraPlugins: 'editorplaceholder',
        });
        CKEDITOR.replace('estimate_term_condition_main_text', {
            extraPlugins: 'editorplaceholder',
        });

        CKEDITOR.replace('cover_footer_one', {
            extraPlugins: 'editorplaceholder',
            placeholder_select: {
                placeholders: ['companies.name', 'companies.company_name', 'companies.address', 'companies.pincode', 'companies.country_name', 'companies.state_name', 'companies.city_name'],
                format: '${%placeholder%}'
            }
        });

        CKEDITOR.replace('cover_footer_two', {
            extraPlugins: 'editorplaceholder',
            placeholder_select: {
                placeholders: ['estimates.estimate_no', 'estimates.estimate_date'],
                format: '${%placeholder%}'
            }
        });

        CKEDITOR.on('editor', function (event) {
            if ('placeholder' == event.data.name) {
                var input = event.data.definition.getContents('info').get('name');
                input.type = 'select';
                input.items = [['FirstName'], ['LastName']];
                input.setup = function () {
                    this.setValue('FirstName');
                };
            }
        });

        $(document).ready(function () {
            $('.color_picker').on('change', function () {
                if ($(this).attr('id') == 'color_picker_one') {
                    $('.main_div_bg').css('background-color', $(this).val());
                    $('#color_picker_one').val($(this).val());
                    getDivValThemeEstimate($(this).val())
                }

                if ($(this).attr('id') == 'color_picker_two') {
                    $('#partial_div_bg').css('background-color', $(this).val());
                    $('#color_picker_two').val($(this).val());
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // formValition('#proposal-template-form');
            $('.proposal-template-form').on('submit', function (e) {
                e.preventDefault();
                // if ( $(this).parsley().isValid() ) {
                var formData = new FormData(this);
                formData.append("cover_title", CKEDITOR.instances.editor.getData());
                formData.append("cover_content", CKEDITOR.instances.cover_main_text.getData());
                formData.append("cover_footer_one", CKEDITOR.instances.cover_footer_one.getData());
                formData.append("cover_footer_two", CKEDITOR.instances.cover_footer_two.getData());
                formData.append("aboutas_title", CKEDITOR.instances.editor_aboutus.getData());
                formData.append("aboutas_content", CKEDITOR.instances.aboutus_main_text.getData());
                formData.append("product_title", CKEDITOR.instances.editor_product.getData());
                formData.append("product_content", CKEDITOR.instances.product_main_text.getData());
                formData.append("testimonials_title", CKEDITOR.instances.editor_testimonials.getData());
                formData.append("testimonials_content", CKEDITOR.instances.testimonials_main_text.getData());
                formData.append("terms_title", CKEDITOR.instances.editor_terms.getData());
                formData.append("terms_content", CKEDITOR.instances.terms_main_text.getData());
                formData.append("est_bank_details", CKEDITOR.instances.estimate_cover_main_text.getData());
                formData.append("est_term_condition_details", CKEDITOR.instances.estimate_term_condition_main_text.getData());
                formData.append("theme_color_one", $('#color_picker_one').val());
                formData.append("theme_color_two", $('#color_picker_two').val());
                $.ajax({
                    type: 'POST',
                    url: '{{route('proposal.store')}}',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: formData,
                    // data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function () {
                        $("#proposal_template_button").prop('disabled', true);
                        $("#proposal_template_button").html('<i class="mdi mdi-spin mdi-loading"></i> Loading...');
                    },
                    success: function (data) {
                        toastrSuccess('Successfully saved...', 'Success');
                        $("#proposal_template_button").prop('disabled', false);
                        $("#proposal_template_button").html('<i class="uil-arrow-circle-right"></i> Save');
                        window.location.href = '{{route('proposal.index')}}';
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
                                toastrInfo('Estimate no already exist.', 'Warning');
                                break;
                            default:
                                toastrError('Error - ' + errorMessage, 'Error');
                        }
                        $("#proposal_template_button").prop('disabled', false);
                        $("#proposal_template_button").html('<i class="uil-arrow-circle-right"></i> Save');
                    },
                    complete: function (data) {
                        $("#proposal_template_button").html('Save');
                        $("#proposal_template_button").prop('<i class="uil-arrow-circle-right"></i> disabled', false);
                    }
                });
                // }
            });
        });
    </script>
@endpush
