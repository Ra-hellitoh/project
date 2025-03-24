<nav id="nav-bar" class="navbar navbar-expand-lg px-lg-3 py-lg-2 shadow-sm sticky-top" 
     style="background-color: #054473;">
  <div class="container-fluid">
    <a class="navbar-brand me-5 fw-bold fs-3 h-font d-flex align-items-center text-white" href="index.php">
      <img src="images/about/ku.png" alt="KU Logo" style="height: 40px; width: auto; margin-right: 10px;">
      <?php echo $settings_r['site_title'] ?>
    </a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link me-2 text-white" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2 text-white" href="rooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2 text-white" href="facilities.php">Facilities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link me-2 text-white" href="contact.php">Contact us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="about.php">About</a>
        </li>
      </ul>
      <div class="d-flex">
        <?php 
          if(isset($_SESSION['login']) && $_SESSION['login']==true)
          {
            $path = USERS_IMG_PATH;
            echo<<<data
              <div class="btn-group">
                <button type="button" class="btn btn-outline-light shadow-none dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                  <img src="$path$_SESSION[uPic]" style="width: 25px; height: 25px;" class="me-1 rounded-circle">
                  $_SESSION[uName]
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end">
                  <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                  <li><a class="dropdown-item" href="bookings.php">Bookings</a></li>
                  <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
              </div>
            data;
          }
          else
          {
            echo<<<data
              <button type="button" class="btn btn-outline-light shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                Login
              </button>
              <button type="button" class="btn btn-outline-light shadow-none me-lg-3 me-2" data-bs-toggle="modal" data-bs-target="#registerModal">
                Register
              </button>
              <a href="/admin/" class="btn btn-warning shadow-none">Admin Login</a>
            data;
          }
        ?>
      </div>
    </div>
  </div>
</nav>



<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="login-form">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-circle fs-3 me-2"></i> User Login
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Email / Mobile</label>
            <input type="text" name="email_mob" required class="form-control shadow-none">
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="pass" required class="form-control shadow-none">
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
              Forgot Password?
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <form id="register-form">
    <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-lines-fill fs-3 me-2"></i> User Registration
        </h5>
        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="add_student.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Admission Number:</label>
                <input type="text" name="pincode" class="form-control" 
                       pattern="[A-Za-z0-9\/]+" 
                       title="Enter a valid admission number (e.g., e35/0475/3030)" 
                       required>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" class="form-control" 
                       pattern="[0-9]{10}" 
                       title="Enter a valid 10-digit phone number" 
                       required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>
</form>

    </div>
  </div>
</div>

<div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="forgot-form">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-circle fs-3 me-2"></i> Forgot Password
          </h5>
        </div>
        <div class="modal-body">
          <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
            Note: A link will be sent to your email to reset your password!
          </span>
          <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" required class="form-control shadow-none">
          </div>
          <div class="mb-2 text-end">
            <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              CANCEL
            </button>
            <button type="submit" class="btn btn-dark shadow-none">SEND LINK</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>