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
        <!-- <a href="index.html">
            <img src="assets/images/logo-sm.svg" class="logo" alt="Tps Gallery">
        </a> -->
        <!-- Logo sm end -->
    </div>
    <!-- App brand sm ends -->

    <!-- Page title starts -->

    <!-- Page title ends -->
    <!-- Session starts -->
    @php
    use Illuminate\Support\Facades\DB;

    // Retrieve all session programmes from the database
    $sessionProgrammes = DB::table('session_programmes')->where('is_current',1)->get();

    // Check if a session ID has been submitted
    if (request()->has('session_id')) {
    // Store the selected session ID in the session
    session(['selected_session' => request()->session_id]);
    }

    // Get the selected session ID from the session
    $selectedSessionId = session('selected_session');

    @endphp

    @can('programme-session-list')
    <div class="input-group" style="font-size:18px; margin-left: 1%; height:36px; width:30%">
        <form action="{{ url()->current() }}" method="GET" style="display:inline-block; margin-left:10px;">
            <div class="input-group" style="font-size:18px; height:36px;">
                <button class="btn btn-outline-secondary" type="button">
                    Active Session
                </button>
                <select name="session_id" class="form-control activeSession" id="abc4" onchange="this.form.submit()">
                    <option value="" disabled {{ !$selectedSessionId ? 'selected' : '' }}>Choose the session</option>
                    @foreach($sessionProgrammes as $sessionProgramme)
                    <option value="{{ $sessionProgramme->id }}"
                        {{ $selectedSessionId == $sessionProgramme->id ? 'selected' : '' }}>
                        {{ $sessionProgramme->programme_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    @endcan()
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


            <!-- Notification script starts -->
            <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
            <script>
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;


            var pusher = new Pusher('3a9b85e8ad0fb87a0a56', {
                cluster: 'mt1',
                encrypted: true, // Use encrypted connection (recommended)
                reconnection: true, // Enable automatic reconnection
                reconnectionAttempts: 5, // Max number of reconnection attempts
                reconnectionDelay: 1000, // Time in ms before each retry attempt
                reconnectTimeout: 5000, // Timeout before retrying reconnection
            });
            var channel = pusher.subscribe('announcements');
            channel.bind('announcement', function(data) {
                //app.messages.push(JSON.stringify(data));
                var countLabel = document.querySelector('.count-label');
                countLabel.style.background = 'red';
                var currentValue = parseInt(countLabel.textContent, 10);
                var newValue = currentValue + 1;
                countLabel.textContent = newValue;

                // Add notification to the dropdown
                var notificationsContainer = document.querySelector('.dropdown-menu .mx-3.d-flex.flex-column');
                var notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item';
                notificationItem.innerHTML = `
                        <div class="bg-danger-subtle border border-${data.announcement.type} px-3 py-2 rounded-1">
                        <a href="javascript:void(0)" class="dropdown-item text-${data.announcement.type} d-flex align-items-center">
                            ${data.announcement.title}
                        </a>
                        <p class="small m-0">${data.announcement.created_at}</p>
                        </div>
                    `;
                notificationsContainer.prepend(notificationItem); // Add to the top of the list

            });
            window.addEventListener('online', function() {
                console.log('Internet connection restored. Attempting to reconnect...');
                pusher.connect(); // Manually trigger the connection if needed
            });

            window.addEventListener('offline', function() {
                console.log('You are offline. Connection may be lost.');
            });
            </script>



            <div class="d-sm-flex d-none">
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex p-3 position-relative" href="#!" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-4 lh-1 text-primary"></i>
                        <span class="count-label">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
                        <h5 class="fw-semibold px-3 py-2 text-primary">Notifications</h5>
                        <div class="scroll250">
                            <div class="mx-3 d-flex gap-2 flex-column">
                                <div class="bg-danger-subtle border border-danger px-3 py-2 rounded-1">
                                    <p class="m-0 text-danger">New product purchased</p>
                                    <p class="small m-0">Just now</p>
                                </div>

                            </div>
                        </div>
                        <div class="d-grid m-3">
                            <a href="javascript:void(0)" class="btn btn-primary">View all</a>
                        </div>
                    </div>
                </div>

            </div>
            <!-- User settings start -->
            <div class="dropdown ms-2">
                @if(Auth::check())
                <!-- Check if user is authenticated -->

                <a id="userSettings" class="dropdown-toggle user-settings" href="#!" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 text-truncate d-lg-block d-none">{{ Auth::user()->name }}</span>
                    <div class="icon-box md rounded-4 fw-bold bg-primary-subtle text-primary">

                        <?php
                            $string = Auth::user()->name;
                            function getFirstLetters($string)
                            {
                                // Split the string into words
                                $words        = explode(' ', $string);
                                $firstLetters = '';

                                // Loop through each word and get the first letter
                                foreach ($words as $word) {
                                    if (! empty($word)) {
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
                    <a class="dropdown-item d-flex align-items-center" @if(auth()->user()->hasRole('Student'))
                        href="{{ url('student/profile/' . Auth::user()->id) }}" @else
                        href="{{ url('staff/profile/' . Auth::user()->id) }}" @endif>
                        <i class="bi bi-person fs-4 me-2"></i>My Profile
                    </a>

                    <a class="dropdown-item d-flex align-items-center"
                        href="{{ url('/profile/change-password/' . Auth::user()->id) }}"><i
                            class="bi bi-gear fs-4 me-2"></i>Change Password</a>
                    <div class="mx-3 my-2 d-grid">
                        <a href="{{ route('logout') }}" class="btn btn-warning"
                            onclick="event.preventDefault();
                                                                                                                            document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
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