<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Annuaire PDD') }}</title>

    <!-- creative Dashboard-->
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('theme-admin/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('theme-admin/img/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{asset('theme-admin/css/material-dashboard.min.css').'?v=2.1.2'}}" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('theme-admin/demo/demo.css')}}" rel="stylesheet" />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('style')
    <!-- Styles -->
    <!--link href="{ asset('css/app.css') }}" rel="stylesheet"-->
</head>
<body class="" id="app">
    <div class="wrapper ">
    @auth
    <div class="sidebar" data-color="purple" data-background-color="black" data-image="{{asset('theme-admin/img/sidebar-1.jpg')}}">
        <!--
          Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

          Tip 2: you can also add an image using data-image tag
      -->
        <div class="logo">
            <a href="http://www.creative-tim.com" class="simple-text logo-normal">
                <center>
                    <img src="{{asset('images/exossa_fav_by_iconegr-2.png')}}" alt="">
                </center>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                @if(auth()->user()->isAdmin())
                <li class="nav-item @if(request()->routeIs('dashboard*')) active @endif">
                    <a class="nav-link" href="{!! route('dashboard', ['id'=>encrypt(auth()->user()->id)]) !!}">
                        <i class="material-icons">dashboard</i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endif

               <li class="nav-item @if(request()->routeIs('annee_spirituelles*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#annee_spirituelles" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Année spirituelle <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('annee_spirituelles*')) show @endif" id="annee_spirituelles" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('annee_spirituelles.index')) active @endif" >
                                <a class="nav-link" href="{!! route('annee_spirituelles.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('annee_spirituelles.create')) active @endif">
                                <a class="nav-link" href="{!! route('annee_spirituelles.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

               <li class="nav-item @if(request()->routeIs('apostolats*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#apostolats" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Apostolat <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('apostolats*')) show @endif" id="apostolats" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('apostolats.index')) active @endif" >
                                <a class="nav-link" href="{!! route('apostolats.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('apostolats.create')) active @endif">
                                <a class="nav-link" href="{!! route('apostolats.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('niveau_engagements*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#niveau_engagements" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Niveau d'engagement <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('niveau_engagements*')) show @endif" id="niveau_engagements" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('niveau_engagements.index')) active @endif" >
                                <a class="nav-link" href="{!! route('niveau_engagements.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('niveau_engagements.create')) active @endif">
                                <a class="nav-link" href="{!! route('niveau_engagements.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('zones*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#zones" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>zones <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('zones*')) show @endif" id="zones" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('zones.index')) active @endif" >
                                <a class="nav-link" href="{!! route('zones.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('zones.create')) active @endif">
                                <a class="nav-link" href="{!! route('zones.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('sous_zones*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#sous-zones" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Sous-zones <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('sous_zones*')) show @endif" id="sous-zones" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('sous_zones.index')) active @endif" >
                                <a class="nav-link" href="{!! route('sous_zones.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('sous_zones.create')) active @endif">
                                <a class="nav-link" href="{!! route('sous_zones.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('groupes*')) active @endif">
                    <a class="nav-link @if(request()->routeIs('groupes*')) show @endif" data-toggle="collapse" href="#groupes" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Groupes <b class="caret"></b> </p>
                    </a>
                    <div class="collapse" id="groupes" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('groupes.index')) active @endif" >
                                <a class="nav-link" href="{!! route('groupes.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('groupes.create')) active @endif">
                                <a class="nav-link" href="{!! route('groupes.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('responsable_zones*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#responsable-zones" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Responsable de zones <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('responsable_zones*')) show @endif" id="responsable-zones" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('responsable_zones.index')) active @endif" >
                                <a class="nav-link" href="{!! route('responsable_zones.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('responsable_sous_zones*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#responsable-sous-zones" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Responsable de Sous-zones <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('responsable_sous_zones*')) show @endif" id="responsable-sous-zones" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('responsable_sous_zones.index')) active @endif" >
                                <a class="nav-link" href="{!! route('responsable_sous_zones.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('responsable_groupes*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#responsable-groupes" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Responsable de groupes <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('responsable_groupes*')) show @endif" id="responsable-groupes" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('responsable_groupes.index')) active @endif" >
                                <a class="nav-link" href="{!! route('responsable_groupes.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('categorie_activites*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#categorie-activites" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Categorie d'activités <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('categorie_activites*')) show @endif" id="categorie-activites" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('categorie_activites.index')) active @endif" >
                                <a class="nav-link" href="{!! route('categorie_activites.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('categorie_activites.create')) active @endif">
                                <a class="nav-link" href="{!! route('categorie_activites.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('activites*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#activites" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Activités <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('activites*')) show @endif" id="activites" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('activites.index')) active @endif" >
                                <a class="nav-link" href="{!! route('activites.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('activites.create')) active @endif">
                                <a class="nav-link" href="{!! route('activites.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item @if(request()->routeIs('users*')) active @endif">
                    <a class="nav-link" data-toggle="collapse" href="#users" aria-expanded="true">
                        <i class="material-icons">person</i>
                        <p>Membre <b class="caret"></b> </p>
                    </a>
                    <div class="collapse @if(request()->routeIs('users*')) show @endif" id="users" style="">
                        <ul class="nav">
                            <li class="nav-item @if(request()->routeIs('users.index')) active @endif" >
                                <a class="nav-link" href="{!! route('users.index') !!}">
                                    <span class="sidebar-mini"> L </span>
                                    <span class="sidebar-normal"> Lister </span>
                                </a>
                            </li>

                            <li class="nav-item @if(request()->routeIs('users.create')) active @endif">
                                <a class="nav-link" href="{!! route('users.create') !!}">
                                    <span class="sidebar-mini"> A </span>
                                    <span class="sidebar-normal"> Ajouter </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                </ul>
        </div>
        </div>
    @endauth
    <div class="main-panel">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
            <div class="navbar-wrapper">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                        <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                        <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                        <div class="ripple-container"></div></button>
                </div>
                <a class="navbar-brand" href="javascript:;">@yield('page_title')<div class="ripple-container"></div></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li> {{ucfirst(auth()->user()->prenom)}} {{ucfirst(auth()->user()->nom)}} ({{auth()->user()->niveau}})</li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">notifications</i>
                            <span class="notification">5</span>
                            <p class="d-lg-none d-md-block">
                                Some Actions
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Mike John responded to your email</a>
                            <a class="dropdown-item" href="#">You have 5 new tasks</a>
                            <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                            <a class="dropdown-item" href="#">Another Notification</a>
                            <a class="dropdown-item" href="#">Another One</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">person</i>
                            <p class="d-lg-none d-md-block">
                                Account
                            </p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                            <a class="dropdown-item" href="{{route('profile')}}">Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                                {{ __('Déconnexion') }}({{auth()->user()->nom}})
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>

                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    @yield('content')
    <footer class="footer">
    <div class="container-fluid">
        <nav class="float-left">
    <ul>
    <li>
        <a href="https://www.creative-tim.com">&copy; {{date('Y')}} MOUVEMENT DE L'INCARNATION</a>
    </li>
    </ul>
    </nav>
    <!--div class="copyright float-right">
     réalisé par
    <a href="https://www.code-sniper.com" target="_blank">CODE SNIPER AGENCY</a>
    </div-->
    </div>
    </footer>
    </div>
        </div>
        <div class="fixed-plugin" style="display:none">
    <div class="dropdown show-dropdown">
    <a href="#" data-toggle="dropdown">
    <i class="fa fa-cog fa-2x"> </i>
    </a>
    <ul class="dropdown-menu">
    <li class="header-title"> Sidebar Filters</li>
    <li class="adjustments-line">
        <a href="javascript:void(0)" class="switch-trigger active-color">
            <div class="badge-colors ml-auto mr-auto">
                <span class="badge filter badge-purple" data-color="purple"></span>
                <span class="badge filter badge-azure" data-color="azure"></span>
                <span class="badge filter badge-green" data-color="green"></span>
                <span class="badge filter badge-warning" data-color="orange"></span>
                <span class="badge filter badge-danger" data-color="danger"></span>
                <span class="badge filter badge-rose active" data-color="rose"></span>
            </div>
            <div class="clearfix"></div>
        </a>
    </li>
    <li class="header-title">Images</li>
    <li class="active">
        <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="{{asset('theme-admin/img/sidebar-1.jpg')}}" alt="">
        </a>
    </li>
    <li>
        <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="{{asset('theme-admin/img/sidebar-2.jpg')}}" alt="">
        </a>
    </li>
    <li>
        <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="{{asset('theme-admin/img/sidebar-3.jpg')}}" alt="">
        </a>
    </li>
    <li>
        <a class="img-holder switch-trigger" href="javascript:void(0)">
        <img src="{{asset('theme-admin/img/sidebar-4.jpg')}}" alt="">
        </a>
    </li>
    <li class="button-container">
        <a href="https://www.creative-tim.com/product/material-dashboard" target="_blank" class="btn btn-primary btn-block">Free Download</a>
    </li>
    <!-- <li class="header-title">Want more components?</li>
    <li class="button-container">
    <a href="https://www.creative-tim.com/product/material-dashboard-pro" target="_blank" class="btn btn-warning btn-block">
    Get the pro version
    </a>
    </li> -->
    <li class="button-container">
        <a href="https://demos.creative-tim.com/material-dashboard/docs/2.1/getting-started/introduction.html" target="_blank" class="btn btn-default btn-block">
        View Documentation
        </a>
    </li>
    <li class="button-container github-star">
        <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>
    </li>
    <li class="header-title">Thank you for 95 shares!</li>
        <li class="button-container text-center">
        <button id="twitter" class="btn btn-round btn-twitter"><i class="fa fa-twitter"></i> &middot; 45</button>
        <button id="facebook" class="btn btn-round btn-facebook"><i class="fa fa-facebook-f"></i> &middot; 50</button>
        <br>
        <br>
    </li>
    </ul>
    </div>
    </div>
<!--   Core JS Files   -->
<script src="{{asset('theme-admin/js/core/jquery.min.js')}}"></script>
<script src="{{asset('theme-admin/js/core/popper.min.js')}}"></script>
<script src="{{asset('theme-admin/js/core/bootstrap-material-design.min.js')}}"></script>
<script src="{{asset('theme-admin/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
<!-- Plugin for the momentJs  -->
<script src="{{asset('theme-admin/js/plugins/moment.min.js')}}"></script>
<!--  Plugin for Sweet Alert -->
<script src="{{asset('theme-admin/js/plugins/sweetalert2.js')}}"></script>
<!-- Forms Validations Plugin -->
<script src="{{asset('theme-admin/js/plugins/jquery.validate.min.js')}}"></script>
<!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
<script src="{{asset('theme-admin/js/plugins/jquery.bootstrap-wizard.js')}}"></script>
<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="{{asset('theme-admin/js/plugins/bootstrap-selectpicker.js')}}"></script>
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="{{asset('theme-admin/js/plugins/bootstrap-datetimepicker.min.js')}}"></script>
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
<script src="{{asset('theme-admin/js/plugins/jquery.dataTables.min.js')}}"></script>
<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{asset('theme-admin/js/plugins/bootstrap-tagsinput.js')}}"></script>
<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="{{asset('theme-admin/js/plugins/jasny-bootstrap.min.js')}}"></script>
<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
<script src="{{asset('theme-admin/js/plugins/fullcalendar.min.js')}}"></script>
<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
<!--script src="{{--asset('theme-admin/js/plugins/jquery-jvectormap.js')--}}"></script-->
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="{{asset('theme-admin/js/plugins/nouislider.min.js')}}"></script>
<!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
<!-- Library for adding dinamically elements -->
<script src="{{asset('theme-admin/js/plugins/arrive.min.js')}}"></script>
<!--  Google Maps Plugin    -->
<!--script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script-->
<!-- Chartist JS -->
<script src="{{asset('theme-admin/js/plugins/chartist.min.js')}}"></script>
<!--  Notifications Plugin    -->
<script src="{{asset('theme-admin/js/plugins/bootstrap-notify.js')}}"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{asset('theme-admin/js/material-dashboard.js').'?v=2.1.2'}}" type="text/javascript"></script>
<!--script src="asset('theme-admin/js/material-dashboard.min.js').'?v=2.1.2'}}" type="text/javascript"></script-->
<script src="{{asset('js/bootstrap-autocomplete.min.js')}}"></script>
<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="{{asset('theme-admin/demo/demo.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script>
    $(document).ready(function() {
    $().ready(function() {
    $sidebar = $('.sidebar');

    $sidebar_img_container = $sidebar.find('.sidebar-background');

    $full_page = $('.full-page');

    $sidebar_responsive = $('body > .navbar-collapse');

    window_width = $(window).width();

    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

    if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
    if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
    $('.fixed-plugin .dropdown').addClass('open');
    }

    }

    $('.fixed-plugin a').click(function(event) {
    // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
    if ($(this).hasClass('switch-trigger')) {
    if (event.stopPropagation) {
    event.stopPropagation();
    } else if (window.event) {
    window.event.cancelBubble = true;
    }
    }
    });

    $('.fixed-plugin .active-color span').click(function() {
    $full_page_background = $('.full-page-background');

    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    var new_color = $(this).data('color');

    if ($sidebar.length != 0) {
    $sidebar.attr('data-color', new_color);
    }

    if ($full_page.length != 0) {
    $full_page.attr('filter-color', new_color);
    }

    if ($sidebar_responsive.length != 0) {
    $sidebar_responsive.attr('data-color', new_color);
    }
    });

    $('.fixed-plugin .background-color .badge').click(function() {
    $(this).siblings().removeClass('active');
    $(this).addClass('active');

    var new_color = $(this).data('background-color');

    if ($sidebar.length != 0) {
    $sidebar.attr('data-background-color', new_color);
    }
    });

    $('.fixed-plugin .img-holder').click(function() {
    $full_page_background = $('.full-page-background');

    $(this).parent('li').siblings().removeClass('active');
    $(this).parent('li').addClass('active');


    var new_image = $(this).find("img").attr('src');

    if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
    $sidebar_img_container.fadeOut('fast', function() {
    $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
    $sidebar_img_container.fadeIn('fast');
    });
    }

    if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
    var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

    $full_page_background.fadeOut('fast', function() {
    $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
    $full_page_background.fadeIn('fast');
    });
    }

    if ($('.switch-sidebar-image input:checked').length == 0) {
    var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
    var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

    $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
    $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
    }

    if ($sidebar_responsive.length != 0) {
    $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
    }
    });

    $('.switch-sidebar-image input').change(function() {
    $full_page_background = $('.full-page-background');

    $input = $(this);

    if ($input.is(':checked')) {
    if ($sidebar_img_container.length != 0) {
    $sidebar_img_container.fadeIn('fast');
    $sidebar.attr('data-image', '#');
    }

    if ($full_page_background.length != 0) {
    $full_page_background.fadeIn('fast');
    $full_page.attr('data-image', '#');
    }

    background_image = true;
    } else {
    if ($sidebar_img_container.length != 0) {
    $sidebar.removeAttr('data-image');
    $sidebar_img_container.fadeOut('fast');
    }

    if ($full_page_background.length != 0) {
    $full_page.removeAttr('data-image', '#');
    $full_page_background.fadeOut('fast');
    }

    background_image = false;
    }
    });

    $('.switch-sidebar-mini input').change(function() {
    $body = $('body');

    $input = $(this);

    if (md.misc.sidebar_mini_active == true) {
    $('body').removeClass('sidebar-mini');
    md.misc.sidebar_mini_active = false;

    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

    } else {

    $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

    setTimeout(function() {
    $('body').addClass('sidebar-mini');

    md.misc.sidebar_mini_active = true;
    }, 300);
    }

    // we simulate the window Resize so the charts will get updated in realtime.
    var simulateWindowResize = setInterval(function() {
    window.dispatchEvent(new Event('resize'));
    }, 180);

    // we stop the simulation of Window Resize after the animations are completed
    setTimeout(function() {
    clearInterval(simulateWindowResize);
    }, 1000);

    });
    });
    });
</script>
<script>
    $(document).ready(function() {
    // Javascript method's body can be found in assets/js/demos.js
    md.initDashboardPageCharts();

    });
</script>
    <script>
        $(document).ready(function() {
            $('#minimizeSidebar').click(function() {
                $(this);
                1 == md.misc.sidebar_mini_active ? ($("body").removeClass("sidebar-mini"), md.misc.sidebar_mini_active = !1) : ($("body").addClass("sidebar-mini"), md.misc.sidebar_mini_active = !0);
                var e = setInterval(function() {
                    window.dispatchEvent(new Event("resize"))
                }, 180);
                setTimeout(function() {
                    clearInterval(e)
                }, 1e3)
            });
        });
    </script>
<script>
    $(document).ready(function() {
    // initialise Datetimepicker and Sliders
    md.initFormExtendedDatetimepickers();
    if ($('.slider').length != 0) {
    md.initSliders();
    }
    });
</script>
<!-- Scripts -->
<script>
$(document).ready(function(){
    $autoInputs = $('.auto-complete');
    console.log($autoInputs);
    $autoInputs.each(function(index){
        console.log(index)
        $(this).autoComplete();
    });
});
</script>
<!--script src="{ asset('js/app.js') }}" defer></script-->

<!--Start confirm delete modal -->
<script>
$(document).ready(function(){

    $('.btn-ok').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('href');
        var id = $(this).data('id');
        var _token = $('[name="_token"]').val();
        console.log('url', url);
        $.ajax({
            type: "delete",
            url: url,
            data: "_token="+_token+'&id='+id,
            cache: false,
            success: function(html)
            {
                window.location.reload();
            }
        });
    });

    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').data('href', $(e.relatedTarget).data('href'));
        $(this).find('.btn-ok').data('id', $(e.relatedTarget).data('id'));
    });

    $('#confirm-approve').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').data('href', $(e.relatedTarget).data('href'));
        $(this).find('.btn-ok').data('id', $(e.relatedTarget).data('id'));
    });
});
</script>
<!--End confirm delete modal -->
@yield('script')
</body>
</html>
