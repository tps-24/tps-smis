c <!-- Sidebar wrapper starts -->
 <nav id="sidebar" class="sidebar-wrapper">

<!-- App brand starts -->
<div class="app-brand p-3 my-2">
  <a href="index.html">
    <!-- <img src="resources/assets/images/logo.svg" class="logo" alt="Bootstrap Gallery" /> -->
  </a>
</div>

<!-- App brand ends -->

<!-- Sidebar menu starts -->
<div class="sidebarMenuScroll">
  <ul class="sidebar-menu">
    <li>
      <a href="/tps-rms">
        <i class="bi bi-bar-chart-line"></i>
        <span class="menu-text">Dashboard</span>
      </a>
    </li>
    @can('student-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-box"></i>
        <span class="menu-text">Students</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="/tps-rms/students">Student Details</a>
        </li>
        <li>
          <a href="students/create">Student Registration</a>
        </li>
      </ul>
    </li>
    @endcan()
    
    @can('staff-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-box"></i>
        <span class="menu-text">Staffs</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#">Staff Details</a>
        </li>
        <li>
          <a href="#">Staff Registration</a>
        </li>
      </ul>
    </li>
    @endcan()
    @can('attendance-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-bar-chart-line"></i>
        <span class="menu-text">Attendances</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="/tps-rms/attendences/type/1">Morning</a>
        </li>
        <li>
          <a href="/tps-rms/attendences/type/2">Master Parade</a>
        </li>
        <li>
          <a href="/tps-rms/attendences/type/3">Night</a>
        </li>
        <li>
          <a href="/tps-rms/attendences/type/4">Flag</a>
        </li>
      </ul>
    </li>
    @endcan()
    @can('academic-view')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-box"></i>
        <span class="menu-text">Academics</span>
      </a>
      <ul class="treeview-menu">
        @can('programme-list')
        <li>
          <a href="{{ route('programmes.index') }}">Programmes</a>
        </li>
        @endcan()
        @can('course-list')
        <li>
          <a href="{{ route('courses.index') }}">Courses</a>
        </li>
        @endcan()
        <li>
          <a href="{{ route('courses.index') }}">My Courses</a>
        </li>
        <li>
          <a href="{{ route('enrollments.index') }}">Optional Courses</a>
        </li>
      </ul>
    </li>
    @endcan()
    @can('coursework-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-stickies"></i>
        <span class="menu-text">Course work</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#">semester 1</a>
        </li>
        <li>
          <a href="#">semester 2</a>
        </li>
      </ul>
    </li>
    @endcan()
    
    @can('semester-exam-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-stickies"></i>
        <span class="menu-text">Examination (UE)</span>
      </a>
      
      <ul class="treeview-menu">
        @can('generate-results')
        <li>
          <a href="{{ route('final_results.generate') }}">Generate Results</a>
        </li>
        @endcan()
        <li>
          <a href="{{ route('final_results.index') }}">semester 1</a>
        </li>
        <li>
          <a href="{{ route('final_results.index') }}">semester 2</a>
        </li>
      </ul>
    </li>
    @endcan()

    @can('print-certificate')
    <li>
      <a href="#">
        <i class="bi bi-printer"></i>
        <span class="menu-text">Print Certificate(s)</span>
      </a>
    </li>
    @endcan()
    
    <li>
      <a href="#">
        <i class="bi bi-send"></i>
        <span class="menu-text">Announcements</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="bi bi-download"></i>
        <span class="menu-text">Download Center</span>
      </a>
    </li>
    <!-- <li>
      <a href="#">
        <i class="bi bi-globe"></i>
        <span class="menu-text">Hostel</span>
      </a>
    </li> -->
    @can('hosptal-list')
    <li>
      <a href="{{ route('hospital.index') }}">
        <i class="bi bi-calendar2"></i>
        <span class="menu-text">Hospital</span>
      </a>
    </li>
    @endcan()
    @can('mps-list')
    <li>
      <a href="#">
        <i class="bi bi-wallet2"></i>
        <span class="menu-text">MPS</span>
      </a>
    </li>
    @endcan()
    <li>
      <a href="#">
        <i class="bi bi-mouse3"></i>
        <span class="menu-text">Leave(s)</span>
      </a>
    </li>
    @can('beat-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-pie-chart"></i>
        <span class="menu-text">Guards &amp; Patrols</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#">Guards</a>
        </li>
        <li>
          <a href="#">Patrol</a>
        </li>
      </ul>
    </li>
    @endcan()
    <li>
      <a href="{{ route('users.index') }}">
        <i class="bi bi-border-all"></i>
        <span class="menu-text">Users</span>
      </a>
    </li>
    @can('role-list')
    <li>
      <a href="{{ route('roles.index') }}">
        <i class="bi bi-archive"></i>
        <span class="menu-text">Roles &amp; Permissions</span>
      </a>
    </li>
    @endcan()
    @can('report-list')
    <li>
      <a href="#">
        <i class="bi bi-border-all"></i>
        <span class="menu-text">Reports</span>
      </a>
    </li>
    @endcan()
    @can('setting-list')
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-gear"></i>
        <span class="menu-text">Settings</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#">General Settings</a>
        </li>
        <li>
          <a href="{{ route('semesters.index') }}">Semester Settings</a>
        </li>
        <li>
          <a href="{{ route('session_programmes.index') }}">Session Settings</a>
        </li>
        <li>
          <a href="#">Notification Setting</a>
        </li>
        
        @can('create-backup')
        <li>
          <a href="#">Backup & Restore</a>
        </li>
        @endcan()
      </ul>
    </li>
    @endcan()
    <li>
      <a href="#">
        <i class="bi bi-headphones"></i>
        <span class="menu-text">Support</span>
      </a>
    </li>
  </ul>
</div>
<!-- Sidebar menu ends -->

</nav>
<!-- Sidebar wrapper ends -->