<style>
 /* General Sidebar Styles */
.sidebar-menu {
  list-style: none;
  margin: 0;
  padding: 0;
}

.sidebar-menu a {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  color: #343a40; /* Default text color */
  text-decoration: none;
  transition: background-color 0.3s, color 0.3s;
}

.sidebar-menu a:hover {
  background-color: #f1f1f1; /* Hover state */
  color:rgb(14, 178, 207); /* Text color on hover */
}

/* Active Menu Item Styles */
a.active {
  background-color:rgb(10, 197, 221); /* Active menu background */
  color: #ffffff; /* Text color for active menu */
  font-weight: bold; /* Emphasize active menu */
  border-left: 4px solidrgb(24, 153, 175); /* Left border for active indicator */
}

/* Treeview Styling */
.treeview.menu-open > .treeview-menu {
  display: block;
}

.treeview-menu {
  display: none;
  padding-left: 20px; /* Indentation for sub-menu items */
}

.treeview-menu a {
  padding: 8px 20px;
  font-size: 0.95rem; /* Slightly smaller font size for sub-menu */
  color: #6c757d; /* Sub-menu default text color */
}

.treeview-menu a.active {
  background-color:rgb(12, 143, 160); /* Darker blue for active sub-menu */
  color: #ffffff;
}
</style>

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
        @can('staff-create')
        <li>
        <a href="{{ route('staffs.create') }}">Staff Registration</a>
        </li>
        <li>
        <a href="{{ route('staff.resume') }}">Staff Resume</a>
        </li>
        @endcan()
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
        <a href="/tps-smis/attendences/type/4">Evening</a>
        </li>
        <li>
        <a href="/tps-smis/attendences/type/3">Night</a>
        </li>
      </ul>
      </li>
    @endcan()

    <li class="treeview">
        <a href="#!">
          <i class="bi bi-heart-pulse"></i>
          <span class="menu-text">Hospital</span>
        </a>
        <ul class="treeview-menu">
        @can('hospital-dashboard')
          <li>
            <a href="{{ route('dispensary.page') }}">Hospital Dashboard</a>
          </li>
        @endcan()

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
        @can('hospital-edit')
        <li>
        <a href="{{ route('doctor.page') }}">
          <i class="bi bi-stethoscope"></i>
          <span class="menu-text">Doctor Panel</span>
        </a>
        </li>
        @endcan()
        </ul>
      </li>

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
            @can('semester-exam-list')
            <li>
            <a href="{{ route('semester_exams.index') }}">Semester Exam (SE)</a> <!-- For Teacher-->
            </li>
            @endcan()
            @can('optional-enrollment-list')
            <li>
            <a href="{{ route('enrollments.index') }}">Optional Courses</a> <!-- For Academic Coord -->
            </li>
            @endcan()
            
            @can('generate-results')
            <li>
              <a href="{{ route('final_results.generate') }}">Generate Final Results</a>
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
    <li class="treeview">
    <a href="#">
        <i class="bi bi-mouse3"></i>
        <span class="menu-text">Leave(s)</span>
    </a>

    <ul class="treeview-menu">
        @if (auth()->check())
          @if (auth()->user()->hasRole('Student'))
              <li>
                  <a href="{{ route('leave-requests.index') }}">
                      Apply for Leave
                  </a>
              </li>
          @elseif (auth()->user()->hasRole('Sir Major'))
              <li>
                  <a href="{{ route('leave-requests.index') }}">
                      <i class="bi bi-inbox"></i> Sir Major Panel
                  </a>
              </li>
          @elseif (auth()->user()->hasRole('OC Coy'))
              <li>
                  <a href="{{ route('leave-requests.oc-panel') }}">
                      <i class="bi bi-person-video3"></i> OC Panel
                  </a>
              </li>
          @elseif (auth()->user()->hasRole('Chief Instructor'))
              <li>
                  <a href="{{ route('leave-requests.chief-instructor') }}">
                      <i class="bi bi-person-badge"></i> Chief Instructor Panel
                  </a>
              </li>
          @else
              <li>
                  <a href="{{ route('leave-requests.index') }}">
                      <i class="bi bi-inbox"></i> Leave Request Panel
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
    
    </ul>
    </li>

    @can('beat-list')
    <li class="treeview">
      <a href="!#">
        <i class="bi bi-pie-chart"></i>
        <span class="menu-text">Guards &amp; Patrols</span>
      </a>
      <ul class="treeview-menu">
        @can('beat-create')
        <li>
        <a href="{{url('beats')}}">Generate Beat</a>
        </li>
        @endcan()
        <li>
        <a href="{{url('/report/generate')}}">Beat Report</a>
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
        <a href="{{ route('certificates.index') }}">Certificate Settings</a>
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
 
<!-- JavaScript for interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
      const menuItems = document.querySelectorAll('.sidebar-menu a');
      const currentUrl = window.location.pathname;

      // Mark the current menu item as active
      menuItems.forEach(item => {
        if (item.href === window.location.origin + currentUrl) {
          item.classList.add('active');
          const parentTreeview = item.closest('.treeview');
          if (parentTreeview) {
            parentTreeview.classList.add('menu-open');
          }
        }
      });

      // Handle menu clicks to dynamically manage active state and open menus
      menuItems.forEach(item => {
        item.addEventListener('click', function () {
          // Remove "active" from all menu items
          menuItems.forEach(i => i.classList.remove('active'));

          // Add "active" to clicked item
          this.classList.add('active');

          // Collapse all treeviews
          const allTreeviews = document.querySelectorAll('.treeview');
          allTreeviews.forEach(tree => tree.classList.remove('menu-open'));

          // Expand treeview of the clicked item if it exists
          const clickedTreeview = this.closest('.treeview');
          if (clickedTreeview) {
            clickedTreeview.classList.add('menu-open');
          }
        });
      });
    });
</script>