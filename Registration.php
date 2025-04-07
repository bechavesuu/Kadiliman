<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Login / Sign Up</title>
    <style>
    body {
        background: radial-gradient(#1a1a1a 0%, #000000 100%);
        color: white;
        height: 100%;
        margin: 0;
        padding-top: 76px;
    }
    .navbar-custom {
        background-color: #000 !important;
        border-bottom: 1px solid #ffffff;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
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
    
    .login-container {
        max-width: 800px;
        margin: 100px auto;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 5px;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: hidden;
    }

    .tab-selector {
        display: flex;
        width: 100%;
        margin-bottom: 20px;
    }

    .tab-btn {
        flex: 1;
        padding: 15px 0;
        text-align: center;
        background-color: #333;
        color: white;
        border: none;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .tab-btn.active {
        background-color: #555;
    }

    .login-form-container {
        display: flex;
        width: 100%;
    }

    .form-section {
        flex: 1;
        padding: 10px 30px 40px 30px; /* Top, Right, Bottom, Left */
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-group {
        width: 100%;
        max-width: 300px;
        margin-bottom: 15px;
    }

    .name-row {
        display: flex;
        gap: 15px;
        width: 100%;
        max-width: 300px;
    }

    .name-field {
        flex: 1;
    }

        .cafe-image {
        flex: 1;
        background-image: url('img/LOGO-removebg-preview.png');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        margin: 20px;  /* Add margin around all sides */
        border-radius: 5px;  /* Optional: adds rounded corners */
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        background-color: #333;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        color: white;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .submit-btn {
        background-color: #444;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 12px 25px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
        width: 100%;
        max-width: 300px;
    }

    .submit-btn:hover {
        background-color: #666;
    }

    .help-text {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        width: 100%;
        max-width: 300px;
    }

    .help-text a {
        color: #999;
        text-decoration: none;
    }

    .help-text a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-form-container {
            flex-direction: column;
        }
        
        .cafe-image {
            min-height: 200px;
        }
        
        .name-row {
            flex-direction: column;
            gap: 15px;
        }
    }
    </style>
  </head>
  <body>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="Homepage.php">
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
          </div>
        </div>
      </nav>
      
      <div class="container">
        <div class="login-container">
            <div class="tab-selector">
                <button class="tab-btn active" id="signin-tab" onclick="switchTab('signin')">Sign-in</button>
                <button class="tab-btn" id="signup-tab" onclick="switchTab('signup')">Sign-Up</button>
            </div>
            
            <!-- Sign In Form -->
            <form action="/KADILIMAN/register/login.php" method="POST">
                <div class="login-form-container" id="signin-container">
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">Username:</label>
                            <input type="text" class="form-control" id="signin-username" name="signin-username">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password:</label>
                            <input type="password" class="form-control" id="signin-password" name="signin-password">
                        </div>
                        <button class="submit-btn" type="submit">Sign-in</button>
                        <div class="help-text">
                            <a href="#">Need Help?</a>
                        </div>
                    </div>
                    <div class="cafe-image" style="background-image: url('img/LOGO-removebg-preview.png')"></div>
                </div>
            </form>
            
            <!-- Sign Up Form -->
            <form action="/KADILIMAN/register/registerUser.php" method="POST">
                <div class="login-form-container" id="signup-container" style="display: none;">
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">Username:</label>
                            <input type="text" class="form-control" id="signup-username" name="signup-username">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email:</label>
                            <input type="email" class="form-control" id="signup-email" name="signup-email">
                        </div>
                        <div class="name-row">
                            <div class="name-field">
                                <label class="form-label">First Name:</label>
                                <input type="text" class="form-control" id="signup-firstname" name="signup-firstname">
                            </div>
                            <div class="name-field">
                                <label class="form-label">Surname:</label>
                                <input type="text" class="form-control" id="signup-surname" name="signup-surname">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Branch:</label>
                            <input type="text" class="form-control" id="signup-branch" name="signup-branch">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password:</label>
                            <input type="password" class="form-control" id="signup-password" name="signup-password" oninput="updatePasswordFeedback()">
                            <div id="password-feedback" style="margin-top: 10px;"></div>
                        </div>
                        <button class="submit-btn" type="submit">Sign-Up</button>
                    </div>
                </div>
            </form>
        </div>
      </div>

      <!-- Registration Success Modal -->
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content" style="background-color: #333; color: white;">
                  <div class="modal-header">
                      <h5 class="modal-title" id="successModalLabel">Successful</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;"></button>
                  </div>
                  <div class="modal-body">
Thank you!                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>

    <!-- JavaScript for tab switching -->
    <script>
    function switchTab(tabName) {
        // Hide all containers
        document.getElementById('signin-container').style.display = 'none';
        document.getElementById('signup-container').style.display = 'none';
        
        // Show selected container
        document.getElementById(tabName + '-container').style.display = 'flex';
        
        // Update active tab button
        document.getElementById('signin-tab').classList.remove('active');
        document.getElementById('signup-tab').classList.remove('active');
        document.getElementById(tabName + '-tab').classList.add('active');
    }

    function updatePasswordFeedback() {
        const password = document.getElementById('signup-password').value;
        const feedback = validatePassword(password);
        document.getElementById('password-feedback').innerHTML = feedback;
    }

    function validateFormFields() {
        const requiredFields = [
            'signup-username', 'signup-email', 'signup-firstname', 'signup-surname', 'signup-branch', 'signup-password'
        ];
        let isValid = true;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.style.color = 'red';
            errorElement.style.fontSize = '12px';
            errorElement.textContent = 'This is required';

            // Remove existing error message
            if (field.nextElementSibling && field.nextElementSibling.className === 'error-message') {
                field.nextElementSibling.remove();
            }

            if (!field.value.trim()) {
                field.parentNode.appendChild(errorElement);
                isValid = false;
            }
        });

        return isValid;
    }

    document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    
    // Store the current page URL before login
    let returnTo = '';
    
    // First priority: Check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const paramRedirect = urlParams.get('redirect');
    
    // Second priority: Use document.referrer (where user came from)
    const referrer = document.referrer;
    const referrerPage = referrer ? referrer.split('/').pop() : '';
    
    // Third priority: Use localStorage if we stored the page earlier
    const storedPage = localStorage.getItem('lastVisitedPage');
    
    // Check if user is coming from homepage
    const isFromHomepage = referrerPage === '' || referrerPage === 'Homepage.php' || referrerPage === 'Homepage.php' || referrer.endsWith('/');
    
    // Determine which page to redirect to after login
    if (isFromHomepage) {
        // If coming from homepage, always redirect to Dashboard
        returnTo = 'Dashboard.php';
    } else if (paramRedirect) {
        // Priority 1: Use explicit redirect parameter
        returnTo = paramRedirect;
    } else if (referrerPage && referrerPage !== 'login.php') {
        // Priority 2: Use referrer if it's not the login page itself
        returnTo = referrerPage;
    } else if (storedPage) {
        // Priority 3: Use previously stored page
        returnTo = storedPage;
    } else {
        // Default fallback
        returnTo = 'Dashboard.php';
    }
    
    // Save return URL in form data to send to the server
    formData.append('returnTo', returnTo);

    fetch('/KADILIMAN/register/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Clear the stored page since we're about to redirect
            localStorage.removeItem('lastVisitedPage');
            
            setTimeout(() => {
                // Use the server's redirect suggestion if available, otherwise use our calculated returnTo
                window.location.href = data.redirectUrl || returnTo;
            }, 2000);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
});

// Add this script to all your pages to track user location
//document.addEventListener('DOMContentLoaded', function() {
    // Don't store login page as return destination
    //if (!window.location.pathname.includes('login.php')) {
        //localStorage.setItem('lastVisitedPage', window.location.pathname.split('/').pop() || 'Dashboard.php');
   // }
//});
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="register/passwordValidation.js"></script>
  </body>
</html>