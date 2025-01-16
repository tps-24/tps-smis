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

        <div class="d-flex justify-content-center" style="margin-top:10">

          <!-- Form starts -->
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Logo starts -->
            <center>
                <a href="/tps-rms" class="auth-logo mt-5 mb-3">
                <img src="resources/assets/images/logo.png" style="height:200 !important; width:200" alt="Police Logo" />
                </a>
            </center>
            <!-- Logo ends -->

            <!-- Authbox starts -->
            <div class="auth-box">

              <h4 class="mb-4" style="text-align:center; color: #072A6C">TPS - SMIS</h4>

              <div class="mb-3">
                <label class="form-label" for="email">{{ __('Username') }} <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                  </span>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"   placeholder="Enter your username" required autocomplete="email" autofocus>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="mb-2">
                <label class="form-label" for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                  </span>
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter password" required autocomplete="current-password">
                  <button class="btn btn-outline-secondary" type="button">
                    <i class="bi bi-eye"></i>
                  </button>
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <!-- <div class="d-flex justify-content-end mb-3">
                <a href="#" class="text-decoration-underline"></a>
              </div> -->

              <div class="d-grid gap-4" style="margin-top:40px">
                <!-- <a href="#" class="btn btn-outline-dark">Not registered? Signup</a> -->
                  <button type="submit" class="btn btn-primary">
                  {{ __('Login') }}
                  </button>

                  @if (Route::has('password.request'))
                  <!-- <a class="btn btn-link" href="{{ route('password.request') }}"> -->
                  <a class="btn btn-link" href="#">
                      {{ __('Forgot Your Password?') }}
                  </a>
                  @endif
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