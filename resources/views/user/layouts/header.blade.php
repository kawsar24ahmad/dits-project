  <!-- Navbar -->
  @include('user.layouts.sidebar')
  <!-- /.navbar -->

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>

      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
          <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                  </button>
              </div>
          </div>
      </form>
      <!-- Right navbar links -->
      <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item me-3 d-none d-md-block">
              <button class="btn btn-outline-secondary">
                  <i class="bi bi-headset"></i> Support
              </button>
          </li>
          <li class="nav-item dropdown me-3">
              <a class="nav-link dropdown-toggle notification-bell" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-bell"></i>
                  <span class="notification-count">23</span>
              </a>
              <ul style="background-color: rgb(239, 242, 246);" class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                  <li>
                      <h6 class="dropdown-header">23 New Notifications</h6>
                  </li>
                  <li>
                      <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="#">Top-up successful on meta account ID: 608930715220106</a></li>
                  <li><a class="dropdown-item" href="#">Ad account shared with another BM</a></li>
                  <li><a class="dropdown-item" href="#">Your deposit is successful</a></li>
                  <!-- Add more notifications -->
              </ul>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if(auth()->user()->avatar != null)
                    @if (file_exists(auth()->user()->avatar))
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="User" class="rounded-circle" style="height: 32px; width: 32px; object-fit: cover;">
                    @else
                    <img src="{{ auth()->user()->avatar }}" alt="User" class="rounded-circle" style="height: 32px; width: 32px; object-fit: cover;">
                    @endif

                @else
                    <img src="{{ asset('default.png') }}" alt="User" class="rounded-circle" style="height: 32px; width: 32px; object-fit: cover;">
                @endif

              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                  <li class="dropdown-item-text">
                      <strong>{{ auth()->user()->name }}</strong><br>
                      {{ auth()->user()->email }}
                  </li>
                  <li>
                      <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="{{ route('user.profile.edit') }}">Account Settings</a></li>
                  <li><a class="dropdown-item" href="#">Notification Center</a></li>
                  <li><a class="dropdown-item" href="#">Support Inbox</a></li>
                  <li>
                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <a onclick="event.preventDefault();
                            this.closest('form').submit();" class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a>
                      </form>
                  </li>
              </ul>
          </li>

      </ul>
  </nav>
