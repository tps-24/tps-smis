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
    <li>
      <a href="/tps-rms/attendences">
        <i class="bi bi-bar-chart-line"></i>
        <span class="menu-text">Attendences</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="bi bi-send"></i>
        <span class="menu-text">Print Certificate(s)</span>
      </a>
    </li>
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-box"></i>
        <span class="menu-text">Academics</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#">View Modules</a>
        </li>
        <li>
          <a href="#">Module Registration</a>
        </li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-stickies"></i>
        <span class="menu-text">Examination</span>
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
    <li>
      <a href="#">
        <i class="bi bi-globe"></i>
        <span class="menu-text">Hostel</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="bi bi-calendar2"></i>
        <span class="menu-text">Hospital</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="bi bi-wallet2"></i>
        <span class="menu-text">MPS</span>
      </a>
    </li>
    <li>
      <a href="#">
        <i class="bi bi-mouse3"></i>
        <span class="menu-text">Leaves</span>
      </a>
    </li>
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
    <li>
      <a href="{{ route('users.index') }}">
        <i class="bi bi-border-all"></i>
        <span class="menu-text">Users</span>
      </a>
    </li>
    <li class="treeview">
      <a href="#!">
        <i class="bi bi-ui-checks-grid"></i>
        <span class="menu-text">Roles &amp; Permission</span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ route('roles.index') }}">Roles</a>
        </li>
        <li>
          <a href="#">Permission</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="tables.html">
        <i class="bi bi-border-all"></i>
        <span class="menu-text">Reports</span>
      </a>
    </li>
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
          <a href="{{ route('session_programmes.index') }}">Session Settings</a>
        </li>
        <li>
          <a href="#">Notification Setting</a>
        </li>
        
        <li>
          <a href="#">Backup & Restore</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="support.html">
        <i class="bi bi-headphones"></i>
        <span class="menu-text">Support</span>
      </a>
    </li>
  </ul>
</div>
<!-- Sidebar menu ends -->

</nav>

        <!-- Sidebar wrapper ends -->
