<nav class="main-header navbar navbar-expand navbar-light bg-success">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link text-white" data-toggle="dropdown" href="#">
        <i class="fas fa-user-cog text-white"></i>
        <span class="badge badge-warning navbar-badge"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">        
        <div class="dropdown-divider"></div>        
        <!-- Ubah Password -->
        <a href="{{ route('password.edit') }}" class="dropdown-item">
          <i class="fas fa-key mr-2"></i> Ubah Password
        </a>
        <div class="dropdown-divider"></div>
        <!-- Logout -->
        <a href="#" class="dropdown-item"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </div>

      {{-- <a class="nav-link text-white" data-toggle="dropdown" href="#">
        <i class="fas fa-user-cog text-white"></i>
        <span class="badge badge-warning navbar-badge">15</span>
      </a> --}}
      {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">15 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> 4 new messages
          <span class="float-right text-muted text-sm">3 mins</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 8 friend requests
          <span class="float-right text-muted text-sm">12 hours</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> 3 new reports
          <span class="float-right text-muted text-sm">2 days</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div> --}}
    </li>
  </ul>
</nav>