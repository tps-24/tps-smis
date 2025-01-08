@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

@include('layouts.head')
<style>
    .auth-box{
        padding: 30px;
        padding-bottom: 50px;
    }
</style>

  <body>

    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- Auth container starts -->
      <div class="auth-container">

        <div class="d-flex justify-content-center" style="margin-top:100px">

          <!-- Form starts -->
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Logo starts -->
            <center>
                <a href="/" class="auth-logo mt-5 mb-3">
                <img src="resources/assets/images/logo.png" style="height:180 !important; width:180" alt="Police Logo" />
                </a>
            </center>
            <!-- Logo ends -->

            <!-- Authbox starts -->
            <div class="auth-box">

              <h4 class="mb-4" style="text-align:center; color: #072A6C">TPS - RMS</h4>

              <div class="mb-3">
                <label class="form-label" for="email">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input type="text" id="email" class="form-control" placeholder="Enter your username">
                </div>
              </div>

              <div class="mb-2">
                <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input type="password" id="password" class="form-control" placeholder="Enter password">
                  <button class="btn btn-outline-secondary" type="button">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="d-flex justify-content-end mb-3">
                <a href="#" class="text-decoration-underline">Forgot password?</a>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
                <!-- <a href="#" class="btn btn-outline-dark">Not registered? Signup</a> -->
              </div>
            </div>
            <!-- Authbox ends -->

          </form>
          <!-- Form ends -->

        </div>

      </div>
      <!-- Auth container ends -->

    </div>
    <!-- Page wrapper ends -->

  </body>

</html>

@endsection