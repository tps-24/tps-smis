<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<!-- Mirrored from preview.keenthemes.com/keen/demo1/account/overview.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Jan 2025 11:04:36 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <title>Keen - Multi-demo Bootstrap 5 HTML Admin Dashboard Template by KeenThemes</title>
    <meta charset="utf-8" />
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Bootstrap Market trusted by over 4,000 beginners and professionals. Multi-demo, Dark Mode, RTL support. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="keen, bootstrap, bootstrap 5, bootstrap 4, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Keen - Multi-demo Bootstrap 5 HTML Admin Dashboard Template by KeenThemes" />
    <meta property="og:url" content="https://keenthemes.com/keen" />
    <meta property="og:site_name" content="Keen by Keenthemes" />
    <link rel="canonical" href="overview.html" />
    <link rel="shortcut icon" href="resources/2/assets/media/logos/favicon.ico" />


    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="resources/2/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->


    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="resources/2/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="resources/2/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Google tag-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-37564768-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-37564768-1');
    </script>
    <!--end::Google tag-->
    <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
    if (window.top != window.self) {
        window.top.location.replace(window.self.location.href);
    }
    </script>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
    var defaultThemeMode = "light";
    var themeMode;

    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }

        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }

        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">


            <!--begin::Header-->
            <div id="kt_app_header" class="app-header ">

                <!--begin::Header container-->
                <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between "
                    id="kt_app_header_container">




                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">

                            <!--begin::Toolbar-->
                            <div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

                                <!--begin::Toolbar container-->
                                <div id="kt_app_toolbar_container"
                                    class="app-container  container-xxl d-flex flex-stack ">



                                    <!--begin::Page title-->
                                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
                                        <!--begin::Title-->
                                        <h1
                                            class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                                            Account Overview
                                        </h1>
                                        <!--end::Title-->


                                        <!--begin::Breadcrumb-->
                                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                            <!--begin::Item-->
                                            <li class="breadcrumb-item text-muted">
                                                <a href="../index.html" class="text-muted text-hover-primary">
                                                    Home </a>
                                            </li>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <li class="breadcrumb-item">
                                                <span class="bullet bg-gray-500 w-5px h-2px"></span>
                                            </li>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <li class="breadcrumb-item text-muted">
                                                Account </li>
                                            <!--end::Item-->

                                        </ul>
                                        <!--end::Breadcrumb-->
                                    </div>
                                    <!--end::Page title-->
                                    <!--begin::Actions-->
                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                        <!--begin::Filter menu-->
                                        <div class="d-flex">
                                            <select name="campaign-type" data-control="select2" data-hide-search="true"
                                                class="form-select form-select-sm bg-body border-body w-175px">
                                                <option value="Twitter" selected="selected">Select Campaign</option>
                                                <option value="Twitter">Twitter Campaign</option>
                                                <option value="Twitter">Facebook Campaign</option>
                                                <option value="Twitter">Adword Campaign</option>
                                                <option value="Twitter">Carbon Campaign</option>
                                            </select>

                                            <a href="#" class="btn btn-icon btn-sm btn-success flex-shrink-0 ms-4"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
                                                <i class="ki-duotone ki-plus fs-2"></i>
                                            </a>
                                        </div>
                                        <!--end::Filter menu-->


                                        <!--begin::Secondary button-->
                                        <!--end::Secondary button-->

                                        <!--begin::Primary button-->
                                        <!--end::Primary button-->
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Toolbar container-->
                            </div>
                            <!--end::Toolbar-->





                            <!-- anza -->
                            <!--begin::Content-->
                            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container  container-xxl ">

                                    <!--begin::Navbar-->
                                    <div class="card card-flush mb-9" id="kt_user_profile_panel">
                                        <!--begin::Hero nav-->
                                        <div class="card-header rounded-top bgi-size-cover h-200px"
                                            style="background-position: 100% 50%; background-image:url('resources/2/assets/media/misc/profile-head-bg.jpg')">
                                        </div>
                                        <!--end::Hero nav-->

                                        <!--begin::Body-->
                                        <div class="card-body mt-n19">
                                            <!--begin::Details-->
                                            <div class="m-0">
                                                <!--begin: Pic-->
                                                <div class="d-flex flex-stack align-items-end pb-4 mt-n19">
                                                    <div
                                                        class="symbol symbol-125px symbol-lg-150px symbol-fixed position-relative mt-n3">
                                                        <img src="resources/2/assets/media/avatars/300-3.jpg" alt="image"
                                                            class="border border-white border-4"
                                                            style="border-radius: 20px" />
                                                        <div
                                                            class="position-absolute translate-middle bottom-0 start-100 ms-n1 mb-9 bg-success rounded-circle h-15px w-15px">
                                                        </div>
                                                    </div>

                                                    <!--begin::Toolbar-->
                                                    <div class="me-0">
                                                        <button
                                                            class="btn btn-icon btn-sm btn-active-color-primary  justify-content-end pt-3"
                                                            data-kt-menu-trigger="click"
                                                            data-kt-menu-placement="bottom-end">
                                                            <i class="fonticon-settings fs-2"></i>
                                                        </button>

                                                        <!--begin::Menu 3-->
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                                            data-kt-menu="true">
                                                            <!--begin::Heading-->
                                                            <div class="menu-item px-3">
                                                                <div
                                                                    class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                                                    Payments
                                                                </div>
                                                            </div>
                                                            <!--end::Heading-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Create Invoice
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link flex-stack px-3">
                                                                    Create Payment

                                                                    <span class="ms-2" data-bs-toggle="tooltip"
                                                                        title="Specify a target name for future usage and reference">
                                                                        <i class="ki-duotone ki-information fs-6"><span
                                                                                class="path1"></span><span
                                                                                class="path2"></span><span
                                                                                class="path3"></span></i> </span>
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="#" class="menu-link px-3">
                                                                    Generate Bill
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                                                data-kt-menu-placement="right-end">
                                                                <a href="#" class="menu-link px-3">
                                                                    <span class="menu-title">Subscription</span>
                                                                    <span class="menu-arrow"></span>
                                                                </a>

                                                                <!--begin::Menu sub-->
                                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Plans
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Billing
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3">
                                                                            Statements
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu separator-->
                                                                    <div class="separator my-2"></div>
                                                                    <!--end::Menu separator-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <div class="menu-content px-3">
                                                                            <!--begin::Switch-->
                                                                            <label
                                                                                class="form-check form-switch form-check-custom form-check-solid">
                                                                                <!--begin::Input-->
                                                                                <input
                                                                                    class="form-check-input w-30px h-20px"
                                                                                    type="checkbox" value="1"
                                                                                    checked="checked"
                                                                                    name="notifications" />
                                                                                <!--end::Input-->

                                                                                <!--end::Label-->
                                                                                <span
                                                                                    class="form-check-label text-muted fs-6">
                                                                                    Recuring
                                                                                </span>
                                                                                <!--end::Label-->
                                                                            </label>
                                                                            <!--end::Switch-->
                                                                        </div>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu sub-->
                                                            </div>
                                                            <!--end::Menu item-->

                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3 my-1">
                                                                <a href="#" class="menu-link px-3">
                                                                    Settings
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                        <!--end::Menu 3-->
                                                    </div>
                                                    <!--end::Toolbar-->
                                                </div>
                                                <!--end::Pic-->

                                                <!--begin::Info-->
                                                <div class="d-flex flex-stack flex-wrap align-items-end">
                                                    <!--begin::User-->
                                                    <div class="d-flex flex-column">
                                                        <!--begin::Name-->
                                                        <div class="d-flex align-items-center mb-2">
                                                            <a href="#"
                                                                class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1">Bessie
                                                                Cooper</a>
                                                            <a href="#" class="" data-bs-toggle="tooltip"
                                                                data-bs-placement="right" title="Account is verified">
                                                                <i class="ki-duotone ki-verify fs-1 text-primary"><span
                                                                        class="path1"></span><span
                                                                        class="path2"></span></i> </a>
                                                        </div>
                                                        <!--end::Name-->

                                                        <!--begin::Text-->
                                                        <span class="fw-bold text-gray-600 fs-6 mb-2 d-block">
                                                            Design is like a fart. If you have to force it, it’s
                                                            probably shit.
                                                        </span>
                                                        <!--end::Text-->

                                                        <!--begin::Info-->
                                                        <div
                                                            class="d-flex align-items-center flex-wrap fw-semibold fs-7 pe-2">
                                                            <a href="#"
                                                                class="d-flex align-items-center text-gray-500 text-hover-primary">
                                                                UI/UX Design
                                                            </a>
                                                            <span
                                                                class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                                            <a href="#"
                                                                class="d-flex align-items-center text-gray-500 text-hover-primary">
                                                                Austin, TX
                                                            </a>
                                                            <span
                                                                class="bullet bullet-dot h-5px w-5px bg-gray-500 mx-3"></span>
                                                            <a href="#" class="text-gray-500 text-hover-primary">
                                                                3,450 Followers
                                                            </a>
                                                        </div>
                                                        <!--end::Info-->
                                                    </div>
                                                    <!--end::User-->

                                                    <!--begin::Actions-->
                                                    <div class="d-flex">
                                                        <a href="#" class="btn btn-sm btn-light me-3"
                                                            id="kt_drawer_chat_toggle">Send Message</a>

                                                        <button class="btn btn-sm btn-primary"
                                                            id="kt_user_follow_button">
                                                            <i class="ki-duotone ki-check fs-2 d-none"></i>
                                                            <!--begin::Indicator label-->
                                                            <span class="indicator-label">
                                                                Follow</span>
                                                            <!--end::Indicator label-->

                                                            <!--begin::Indicator progress-->
                                                            <span class="indicator-progress">
                                                                Please wait... <span
                                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                            </span>
                                                            <!--end::Indicator progress-->
                                                        </button>
                                                    </div>
                                                    <!--end::Actions-->
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                    </div>
                                    <!--end::Navbar-->

                                    <!--begin::Nav items-->
                                    <div id="kt_user_profile_nav"
                                        class="rounded bg-gray-200 d-flex flex-stack flex-wrap mb-9 p-2"
                                        data-kt-sticky="true" data-kt-sticky-name="sticky-profile-navs"
                                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                                        data-kt-sticky-width="{target: '#kt_user_profile_panel'}"
                                        data-kt-sticky-left="auto" data-kt-sticky-top="70px"
                                        data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                                        <!--begin::Nav-->
                                        <ul class="nav flex-wrap border-transparent">
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    active" href="overview.html">

                                                    Overview </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="settings.html">

                                                    Settings </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="security.html">

                                                    Security </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="activity.html">

                                                    Activity </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="billing.html">

                                                    Billing </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="statements.html">

                                                    Statements </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="referrals.html">

                                                    Referrals </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="api-keys.html">

                                                    API Keys </a>
                                            </li>
                                            <!--end::Nav item-->
                                            <!--begin::Nav item-->
                                            <li class="nav-item my-1">
                                                <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    " href="logs.html">

                                                    Logs </a>
                                            </li>
                                            <!--end::Nav item-->
                                        </ul>
                                        <!--end::Nav-->
                                    </div>
                                    <!--end::Nav items-->
                                    <!--begin::details View-->
                                    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                                        <!--begin::Card header-->
                                        <div class="card-header cursor-pointer">
                                            <!--begin::Card title-->
                                            <div class="card-title m-0">
                                                <h3 class="fw-bold m-0">Profile Details</h3>
                                            </div>
                                            <!--end::Card title-->

                                            <!--begin::Action-->
                                            <a href="settings.html"
                                                class="btn btn-sm btn-primary align-self-center">Edit Profile</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--begin::Card header-->

                                        <!--begin::Card body-->
                                        <div class="card-body p-9">
                                            <!--begin::Row-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Max Smith</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Row-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Company</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8 fv-row">
                                                    <span class="fw-semibold text-gray-800 fs-6">Keenthemes</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">
                                                    Contact Phone

                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Phone number must be active">
                                                        <i class="ki-duotone ki-information fs-7"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i> </span>
                                                </label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8 d-flex align-items-center">
                                                    <span class="fw-bold fs-6 text-gray-800 me-2">044 3276 454
                                                        935</span>
                                                    <span class="badge badge-success">Verified</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Company Site</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <a href="#"
                                                        class="fw-semibold fs-6 text-gray-800 text-hover-primary">keenthemes.com</a>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">
                                                    Country

                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                        title="Country of origination">
                                                        <i class="ki-duotone ki-information fs-7"><span
                                                                class="path1"></span><span class="path2"></span><span
                                                                class="path3"></span></i> </span>
                                                </label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Germany</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-7">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Communication</label>
                                                <!--end::Label-->

                                                <!--begin::Col-->
                                                <div class="col-lg-8">
                                                    <span class="fw-bold fs-6 text-gray-800">Email, Phone</span>
                                                </div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Input group-->

                                            <!--begin::Input group-->
                                            <div class="row mb-10">
                                                <!--begin::Label-->
                                                <label class="col-lg-4 fw-semibold text-muted">Allow Changes</label>
                                                <!--begin::Label-->

                                                <!--begin::Label-->
                                                <div class="col-lg-8">
                                                    <span class="fw-semibold fs-6 text-gray-800">Yes</span>
                                                </div>
                                                <!--begin::Label-->
                                            </div>
                                            <!--end::Input group-->


                                            <!--begin::Notice-->
                                            <div
                                                class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                                                <!--begin::Icon-->
                                                <i class="ki-duotone ki-information fs-2tx text-warning me-4"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span></i>
                                                <!--end::Icon-->

                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-stack flex-grow-1 ">
                                                    <!--begin::Content-->
                                                    <div class=" fw-semibold">
                                                        <h4 class="text-gray-900 fw-bold">We need your attention!</h4>

                                                        <div class="fs-6 text-gray-700 ">Your payment was declined. To
                                                            start using tools, please <a class="fw-bold"
                                                                href="billing.html">Add Payment Method</a>.</div>
                                                    </div>
                                                    <!--end::Content-->

                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Notice-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::details View-->


                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Table Widget 5-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->

        </div>
        <!--end::Content wrapper-->






        <!--begin::Javascript-->
        <script>
        var hostUrl = "resources/2/assets/index.html";
        </script>

        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="resources/2/assets/plugins/global/plugins.bundle.js"></script>
        <script src="resources/2/assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->

        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="resources/2/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <!--end::Vendors Javascript-->

        <!--begin::Custom Javascript(used for this page only)-->
        <script src="resources/2/assets/js/custom/pages/user-profile/general.js"></script>
        <script src="resources/2/assets/js/widgets.bundle.js"></script>
        <script src="resources/2/assets/js/custom/apps/chat/chat.js"></script>
        <script src="resources/2/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
        <script src="resources/2/assets/js/custom/utilities/modals/create-campaign.js"></script>
        <script src="resources/2/assets/js/custom/utilities/modals/create-app.js"></script>
        <script src="resources/2/assets/js/custom/utilities/modals/users-search.js"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->
</body>
<!--end::Body-->

</html>