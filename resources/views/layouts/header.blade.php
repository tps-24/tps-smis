<!-- App header starts -->
<div class="app-header d-flex align-items-center">

    <!-- Toggle buttons starts -->
    <div class="d-flex">
        <button class="toggle-sidebar">
            <i class="bi bi-list lh-1"></i>
        </button>
        <button class="pin-sidebar">
            <i class="bi bi-list lh-1"></i>
        </button>
    </div>
    <!-- Toggle buttons ends -->

    <!-- App brand sm starts -->
    <div class="app-brand-sm d-lg-none d-flex">
        <!-- Logo sm starts -->
        <a href="index.html">
            <img src="assets/images/logo-sm.svg" class="logo" alt="Tps Gallery">
        </a>
        <!-- Logo sm end -->
    </div>
    <!-- App brand sm ends -->

            <!-- Page title starts -->

            <!-- Page title ends -->
    <!-- Session starts -->
    <span style="font-size:18px; margin-left: 1%; text-color:blue; color:#00008b; font-weight:500">Active Session:</span>
    <select class="form-select activeSession" id="abc4" aria-label="Default select example">
        <option selected="">Choose the session</option>
        <option value="1" selected>BASIC RECRUIT COURSE NO.1/2024/2025 </option>
        <option value="2">CORPORAL COURSE NO.1/2024/2025 </option>
        <option value="3">SERGEANT MAJOR COURSE NO.1/2024/2025 </option>
    </select>
    <!-- Session ends -->

    <!-- App header actions starts -->
    <div class="header-actions">

        <!-- Search container start -->
        <div class="search-container d-xl-block d-none me-3">
            <input type="text" class="form-control" id="searchData" placeholder="Search" />
            <i class="bi bi-search"></i>
        </div>
        <!-- Search container ends -->

        <!-- Header action bar starts -->
        <div class="bg-white p-2 rounded-4 d-flex align-items-center">

            <!-- Header actions start -->
            <div class="d-sm-flex d-none">
                <!-- Notifications and actions as they were -->
            </div>
            <!-- Header actions end -->

            <!-- User settings start -->
            <div class="dropdown ms-2">
                @if(Auth::check()) <!-- Check if user is authenticated -->
                    <a id="userSettings" class="dropdown-toggle user-settings" href="#!" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2 text-truncate d-lg-block d-none">{{ Auth::user()->name }}</span>
                        <div class="icon-box md rounded-4 fw-bold bg-primary-subtle text-primary">
                            <?php
                            $string = Auth::user()->name;
                            function getFirstLetters($string) {
                                // Split the string into words
                                $words = explode(' ', $string);
                                $firstLetters = '';

                                // Loop through each word and get the first letter
                                foreach ($words as $word) {
                                    if (!empty($word)) {
                                        $firstLetters .= $word[0];
                                    }
                                }

                                return $firstLetters;
                            }
                            echo getFirstLetters($string); // Prints First Letters of each name
                            ?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg">
                        <a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-person fs-4 me-2"></i>My Profile</a>
                        <a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-gear fs-4 me-2"></i>Account Settings</a>
                        <div class="mx-3 my-2 d-grid">
                            <a href="{{ route('logout') }}" class="btn btn-warning" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg">
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/profile/' . Auth::user()->id) }}"><i
                            class="bi bi-person fs-4 me-2"></i>My Profile</a>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/profile/change-password/' . Auth::user()->id) }}"><i
                            class="bi bi-gear fs-4 me-2"></i>Change Password</a>
                    <div class="mx-3 my-2 d-grid">
                        <a href="{{ route('logout') }}" class="btn btn-warning" 
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                @endif
            </div>
            <!-- User settings end -->

        </div>
        <!-- Header action bar ends -->

    </div>
    <!-- App header actions ends -->

</div>
    @yield('scrumb')
</div>
<!-- App header ends -->
