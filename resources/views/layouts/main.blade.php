<!DOCTYPE html>
<html lang="en">
@extends('layouts.head')

  
<!-- Mirrored from bootstrapget.com/demos/cube-admin-template/default-layout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Jan 2025 11:49:56 GMT -->


  <body>
    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- Main container starts -->
      <div class="main-container">

      @include('layouts.sidebar')

        <!-- App container starts -->
        <div class="app-container">

        @include('layouts.header')

        <!-- header -->

          <!-- App body starts -->
          <div class="app-body">

            <!-- Row starts -->
            <div class="row gx-4">
              <div class="col-sm-12 col-12">
                <div class="card mb-4">
                  <div class="card-header">
                    <h5 class="card-title">
                    @yield('content')
                  </div>
                  <div class="card-body"></div>
                </div>
              </div>
            </div>
            <!-- Row ends -->

          </div>
          <!-- App body ends -->
          @include('layouts.footer')
            <!-- footer ends-->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->
             <!-- *************
			************ JavaScript Files *************
		************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="resources/assets/js/jquery.min.js"></script>
    <script src="resources/assets/js/bootstrap.bundle.min.js"></script>

    <!-- *************
			************ Vendor Js Files *************
		************* -->

    <!-- Overlay Scroll JS -->
    <script src="resources/assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
    <script src="resources/assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

    <!-- Custom JS files -->
    <script src="resources/assets/js/custom.js"></script>
 
  </body>


<!-- Mirrored from bootstrapget.com/demos/cube-admin-template/default-layout.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Jan 2025 11:49:56 GMT -->
</html>