<!DOCTYPE html>
<html
    lang="ru"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>PSGaming Мастер</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Helpers -->
    <script src="{{asset('assets/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>

</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="" class="app-brand-link">
              <span class="app-brand-logo demo">
                <!-- SVG logo (не изменено) -->
              </span>
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">Менеджер</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">

                <!-- Главная -->
                <li class="menu-item @if(Route::is('main.index')) active @endif">
                    <a href="{{route('main.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Главная</div>
                    </a>
                </li>

                <!-- Типы -->
                <li class="menu-item @if(Route::is('main.types')) active @endif">
                    <a href="{{route('main.types')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-menu"></i>
                        <div data-i18n="Analytics">Типы</div>
                    </a>
                </li>

                <!-- Компьютеры -->
                <li class="menu-item @if(Route::is('main.devices')) active @endif">
                    <a href="{{route('main.devices')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-laptop"></i>
                        <div data-i18n="Analytics">Компьютеры</div>
                    </a>
                </li>

                <!-- Продукты -->
                <li class="menu-item @if(Route::is('main.products')) active @endif">
                    <a href="{{route('main.products')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-cart"></i>
                        <div data-i18n="Analytics">Продукты</div>
                    </a>
                </li>

                <!-- История -->
                <li class="menu-item @if(Route::is('main.history')) active @endif">
                    <a href="{{route('main.history')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-history"></i>
                        <div data-i18n="Analytics">История</div>
                    </a>
                </li>


                <!-- История продукта -->
                <li class="menu-item {{ request()->routeIs('main.product-history') ? 'active' : '' }}">
                    <a href="{{ route('main.product-history') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-history"></i>
                        <div data-i18n="Analytics">История продукта</div>
                    </a>
                </li>


                <!-- Панель -->
                <li class="menu-item @if(Route::is('main.dashboard')) active @endif">
                    <a href="{{route('main.dashboard')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                        <div data-i18n="Analytics">Панель</div>
                    </a>
                </li>

                <!-- Профиль -->
                <li class="menu-item @if(Route::is('profile.edit')) active @endif">
                    <a href="{{route('profile.edit')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-user"></i>
                        <div data-i18n="Analytics">Профиль</div>
                    </a>
                </li>

            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->
            <!-- (Navbar оставлен пустым по умолчанию) -->

            <!-- Контент страницы -->
            @yield('content')

        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- GitHub кнопка -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Дополнительные скрипты -->
@stack('scripts')

</body>
</html>
