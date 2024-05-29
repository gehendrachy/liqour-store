<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $setting->sitetitle }} | @yield('title') </title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    {{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}

    <link rel="icon" href="{{ asset('storage/setting/favicon/'.$setting->favicon) }}" type="image/png"/>
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('/backend/assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/animate-css/vivify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/toastr/toastr.min.css')}}">

    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/dropify/css/dropify.min.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/c3/c3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/chartist/css/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/parsleyjs/css/parsley.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css') }}"><!-- MAIN CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('backend/assets/vendor/table-dragger/table-dragger.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/summernote/dist/summernote.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/light-gallery/css/lightgallery.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/html/assets/css/site.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/sweetalert/sweetalert.css') }}"/>
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/nestable/jquery-nestable.css') }}"/>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}"/>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
</head>
<body class="theme-cyan font-montserrat light_version mini_sidebar" id="sideBar">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
        <div class="bar4"></div>
        <div class="bar5"></div>
    </div>
</div>

<div id="modal-loader" >
    <div class="loadingio-spinner-eclipse-5n5ocxxlhe2">
        <div class="ldio-shhdvnglxrk">
            <div></div>
        </div>
    </div>
</div>

<!-- Theme Setting -->
<div class="themesetting">
    <a href="javascript:void(0);" class="theme_btn"><i class="fa fa-gears"></i></a>
    <div class="card theme_color">
        <div class="header">
            <h2>Theme Color</h2>
        </div>
        <ul class="choose-skin list-unstyled mb-0">
            <li data-theme="green">
                <div class="green"></div>
            </li>
            <li data-theme="orange">
                <div class="orange"></div>
            </li>
            <li data-theme="blush">
                <div class="blush"></div>
            </li>
            <li data-theme="cyan" class="active">
                <div class="cyan"></div>
            </li>
            <li data-theme="indigo">
                <div class="indigo"></div>
            </li>
            <li data-theme="red">
                <div class="red"></div>
            </li>
        </ul>
    </div>
    <div class="card setting_switch">
        <div class="header">
            <h2>Settings</h2>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                Light Mode
                <div class="float-right">
                    <label class="switch">
                        <input type="checkbox" class="lv-btn">
                        <span class="slider round"></span>
                    </label>
                </div>
            </li>

            <li class="list-group-item">
                Mini Sidebar
                <div class="float-right">
                    <label class="switch">
                        <input type="checkbox" class="mini-sidebar-btn" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </li>
        </ul>
    </div>

</div>

<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<div id="wrapper">
    <nav class="navbar top-navbar">
        <div class="container-fluid">

            <div class="navbar-left">
                <div class="navbar-btn">
                    <a href="index.html">
                        <img src="{{ asset('backend/assets/images/icon.svg') }}" alt="Oculux Logo" class="img-fluid logo">
                    </a>
                    <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                </div>
                <ul class="nav navbar-nav">
                    <li>
                        <a href="javascript:void(0);" id="toggleSideBar" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="navbar-right">
                <div id="navbar-menu">
                    <ul class="nav navbar-nav text-muted">
                        @if (Auth::user()->hasRole(['Super Admin']) && Request::segment(1) == 'vendor' ) 

                            <li title="Close Vendor Dashboard">
                                <a href="{{ url('/admin') }}" class="icon-menu" data-toggle="tooltip" data-placement="top" title="" data-original-title="Go To Super Admin Dashboard">
                                    <i class="icon-user text-green"></i>
                                </a>
                            </li>|

                        @endif

                        <li title="Visit Site">
                            <a href="{{ url('/') }}" class="icon-menu" target="_blank"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Visit Site">
                                <i class="icon-screen-desktop text-blue"></i>
                            </a>
                        </li>|
                        <li title="Log Out">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="icon-menu"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Log Out">
                                <i class="icon-power text-red"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress-bar" id="myBar"></div>
        </div>
    </nav>
    <div id="left-sidebar" class="sidebar mini_sidebar_on">
        <div class="navbar-brand">
            <a href="{{ url('admin') }}">
                <img src="{{ asset('storage/setting/favicon/thumb_'.$setting->favicon) }}"  alt="Logo" class="img-fluid logo">
                <span>{{ $setting->sitetitle }}</span>
            </a>
            <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right">
                <i class="lnr lnr-menu icon-close"></i>
            </button>
        </div>
        <div class="sidebar-scroll">
            <div class="user-account">

                <div class="dropdown">
                    <span>Welcome,</span>
                    <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{ \Illuminate\Support\Facades\Auth::user()->name }}</strong></a>
                    <ul class="dropdown-menu dropdown-menu-right account vivify flipInY">
                        <li><a href="#"><i class="icon-lock"></i>Change Password</a></li>
                        <li><a href="{{ url('admin/setting') }}"><i class="icon-settings"></i>Settings</a></li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-power"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
            <nav id="left-sidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    <li class="header">Main</li>

                    @if(Request::segment(1) == 'admin')
                    <li class="{{ Request::segment(2) == '' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Dashboard">
                        <a href="{{ url('/admin') }}">
                            <i class="icon-speedometer"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::segment(2) == 'users' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Users">
                        <a href="{{ url('/admin/users') }}">
                            <i class="icon-users"></i><span>Users</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'setting' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Site Settings">
                        <a href="{{ url('/admin/setting') }}">
                            <i class="icon-settings"></i><span>Site Settings</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'vendors' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Vendors">
                        <a href="{{ url('/admin/vendors') }}">
                            <i class="fa fa-th"></i><span>Vendors</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'categories' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Categories">
                        <a href="{{ url('/admin/categories') }}">
                            <i class="fa fa-anchor"></i><span>Categories</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'products' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Products">
                        <a href="{{ url('/admin/products') }}">
                            <i class="fa fa-database"></i><span>Products</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'pages' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Pages">
                        <a href="{{ url('/admin/pages') }}">
                            <i class="fa fa-file-text-o"></i><span>Pages</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'sliders' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Sliders">
                        <a href="{{ url('/admin/sliders') }}">
                            <i class="icon-layers"></i><span>Sliders</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'sales-reports' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Sales Reports">
                        <a href="{{ url('/admin/sales-reports') }}">
                            <i class="fa fa-calculator"></i><span>Sales Reports</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(2) == 'payment-reports' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Payment Reports">
                        <a href="{{ url('/admin/payment-reports') }}">
                            <i class="icon-calculator"></i><span>Payment Reports</span>
                        </a>
                    </li>

                    @elseif(Request::segment(1) == 'vendor')
                    <li class="{{ Request::segment(3) == 'dashboard' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Dashboard">
                        <a href="{{ url('/vendor/'.session()->get('username').'/dashboard') }}">
                            <i class="icon-speedometer"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::segment(3) == 'inventory-products' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Inventory Products">
                        <a href="{{ url('/vendor/'.session()->get('username').'/inventory-products') }}">
                            <i class="fa fa-shopping-cart"></i><span>Inventory Products</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(3) == 'vendor-settings' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Vendor Setting">
                        <a href="{{ url('/vendor/'.session()->get('username').'/vendor-settings') }}">
                            <i class="icon-settings"></i><span>Vendor Setting</span>
                        </a>
                    </li>

                    <li class="{{ Request::segment(3) == 'vendor-orders' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" data-original-title="Vendor Setting">
                        <a href="{{ url('/vendor/'.session()->get('username').'/vendor-orders') }}">
                            <i class="icon-basket-loaded"></i><span>Vendor Orders</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
    <div id="main-content">
        @yield('content')
    </div>



</div>
<!-- Javascript -->
<!-- Scripts -->

<script src="{{ asset('backend/html/assets/bundles/libscripts.bundle.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/c3.bundle.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/chartist.bundle.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/vendorscripts.bundle.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/toastr/toastr.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script><!-- Bootstrap Colorpicker Js -->
<script src="{{ asset('backend/html/assets/bundles/datatablescripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/jquery-datatable/buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/sweetalert/sweetalert2.js') }}"></script><!-- SweetAlert Plugin Js -->

<script src="{{ asset('backend/assets/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Multi Select Plugin Js -->
<script src="{{ asset('backend/assets/vendor/multi-select/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>

<script src="{{ asset('backend/assets/vendor/parsleyjs/js/parsley.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/mainscripts.bundle.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/dropify/js/dropify.js') }}"></script>
<script src="{{ asset('backend/html/assets/js/pages/forms/dropify.js') }}"></script>
<script src="{{ asset('backend/html/assets/js/pages/tables/jquery-datatable.js') }}"></script>
<script src="{{ asset('backend/html/assets/js/index.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/summernote/dist/summernote.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/nestable/jquery.nestable.js') }}"></script><!-- Jquery Nestable -->
<script src="{{ asset('backend/html/assets/js/pages/ui/sortable-nestable.js') }}"></script>
<script src="{{ asset('backend/html/assets/bundles/lightgallery.bundle.js') }}"></script>
@yield('script')

<script type="text/javascript">

    $(function() {
        $('#parsley-form').parsley();
    });
    toastr.options.timeOut = "4000";
    toastr.options.closeButton = true;
    toastr.options.positionClass = 'toast-top-right';
    toastr.options.preventDuplicates = true;
</script>
@if (session('status'))
    <script>
        toastr['success']('{{ session('status') }}', 'Success!');
    </script>
@elseif (session('error'))
    <script>
        toastr['error']('{{ session('error') }}');
    </script>

@elseif (session('log_status'))
    <script>
        toastr['error']('{{ session('log_status') }}','');
    </script>

@elseif (session("parent_status"))
    <script>
        toastr['error']('{{ session("parent_status")["secondary"] }}', '{{ session("parent_status")["primary"] }}');
    </script>

@endif
@if ($errors->any())
    @foreach ($errors->all() as $key=>$error)
        <div data-notify="container"  class="col-11 col-md-4 alert alert-danger alert-with-icon animated fadeInDown cart-alert-message vivify " role="alert" data-notify-position="bottom-right" style="display: inline-block; margin: 15px auto; position: fixed; transition: all 0.5s ease-in-out 0s; z-index: 1031; bottom: <?= $key * 70; ?>px; right: 20px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fa fa-times"></i>
            </button>
            <i data-notify="icon" class="fa fa-bell"></i>
            <span data-notify="title"></span>
            <span data-notify="message">
                    Sorry!! <br> {{ $error }}
                </span>
            <a href="#" target="_blank" data-notify="url"></a>
        </div>
    @endforeach
@endif
<script>
    var $alert = $('.cart-alert-message');
    $alert.hide();

    var i = 0;
    setInterval(function () {
        $($alert[i]).show();
        $($alert[i]).addClass('flipInX');
        i++;
    }, 500);

    // $(".cart-alert-message").fadeTo((($alert.length)+1)*1000, 0.1).slideUp('slow');
    setTimeout(function() {
        $('.cart-alert-message').addClass('fadeOutRight');
    }, $alert.length*($alert.length == 1 ? 5000 : 2000));
</script>
<script>
    $('.colorpicker').colorpicker();

    $(".summernote").summernote({
        disableResizeEditor: true,
        height: 300,
        width:'90%',
        callbacks: {
            onImageUpload: function(files) {
                for(let i=0; i < files.length; i++) {
                    $.upload(files[i]);
                }
            }
        },
    });

    $("#toggleSideBar").click(function(){
        if ($("#left-sidebar").hasClass("mini_sidebar_on")) {
            $("#left-sidebar").removeClass("mini_sidebar_on");
            $("#sideBar").removeClass("mini_sidebar");
        }else{
            $("#left-sidebar").addClass("mini_sidebar_on");
            $("#sideBar").addClass("mini_sidebar");
        }
    });

</script>

</body>
</html>


