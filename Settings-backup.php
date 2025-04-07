<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['firstname'])) {
    // Set a message to display after redirection
    $redirect = urlencode($_SERVER['PHP_SELF']);
    // Redirect to the login page
    header("Location: Registration.php?redirect=$redirect");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Settings</title>
    <style>
        body {
        background: radial-gradient(#1a1a1a 0%, #000000 100%);
        color: white;
        height: 100%;
        margin: 0;
        padding-top: 76px;
    }
      /* Custom styles for the navbar */
      .navbar-custom {
        background-color: #000 !important;
        border-bottom: 1px solid #ffffff;
        position: fixed; /* Change from relative to fixed */
        top: 0; /* Position at the top of the viewport */
        width: 100%; /* Make it full width */
        z-index: 1030; /* Higher z-index to ensure it stays above other content */
    }
      
      .navbar-custom .navbar-brand {
        margin-right: 2rem;
      }
      
      .navbar-custom .navbar-nav .nav-link {
        color: #fff;
        margin: 0 10px;
        transition: color 0.3s, transform 0.2s;
      }
      
      .navbar-custom .navbar-nav .nav-link:hover {
        color: #ff6b00;
        transform: translateY(0);
      }
      
      .navbar-custom .navbar-nav .nav-link.active {
        color: #ff6b00;
        font-weight: bold;
      }
      
      .navbar-custom .dropdown-menu {
        background-color: #222;
        border: none;
      }
      
      .navbar-custom .dropdown-item {
        color: #fff;
      }
      
      .navbar-custom .dropdown-item:hover {
        background-color: #333;
        color: #ff6b00;
      }
      
      .btn-sign-in {
        background-color: transparent;
        border: 1px solid #ffffff;
        color: #ffffff;
        transition: all 0.3s;
      }
      
      .btn-sign-in:hover {
        background-color: #ffffff;
        color: #000;
        transform: scale(1);
      }
      
      /* Make sure the toggler icon is visible against black background */
      .navbar-custom .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
      }
      
      .navbar-custom .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.1);
      }
      
      .center-nav {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
      }
      @media (max-width: 992px) {
        .center-nav {
          position: relative;
          left: 0;
          transform: none;
        }
      }
      /* End Custom styles for the navbar */

      /* Profile settings styles */
    .profile-container {
        display: flex;
        min-height: calc(100vh - 76px);
        margin-top: 0;
    }
    .sidebar {
    width: 250px;
    background-color: #1a1a1a;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    /*padding: 10px 0;*/
    position: sticky;
    top: 76px; /* Make sure this matches the navbar height */
    height: calc(100vh - 76px);
    overflow-y: auto;
    margin-top: 0; /* Add this to ensure no extra margin */
}
    

    .content-area {
        flex: 1;
        padding: 30px;
        overflow-y: auto;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        color: #ffffff;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar-item.active {
        background-color: rgba(255, 107, 0, 0.1);
        border-left: 3px solid #ff6b00;
    }

    .sidebar-item:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #ff6b00;
    }

    .sidebar-icon {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .section-description {
        color: #aaa;
        margin-bottom: 30px;
    }

    .form-control {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-radius: 5px;
        padding: 10px 15px;
    }

    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: #ff6b00;
        color: #ffffff;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.25);
    }

    .form-label {
        color: #aaa;
        margin-bottom: 5px;
    }

    .btn-primary {
        background-color: #ff6b00;
        border-color: #ff6b00;
        color: #ffffff;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #e06000;
        border-color: #e06000;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .form-switch .form-check-input {
        width: 45px;
        height: 22px;
    }

    .form-switch .form-check-input:checked {
        background-color: #ff6b00;
        border-color: #ff6b00;
    }

    .card {
        background-color: rgba(26, 26, 26, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .status-online {
        background-color: #28a745;
    }

    .username-display {
        font-size: 1.1rem;
        color: #aaa;
        margin-bottom: 5px;
    }

    .username-value {
        font-size: 1.4rem;
        font-weight: 600;
        color: #ffffff;
    }

    /* Ensure content sections are displayed and have proper spacing */
    .content-section {
        padding-top: 20px;
        margin-bottom: 50px;
        display: block; /* Always display sections */
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        .profile-container {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 0;
            position: sticky;
            top: 56px; /* Smaller navbar height on mobile */
            height: auto;
            z-index: 1000;
        }

        .sidebar-nav {
            display: flex;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .sidebar-item {
            border-left: none;
            border-bottom: 3px solid transparent;
            padding: 10px 15px;
            white-space: nowrap;
        }

        .sidebar-item.active {
            border-left: none;
            border-bottom: 3px solid #ff6b00;
        }
    }

    @media (max-width: 576px) {
        .content-area {
            padding: 20px 15px;
        }
    }

    /* 2FA and verification styles */
    .verification-section {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .verification-icon {
        width: 40px;
        height: 40px;
        margin-right: 20px;
    }

    .verification-info {
        flex: 1;
    }

    .verification-title {
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .verification-description {
        color: #aaa;
        font-size: 0.9rem;
    }

    /* Loading spinner */
    .spinner-border {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    .logo-container {
        text-align: center;
        margin-top: 20px;
    }

    .logo-img {
        max-width: 200px;
        opacity: 0.8;
    }
      
    </style>
  </head>
  <body>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="Homepage.php">
            <!-- Replace with your actual logo -->
            <img src="img/eye-removebg-preview.png" alt="Logo" height="40">
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav center-nav">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="Dashboard.php">Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Features.php">Features</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contacts</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Branches
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="branches/manila.html">Manila</a></li>
                    <li><a class="dropdown-item" href="branches/quezon-city.html">Quezon City</a></li>
                    <li><a class="dropdown-item" href="branches/makati.html">Makati</a></li>
                    <li><a class="dropdown-item" href="branches/pasig.html">Pasig</a></li>
                    <li><a class="dropdown-item" href="branches/alabang.html">Alabang</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="branches/all-locations.html">All Locations</a></li>
                </ul>
              </li>
            </ul>
            <?php if (isset($_SESSION['firstname'])): ?>
                <!-- Dropdown button when user is logged in -->
                <div class="ms-auto dropdown">
                  <button class="btn btn-sign-in dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['firstname']; ?>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="/KADILIMAN/register/logout.php">Log Out</a></li>
                  </ul>
                </div>
              <?php else: ?>
                <!-- Regular button when user is not logged in -->
                <div class="ms-auto">
                  <a href="Registration.php" class="btn btn-sign-in">Sign In</a>
                </div>
              <?php endif; ?>
        </div>
      </nav>
      
      <!-- Profile Container -->
    <div class="profile-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-nav">
                <a href="#personal-info" class="sidebar-item active" data-section="personal-info">
                    <img class="sidebar-icon" src="img/profile-user%20(2).png" alt="Personal Info Icon">
                    <span>Personal Information</span>
                </a>
                <a href="#security" class="sidebar-item" data-section="security">
                    <img class="sidebar-icon" src="img/secure.png" alt="Security Icon">
                    <span>Security</span>
                </a>
                <a href="#two-factor" class="sidebar-item" data-section="two-factor">
                    <img class="sidebar-icon" src="img/authentication.png" alt="2FA Icon">
                    <span>Two-Factor Authentication</span>
                </a>
                <a href="#login-management" class="sidebar-item" data-section="login-management">
                    <img class="sidebar-icon" src="img/profile.png" alt="Login Management Icon">
                    <span>Login Management</span>
                </a>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Personal Information Section -->
            <section id="personal-info" class="content-section">
                <h2 class="section-title">Personal Information</h2>
                <p class="section-description">This information is private and will not be shared with other users.</p>

                <div class="card mb-4">
                    <div class="username-display">Your username is used to log in and operate the computer</div>
                    <div class="username-value">
                        <span>Ryoshi</span>
                        <span class="status-indicator status-online"></span>
                        <span class="text-success">Online</span>
                    </div>
                </div>

                <form>
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" value="Sample@gmail.com">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" value="Diddy">
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="lastName" value="Dado">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </section>

            <!-- Security Section -->
            <section id="security" class="content-section">
                <h2 class="section-title">Security</h2>
                <p class="section-description">Manage your password and security settings to keep your account safe.</p>

                <div class="card mb-4">
                    <h5 class="mb-3">Change Password</h5>
                    <form>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <h5 class="mb-3">Security Notifications</h5>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="loginAlerts" checked>
                        <label class="form-check-label" for="loginAlerts">Receive alerts for unusual login attempts</label>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="passwordChanges" checked>
                        <label class="form-check-label" for="passwordChanges">Notify me when my password is changed</label>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="securityUpdates">
                        <label class="form-check-label" for="securityUpdates">Receive security update newsletters</label>
                    </div>
                </div>
            </section>

            <!-- Two-Factor Authentication Section -->
            <section id="two-factor" class="content-section">
                <h2 class="section-title">Two-Factor Authentication</h2>
                <p class="section-description">Protect your account from unauthorized access by requiring a secure code when signing in.</p>

                <div class="card mb-4">
                    <div class="verification-section">
                        <div class="verification-icon">
                            <img src="img/authentication.png" alt="Email Verification" style="width: 100%; height: auto;">
                        </div>
                        <div class="verification-info">
                            <h5 class="verification-title">Email Authentication</h5>
                            <p class="verification-description">We will send a verification code to your email</p>
                        </div>
                        <button class="btn btn-primary">Send</button>
                    </div>

                    <div class="mt-4" id="verification-code-form" style="display: none;">
                        <label class="form-label">Enter verification code</label>
                        <div class="d-flex gap-2 mb-3">
                            <input type="text" class="form-control text-center" maxlength="1">
                            <input type="text" class="form-control text-center" maxlength="1">
                            <input type="text" class="form-control text-center" maxlength="1">
                            <input type="text" class="form-control text-center" maxlength="1">
                            <input type="text" class="form-control text-center" maxlength="1">
                            <input type="text" class="form-control text-center" maxlength="1">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-link text-white p-0">Resend code</button>
                            <button class="btn btn-primary">Verify</button>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h5 class="mb-3">Two-Factor Methods</h5>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="emailAuth" checked>
                        <label class="form-check-label" for="emailAuth">Email Authentication</label>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="appAuth">
                        <label class="form-check-label" for="appAuth">Authenticator App</label>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="smsAuth">
                        <label class="form-check-label" for="smsAuth">SMS Authentication</label>
                    </div>
                </div>
            </section>

            <!-- Login Management Section -->
            <section id="login-management" class="content-section">
                <h2 class="section-title">Login Management</h2>
                <p class="section-description">Worried that your account or password has been compromised? You can forcibly log out from all Kadiliman branches.</p>

                <div class="card mb-4">
                    <h5 class="mb-4">Active Sessions</h5>
                    <div class="mb-3 d-flex justify-content-between align-items-center pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1)">
                        <div>
                            <div class="d-flex align-items-center">
                                <span class="status-indicator status-online"></span>
                                <strong>Makati Branch</strong>
                            </div>
                            <div class="text-muted small">Last activity: Today, 2:15 PM</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger">End Session</button>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <span class="status-indicator status-online"></span>
                                <strong>This device</strong>
                            </div>
                            <div class="text-muted small">Last activity: Just now</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger">End Session</button>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <button class="btn btn-danger btn-lg">LOG OUT EVERYWHERE</button>
                </div>

                <div class="logo-container">
                    <img src="img/eye-removebg-preview.png" class="logo-img mb-2" alt="Kadiliman Logo">
                    <h4 class="text-center text-white">KADILIMAN<span class="text-muted small"> ESPORTS CAFE</span></h4>
                </div>
            </section>
        </div>
    </div>

    <!-- Bootstrap and custom JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar item clicks for smooth scrolling and highlighting
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    
    // Add active class to sidebar item based on scroll position
    function setActiveSection() {
        const scrollPosition = window.scrollY;
        
        // Get all sections
        const sections = document.querySelectorAll('.content-section');
        
        // Find the section that is currently in view
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100; // Offset for better UX
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                // Remove active class from all sidebar items
                sidebarItems.forEach(item => item.classList.remove('active'));
                
                // Add active class to the corresponding sidebar item
                const activeItem = document.querySelector(`.sidebar-item[data-section="${sectionId}"]`);
                if (activeItem) {
                    activeItem.classList.add('active');
                }
            }
        });
    }
    
    // Handle sidebar item clicks for smooth scrolling
    sidebarItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the section ID from the data attribute
            const sectionId = this.getAttribute('data-section');
            const section = document.getElementById(sectionId);
            
            if (section) {
                // Smooth scroll to the section
                window.scrollTo({
                    top: section.offsetTop - 80, // Offset for navbar
                    behavior: 'smooth'
                });
                
                // Instead of adding to history, replace the current state
                // This way the back button will exit the page instead of going through sections
                window.history.replaceState(null, null, `#${sectionId}`);
                
                // Update active state on sidebar
                sidebarItems.forEach(sItem => sItem.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
    
    // Handle initial hash in URL
    function checkUrlHash() {
        if (window.location.hash) {
            // Get the section ID from the URL hash (remove the # symbol)
            const sectionId = window.location.hash.substring(1);
            const section = document.getElementById(sectionId);
            
            if (section) {
                // Scroll to the section after a small delay to ensure everything is loaded
                setTimeout(() => {
                    window.scrollTo({
                        top: section.offsetTop - 80, // Offset for navbar
                        behavior: 'smooth'
                    });
                    
                    // Update active state on sidebar
                    sidebarItems.forEach(item => item.classList.remove('active'));
                    const activeItem = document.querySelector(`.sidebar-item[data-section="${sectionId}"]`);
                    if (activeItem) {
                        activeItem.classList.add('active');
                    }
                }, 300);
                
                return true;
            }
        }
        
        return false;
    }
    
    // If no hash in URL, make sure the first section is active
    if (!checkUrlHash()) {
        const firstItem = document.querySelector('.sidebar-item');
        if (firstItem) {
            firstItem.classList.add('active');
        }
    }
    
    // Listen for scroll events to update active section
    window.addEventListener('scroll', setActiveSection);
    
    // Listen for hash changes (back/forward browser navigation)
    window.addEventListener('hashchange', checkUrlHash);
    
    // 2FA verification code display toggle
    const sendButton = document.querySelector('#two-factor .btn-primary');
    if (sendButton) {
        sendButton.addEventListener('click', function() {
            document.getElementById('verification-code-form').style.display = 'block';
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
            
            setTimeout(() => {
                this.innerHTML = 'Sent';
                this.disabled = false;
            }, 1500);
        });
    }
    
    // Auto-focus next input in verification code
    const codeInputs = document.querySelectorAll('#verification-code-form input');
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value.length === this.maxLength && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                codeInputs[index - 1].focus();
            }
        });
    });
});
    </script>
  </body>
</html>