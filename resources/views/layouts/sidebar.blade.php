<!-- Sidebar wrapper starts -->
<nav id="sidebar" class="sidebar-wrapper">

  <!-- App brand starts -->
  <div class="app-brand p-3 my-2">
    <a href="#">
      <!-- <img src="resources/assets/images/logo.svg" class="logo" alt="Bootstrap Gallery" /> -->
    </a>
  </div>

  <!-- App brand ends -->

  <!-- Sidebar menu starts -->
  <div class="sidebarMenuScroll">
    <ul class="sidebar-menu">
      @if (auth()->check())
      @if (auth()->user()->hasRole('Student'))
      <li>
      <a href="{{ route('students.dashboard') }}">
      <i class="bi bi-bar-chart-line"></i>
      <span class="menu-text">Dashboard</span>
      </a>
      </li>
    @else
      <li>
      <a href="/tps-smis">
      <i class="bi bi-bar-chart-line"></i>
      <span class="menu-text">Dashboard</span>
      </a>
      </li>
    @endif
    @else
      <li>
      <a href="/tps-smis">
        <i class="bi bi-bar-chart-line"></i>
        <span class="menu-text">Dashboard</span>
      </a>
      </li>
    @endif


      @can('student-list')
      <li class="treeview">
      <a href="#!">
        <i class="bi bi-box"></i>
        <span class="menu-text">Students</span>
      </a>
      <ul class="treeview-menu">
        <li>
        <a href="/tps-smis/students">Student Details</a>
        </li>
        @can('student-create')
      <li>
      <a href="/tps-smis/students/create">Student Registration</a>
      </li>
    @endcan()
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
        <a href="{{ route('staffs.index') }}">Staff Details</a>
        </li>
        <li>
        <a href="{{ route('staffs.create') }}">Staff Registration</a>
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
        <a href="/tps-smis/attendences/type/1">Morning</a>
        </li>
        <!-- <li>
        <a href="/tps-smis/attendences/type/2">Master Parade</a>
      </li> -->
        <li>
        <a href="/tps-smis/attendences/type/3">Night</a>
        </li>
        <!-- <li>
        <a href="/tps-smis/attendences/type/4">Flag</a>
      </li> -->
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
      <a href="{{ route('programmes.index') }}">Programmes</a> <!-- For Academic Coord-->
      </li>
    @endcan()
        @can('course-list')
      <li>
      <a href="{{ route('courses.index') }}">Courses</a> <!-- For Academic Coord-->
      </li>
    @endcan()
        <li>
        <a href="#">My Courses</a> <!-- For Teacher-->
        </li>
        <li>
        <a href="{{ route('coursework_results.index') }}">Coursework (CA)</a> <!-- For Teacher-->
        </li>
        <li>
            <a href="#!">
            Coursework 2
              <i class="bi bi-caret-right-fill"></i>
            </a>
            <ul class="treeview-menu">
              <li>
              <a href="{{ route('course_works.index') }}">CA Configurations</a> <!-- Add to academic tab -->
              </li>
              <li>
                <a href="#!">Nested 2.1</a>
              </li>
            </ul>
        </li>
        <li>
        <a href="#">Semester Exam (SE)</a> <!-- For Teacher-->
        </li>
        @can('optional-enrollment-list')
      <li>
      <a href="{{ route('enrollments.index') }}">Optional Courses</a> <!-- For Academic Coord -->
      </li>
    @endcan()
      </ul>
      </li>
    @endcan()
      @can('student-courses')
      <li>
      <a href="{{ route('students.myCourses') }}">
        <i class="bi bi-printer"></i>
        <span class="menu-text">My Courses</span>
      </a>
      </li>
    @endcan()
      @can('student-coursework-list')
      <li>
      <a href="{{ route('students.coursework') }}">
        <i class="bi bi-printer"></i>
        <span class="menu-text">Coursework</span>
      </a>
      </li>
    @endcan()
      @can('coursework-config')
      <li class="treeview">
      <a href="#!">
        <i class="bi bi-stickies"></i>
        <span class="menu-text">Coursework</span>
      </a>
      <ul class="treeview-menu">
        <li>
        <a href="{{ route('course_works.index') }}">CA Configurations</a> <!-- Add to academic tab -->
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
      <a href="{{url('/print-certificates')}}">
        <i class="bi bi-printer"></i>
        <span class="menu-text">Print Certificate(s)</span>
      </a>
      </li>
    @endcan()

      <li>
        <a href="{{ route('announcements.index') }}">
          <i class="bi bi-send"></i>
          <span class="menu-text">Announcements</span>
        </a>
      </li>
      <!-- <li>
      
    <a href="{{ route('downloads.index') }}">
              <i class="bi bi-download"></i>
        <span class="menu-text">Download Center</span>
      </a>
    </li> -->
      <li>
        <a href="{{ route('downloads.index') }}">
          <i class="bi bi-download"></i>
          <span class="menu-text">Download Center</span>
        </a>
      </li>
      <li>
        <a href="{{ route('timetable.index') }}">
          <i class="bi bi-calendar2"></i>
          <span class="menu-text">Timetable</span>
        </a>
      </li>


      <li class="treeview">
        <a href="#!">
          <i class="bi bi-hospital"></i>
          <span class="menu-text">Hospital</span>
        </a>
        <ul class="treeview-menu">
          <li>
            <a href="{{ route('dispensary.page') }}">Hospital Dashboard</a>
          </li>

          @can('hospital-create')
        <li>
        <a href="{{ route('hospital.index') }}">
          <i class="bi bi-hospital"></i>
          <span class="menu-text">Sick Panel</span>
        </a>
        </li>
        @endcan()

          @can('hospital-approve')
        <li>
        <a href="{{ route('receptionist.index') }}">
          <i class="bi bi-person-lines-fill"></i>
          <span class="menu-text">Receptionist Panel</span>
        </a>
        </li>
      @endcan()


          @can('hospital-update')
        <li>
        <a href="{{ route('doctor.page') }}">
          <i class="bi bi-stethoscope"></i>
          <span class="menu-text">Doctor Panel</span>
        </a>
        </li>
      @endcan()
        </ul>
      </li>


      @can('mps-list')
      <li class="treeview">
      <a href="!#">
        <i class="bi bi-pie-chart"></i>
        <span class="menu-text">MPS</span>
      </a>
      <ul class="treeview-menu">
        <li>
        <a href="/tps-smis/mps">Lock Up</a>
        </li>
        <li>
        <a href="{{ route('visitors.index') }}">Visitors</a>
        </li>
        <!-- <li>
        <a href="">Report</a>
        </li> -->
      </ul>
      </li>
    @endcan()
      <li>
        <a href="{{route('leaves.index')}}">
          <i class="bi bi-mouse3"></i>
          <span class="menu-text">Leave(s)</span>
        </a>
      </li>


      @can('beat-list')
      <li class="treeview">
      <a href="!#">
        <i class="bi bi-pie-chart"></i>
        <span class="menu-text">Guards &amp; Patrols</span>
      </a>
      <ul class="treeview-menu">
        <li>
        <a href="{{url('beats')}}">Generate Beat</a>
        </li>
        <li>
        <a href="{{url('/report/generate')}}">Beat History</a>
        </li>
      </ul>
      </li>
    @endcan()
      @can('user-list')
      <li>
      <a href="{{ route('users.index') }}">
        <i class="bi bi-border-all"></i>
        <span class="menu-text">Users</span>
      </a>
      </li>
    @endcan()
      @can('role-list')
      <li>
      <a href="{{ route('roles.index') }}">
        <i class="bi bi-archive"></i>
        <span class="menu-text">Roles &amp; Permissions</span>
      </a>
      </li>
    @endcan()
      <li>
      <a href="{{ route('timesheets.index') }}">
        <i class="bi bi-archive"></i>
        <span class="menu-text">Time Sheet</span>
      </a>
      </li>
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
        <a href="{{ route('departments.index') }}">Department Settings</a>
        </li>
        <li>
        <a href="{{ route('semesters.index') }}">Semester Settings</a>
        </li>
        <li>
        <a href="{{ route('session_programmes.index') }}">Session Settings</a>
        </li>
        <li>
        <a href="{{ route('excuse_types.index') }}">Excuse Type Settings</a>
        </li>
        <li>
        <a href="{{ route('campuses.index') }}">Campus Settings</a>
        </li>
        <li>
        <a href="{{ route('guard-areas.index') }}">Guard Areas</a>
        </li>
        <li>
        <a href="{{ route('patrol-areas.index') }}">Patrol Areas</a>
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