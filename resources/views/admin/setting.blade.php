@extends('admin/layouts.header-sidebar')
@section('title','Site Settings')
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Setting</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icon-speedometer"></i>  Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><i class="icon-settings"></i> Site Setting</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                    <a href="{{ url('admin') }}" class="btn btn-sm btn-round btn-outline-primary" title=""><i class="fa fa-angle-double-left"></i> Go Back</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Update Your Site Setting</h6>
                </div>
                <div class="card-body">
                    <form id="advanced-form" data-parsley-validate="" novalidate=""
                          action="{{ route('admin.setting.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-file-image-o"></i> &nbsp;Logo </span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="logo" class="custom-file-input"
                                                               id="inputGroupFile03">
                                                        <label class="custom-file-label" for="inputGroupFile03">Choose
                                                            Logo</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/setting/logo/'.$setting->logo) }}"
                                                     data-toggle="tooltip" data-placement="top" title="" alt="Logo"
                                                     class="rounded img-thumbnail" width="80px" data-original-title="Logo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i
                                                                class="fa fa-file-image-o"></i> &nbsp;Favicon </span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file" name="favicon" class="custom-file-input"
                                                               id="inputGroupFile03">
                                                        <label class="custom-file-label" for="inputGroupFile03">Choose
                                                            Favicon</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/setting/favicon/'.$setting->favicon) }}"
                                                     data-toggle="tooltip" data-placement="top" title="" alt="Favicon"
                                                     class="rounded img-thumbnail" width="80px" data-original-title="Favicon">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-info fa-lg"></i> &nbsp;Site Title</span>
                                    </div>
                                    <input type="text" name="sitetitle" value="{{ $setting->sitetitle  }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-envelope fa-lg"></i> &nbsp;Site Email</span>
                                    </div>
                                    <input type="email" name="siteemail" value="{{ $setting->siteemail }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-phone-square fa-lg"></i> &nbsp; Phone</span>
                                    </div>
                                    <input type="text" name="phone" value="{{ $setting->phone }}" class="form-control" required 
                                           aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-mobile-phone fa-lg"></i> &nbsp; Mobile</span>
                                    </div>
                                    <input type="text" name="mobile" value="{{ $setting->mobile }}"
                                           class="form-control" aria-label="Default" required
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-map-marker fa-lg"></i> &nbsp; Address</span>
                                    </div>
                                    <input type="address" value="{{ $setting->address }}" name="address"
                                           class="form-control" aria-label="Default" required
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-fax fa-lg"></i> &nbsp; Fax</span>
                                    </div>
                                    <input type="text" value="{{ $setting->fax }}" name="fax" class="form-control"
                                           aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-facebook-square fa-lg"></i> &nbsp;Facebook Url</span>
                                    </div>
                                    <input type="text" name="facebookurl" value="{{ $setting->facebookurl }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-twitter fa-lg"></i> &nbsp;Twitter Url</span>
                                    </div>
                                    <input type="text" name="twitterurl" value="{{ $setting->twitterurl }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>


                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-instagram fa-lg"></i>&nbsp; Instagram Url</span>
                                    </div>
                                    <input type="text" name="instagramurl" value="{{ $setting->instagramurl }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-youtube-play fa-lg"></i>&nbsp; Youtube Url</span>
                                    </div>
                                    <input type="text" name="youtubeurl" value="{{ $setting->youtubeurl }}"
                                           class="form-control" aria-label="Default"
                                           aria-describedby="inputGroup-sizing-default">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-file-text-o fa-lg"></i>&nbsp; About Your Site</span>
                                    </div>
                                    <textarea name="sitekeyword" class="form-control" aria-label="Default"
                                              aria-describedby="inputGroup-sizing-default">{{ $setting->sitekeyword }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-default"><i
                                                class="fa fa-map fa-lg"></i>&nbsp; Google Map Url</span>
                                    </div>
                                    <textarea name="googlemapurl" class="form-control" aria-label="Default"
                                              aria-describedby="inputGroup-sizing-default">{{ $setting->googlemapurl }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-danger">Cancel</button>
                        <button style="float: right" type="submit" class="btn btn-outline-success">Update</button>
                    </form>
                </div>
            </div>

        </div>


    </div>
@endsection
