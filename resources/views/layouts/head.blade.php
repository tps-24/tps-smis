<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TPS - SMIS</title>

    <!-- Meta -->
    <meta name="description" content="System for facilitating essential functions of TPS Administration" />
    <meta name="author" content="Tanzania Police School" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="http://localhost/tps-smis/">
    <meta property="og:url" content="http://localhost/tps-smis/">
    <meta property="og:title" content="TPS - Moshi | Tanzania Police School">
    <meta property="og:description" content="System for facilitating essential functions of TPS Administration">
    <meta property="og:type" content="Management System">
    <meta property="og:site_name" content="TPS - Moshi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/tps-smis/resources/assets/images/police-tz-logo.png" />
   


    <!-- *************
			************ CSS Files *************
		************* -->
    <link rel="stylesheet" href="/tps-smis/resources/assets/fonts/bootstrap/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/main.min.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/custom.css" />
    <link rel="stylesheet" href="/tps-smis/resources/assets/css/mine.css" />

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="/tps-smis/resources/assets/js/sweet_alert.js"></script>
    <!-- *************
			************ Vendor Css Files *************
		************ -->

    <!-- Scrollbar CSS -->
    <link rel="stylesheet" href="/tps-smis/resources/assets/vendor/overlay-scroll/OverlayScrollbars.min.css" />
    <style>
      .error{
        color: red;
        font-size: 15px;
      }
      .table-responsive td, .table-responsive th { 
        font-weight: normal;
      }
  /* Global responsive style for SweetAlert popups */
  .swal2-popup {
    max-width: 90vw !important;
    box-sizing: border-box;
    word-wrap: break-word;
    white-space: normal;
    font-size: 1rem;
    padding: 1rem;
  }

    </style>
    @yield('style')
    
  </head>