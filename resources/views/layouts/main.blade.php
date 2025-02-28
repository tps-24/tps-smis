<!DOCTYPE html>
<html lang="en">
  @include('layouts.head')
  

  <body>
    <!-- Page wrapper starts -->
    <div class="page-wrapper">
      <!-- Main container starts -->
      <div class="main-container">
        @include('layouts.sidebar')
        <!-- App container starts -->
        <div class="app-container">
          @include('layouts.header')
          <!-- header ends -->

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

      
			  <!--************ JavaScript Files *************-->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="/tps-smis/resources/assets/js/jquery.min.js"></script>
    <script src="/tps-smis/resources/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/tps-smis/assets/js/moment.min.js"></script>
    <!-- Custom JS files -->
    <script src="/tps-smis/resources/assets/js/custom.js"></script>

    <!-- Vendor Js Files -->
    <!-- Overlay Scroll JS -->
    <script src="/tps-smis/resources/assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
    <script src="/tps-smis/resources/assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

    <!-- Apex Charts -->
    <script src="/tps-smis/resources/assets/vendor/apex/apexcharts.min.js"></script>
    <script src="/tps-smis/resources/assets/vendor/apex/custom/analytics/stats.js"></script>
    <script src="/tps-smis/resources/assets/vendor/apex/custom/analytics/sales.js"></script>
    <script src="/tps-smis/resources/assets/vendor/apex/custom/analytics/views.js"></script>
    <script src="/tps-smis/resources/assets/vendor/apex/custom/analytics/audiences.js"></script>
    <!-- <script src="/tps-rms/resources/assets/vendor/apex/custom/analytics/orders.js"></script> -->
    <script src="/tps-smis/resources/assets/vendor/apex/custom/orders/orders.js"></script>

    <!-- Vector Maps -->
    <script src="/tps-smis/resources/assets/vendor/jvectormap/jquery-jvectormap-2.0.5.min.js"></script>
    <script src="/tps-smis/resources/assets/vendor/jvectormap/world-mill-en.js"></script>
    <script src="/tps-smis/resources/assets/vendor/jvectormap/gdp-data.js"></script>
    <script src="/tps-smis/resources/assets/vendor/jvectormap/continents-mill.js"></script>
    <script src="/tps-smis/resources/assets/vendor/jvectormap/custom/world-map-markers4.js"></script>

    <!-- Rating -->
    <script src="/tps-smis/resources/assets/vendor/rating/raty.js"></script>
    <script src="/tps-smis/resources/assets/vendor/rating/raty-custom.js"></script> 
    
    @yield('scripts')
  </body>
</html>