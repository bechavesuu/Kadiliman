<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['firstname'])) {
    $redirect = urlencode($_SERVER['PHP_SELF']);
    header("Location: Registration.php?redirect=$redirect");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "kadiliman"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to convert decimal hours to hours and minutes format
function formatHoursAndMinutes($decimalHours) {
    $hours = floor($decimalHours);
    $minutes = round(($decimalHours - $hours) * 60);
    
    // Handle case where minutes round up to 60
    if ($minutes == 60) {
        $hours++;
        $minutes = 0;
    }
    
    return [
        'hours' => $hours,
        'minutes' => $minutes,
        'display' => "{$hours}h {$minutes}m"
    ];
}

// Real database functions
function getUserBalance($username, $conn) {
    $sql = "SELECT standard_balance, premium_balance, conversions_used, conversion_reset_time FROM user_balance WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'standard' => (float)$row['standard_balance'],
            'standard_formatted' => formatHoursAndMinutes((float)$row['standard_balance']),
            'premium' => (float)$row['premium_balance'],
            'premium_formatted' => formatHoursAndMinutes((float)$row['premium_balance']),
            'conversion_count' => (int)$row['conversions_used'],
            'conversion_reset_time' => strtotime($row['conversion_reset_time'])
        ];
    } else {
        // User not found in balance table, create a new entry
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            
            // Set default values
            $standard_balance = 0.00;
            $premium_balance = 0.00;
            $conversions_used = 0;
            $reset_time = date('Y-m-d H:i:s', time() + 86400); // 24 hours from now
            
            // Insert new balance record
            $sql = "INSERT INTO user_balance (user_id, username, standard_balance, premium_balance, conversions_used, conversion_reset_time) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isddis", $user_id, $username, $standard_balance, $premium_balance, $conversions_used, $reset_time);
            $stmt->execute();
            
            // Add initial transaction records
            addTransaction($user_id, $username, 'purchase', $standard_balance, 0, 'Initial standard time balance', $conn);
            addTransaction($user_id, $username, 'purchase', 0, $premium_balance, 'Initial premium time balance', $conn);
            
            return [
                'standard' => $standard_balance,
                'standard_formatted' => formatHoursAndMinutes($standard_balance),
                'premium' => $premium_balance,
                'premium_formatted' => formatHoursAndMinutes($premium_balance),
                'conversion_count' => $conversions_used,
                'conversion_reset_time' => strtotime($reset_time)
            ];
        } else {
            // Fallback to default if user not found
            return [
                'standard' => 5,
                'standard_formatted' => formatHoursAndMinutes(5),
                'premium' => 2,
                'premium_formatted' => formatHoursAndMinutes(2),
                'conversion_count' => 0,
                'conversion_reset_time' => time() + 86400
            ];
        }
    }
}

function updateUserBalance($username, $standard_balance, $premium_balance, $conversion_count, $conn) {
    // Check if conversion reset time needs to be updated
    $sql = "SELECT conversion_reset_time FROM user_balance WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $reset_time = strtotime($row['conversion_reset_time']);
    
    // Reset conversion count if 24 hours have passed
    if (time() > $reset_time) {
        $conversion_count = 1; // Set to 1 because this is a new conversion
        $reset_time = time() + 86400; // 24 hours from now
        $reset_time_str = date('Y-m-d H:i:s', $reset_time);
    } else {
        $reset_time_str = $row['conversion_reset_time'];
    }
    
    // Update the balance
    $sql = "UPDATE user_balance SET standard_balance = ?, premium_balance = ?, conversions_used = ?, conversion_reset_time = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddiss", $standard_balance, $premium_balance, $conversion_count, $reset_time_str, $username);
    $result = $stmt->execute();
    
    return $result;
}

function getConversionCount($username, $conn) {
    $balance = getUserBalance($username, $conn);
    
    // Check if reset time has passed
    if (time() > $balance['conversion_reset_time']) {
        // Reset the conversion count in the database
        $sql = "UPDATE user_balance SET conversions_used = 0, conversion_reset_time = ? WHERE username = ?";
        $reset_time = date('Y-m-d H:i:s', time() + 86400);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $reset_time, $username);
        $stmt->execute();
        
        return [
            'count' => 0,
            'reset_time' => time() + 86400
        ];
    }
    
    return [
        'count' => $balance['conversion_count'],
        'reset_time' => $balance['conversion_reset_time']
    ];
}

function addTransaction($user_id, $username, $type, $standard_change, $premium_change, $description, $conn) {
    $sql = "INSERT INTO balance_transactions (user_id, username, transaction_type, standard_change, premium_change, description) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdds", $user_id, $username, $type, $standard_change, $premium_change, $description);
    return $stmt->execute();
}

function getUserId($username, $conn) {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    
    return null;
}

function getRecentTransactions($username, $limit = 5, $conn) {
    $sql = "SELECT * FROM balance_transactions WHERE username = ? ORDER BY transaction_date DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $username, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        // Format the hour changes in the transactions
        $standard_change = (float)$row['standard_change'];
        $premium_change = (float)$row['premium_change'];
        
        $row['standard_change_formatted'] = formatHoursAndMinutes(abs($standard_change));
        $row['premium_change_formatted'] = formatHoursAndMinutes(abs($premium_change));
        
        // Add sign indicators for display purposes
        $row['standard_change_sign'] = $standard_change >= 0 ? '+' : '-';
        $row['premium_change_sign'] = $premium_change >= 0 ? '+' : '-';
        
        $transactions[] = $row;
    }
    
    return $transactions;
}

// Handle form submission for converting balance
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert'])) {
    // Handle hours and minutes input
    $hours = isset($_POST['hours_to_convert']) ? (int)$_POST['hours_to_convert'] : 0;
    $minutes = isset($_POST['minutes_to_convert']) ? (int)$_POST['minutes_to_convert'] : 0;
    
    // Convert to decimal hours for calculation
    $hours_to_convert = $hours + ($minutes / 60);
    
    $conversion_type = $_POST['conversion_type'];
    $current_balance = getUserBalance($_SESSION['username'], $conn);
    
    // Check conversion limit
    $conversion_data = getConversionCount($_SESSION['username'], $conn);
    $remaining_conversions = 3 - $conversion_data['count'];
    
    if ($conversion_data['count'] >= 3) {
        $reset_time = date('H:i', $conversion_data['reset_time']);
        $message = "You've reached your daily conversion limit (3 per day). Limit resets at {$reset_time}.";
        $messageType = "danger";
    } 
    // Validate conversion amount
    elseif ($hours_to_convert <= 0) {
        $message = "Please enter a valid number of hours to convert.";
        $messageType = "danger";
    } 
    // Standard to Premium conversion
    elseif ($conversion_type === 'standard_to_premium') {
        if ($hours_to_convert > $current_balance['standard']) {
            $message = "You don't have enough standard hours to convert.";
            $messageType = "danger";
        } else {
            // Calculate how many whole 3-hour blocks can be converted
            $blocks = floor($hours_to_convert / 3);
            $hours_actually_converted = $blocks * 3;
            $premium_hours_gained = $blocks * 2;
            $standard_hours_remaining = $current_balance['standard'] - $hours_actually_converted;
            
            // Update balances
            $new_standard_balance = $standard_hours_remaining;
            $new_premium_balance = $current_balance['premium'] + $premium_hours_gained;
            $new_conversion_count = $conversion_data['count'] + 1;
            
            // Update in database
            if (updateUserBalance($_SESSION['username'], $new_standard_balance, $new_premium_balance, $new_conversion_count, $conn)) {
                // Add transaction record
                $user_id = getUserId($_SESSION['username'], $conn);
                
                // Format hours for description
                $converted_formatted = formatHoursAndMinutes($hours_actually_converted);
                $gained_formatted = formatHoursAndMinutes($premium_hours_gained);
                
                $description = "Converted {$converted_formatted['display']} standard hours to {$gained_formatted['display']} premium hours";
                addTransaction($user_id, $_SESSION['username'], 'conversion', -$hours_actually_converted, $premium_hours_gained, $description, $conn);
                
                if ($hours_actually_converted < $hours_to_convert) {
                    $unconverted = $hours_to_convert - $hours_actually_converted;
                    $unconverted_formatted = formatHoursAndMinutes($unconverted);
                    $message = "Converted {$converted_formatted['display']} standard hours to {$gained_formatted['display']} premium hours. {$unconverted_formatted['display']} couldn't be converted (need multiples of 3).";
                } else {
                    $message = "Successfully converted {$converted_formatted['display']} standard hours to {$gained_formatted['display']} premium hours.";
                }
                $messageType = "success";
                $remaining_conversions--;
            } else {
                $message = "Error updating balance. Please try again.";
                $messageType = "danger";
            }
        }
    }
    // Premium to Standard conversion
    elseif ($conversion_type === 'premium_to_standard') {
        if ($hours_to_convert > $current_balance['premium']) {
            $message = "You don't have enough premium hours to convert.";
            $messageType = "danger";
        } else {
            // Calculate how many whole 2-hour blocks can be converted
            $blocks = floor($hours_to_convert / 2);
            $hours_actually_converted = $blocks * 2;
            $standard_hours_gained = $blocks * 3;
            $premium_hours_remaining = $current_balance['premium'] - $hours_actually_converted;
            
            // Update balances
            $new_premium_balance = $premium_hours_remaining;
            $new_standard_balance = $current_balance['standard'] + $standard_hours_gained;
            $new_conversion_count = $conversion_data['count'] + 1;
            
            // Update in database
            if (updateUserBalance($_SESSION['username'], $new_standard_balance, $new_premium_balance, $new_conversion_count, $conn)) {
                // Add transaction record
                $user_id = getUserId($_SESSION['username'], $conn);
                
                // Format hours for description
                $converted_formatted = formatHoursAndMinutes($hours_actually_converted);
                $gained_formatted = formatHoursAndMinutes($standard_hours_gained);
                
                $description = "Converted {$converted_formatted['display']} premium hours to {$gained_formatted['display']} standard hours";
                addTransaction($user_id, $_SESSION['username'], 'conversion', $standard_hours_gained, -$hours_actually_converted, $description, $conn);
                
                if ($hours_actually_converted < $hours_to_convert) {
                    $unconverted = $hours_to_convert - $hours_actually_converted;
                    $unconverted_formatted = formatHoursAndMinutes($unconverted);
                    $message = "Converted {$converted_formatted['display']} premium hours to {$gained_formatted['display']} standard hours. {$unconverted_formatted['display']} couldn't be converted (need multiples of 2).";
                } else {
                    $message = "Successfully converted {$converted_formatted['display']} premium hours to {$gained_formatted['display']} standard hours.";
                }
                $messageType = "success";
                $remaining_conversions--;
            } else {
                $message = "Error updating balance. Please try again.";
                $messageType = "danger";
            }
        }
    }
}



// Get current balance for display
$current_balance = getUserBalance($_SESSION['username'], $conn);

// Get conversion count and reset time
$conversion_data = getConversionCount($_SESSION['username'], $conn);
$remaining_conversions = 3 - $conversion_data['count'];
$reset_time = date('h:i A', $conversion_data['reset_time']);

// Get recent transactions
$recent_transactions = getRecentTransactions($_SESSION['username'], 5, $conn);
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en" data-bss-forced-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Balance & Transfer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Titillium+Web:400,600,700">

<style>
   body {
        background: radial-gradient(#1a1a1a 0%, #000000 100%);
        color: white;
        height: 100%;
        margin: 0;
        padding-top: 67px;
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

    /* Page container */
    .page-container {
        width: 100%;
        max-width: 1000px;
        margin: 60px auto;
        padding: 0 15px;
    }

    /* Section styles */
    .section-title {
        font-size: 1.8rem;
        color: #ffffff;
        padding-left: 10px;
        border-left: 3px solid #ff6b00;
        margin-bottom: 25px;
    }

    /* Balance cards */
    .balance-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .balance-card {
        background-color: rgba(26, 26, 26, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .balance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 107, 0, 0.2);
        border-color: rgba(255, 107, 0, 0.3);
    }

    .balance-type {
        font-size: 1.3rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .balance-hours {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ff6b00;
        margin-bottom: 15px;
    }

    .top-up-btn {
        background-color: #000000;
        color: #ffffff;
        border: 1px solid #ff6b00;
        border-radius: 5px;
        padding: 8px 15px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .top-up-btn:hover {
        background-color: #ff6b00;
        color: #000000;
        transform: scale(1.05);
    }

    /* PC type badge */
    .pc-type-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 10px;
    }

    .standard-badge {
        background-color: #4682B4;
        color: white;
    }

    .premium-badge {
        background-color: #FFD700;
        color: black;
    }

    /* Transfer section */
    .transfer-container {
        background-color: rgba(26, 26, 26, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .conversion-info {
        background-color: rgba(255, 107, 0, 0.1);
        border-left: 3px solid #ff6b00;
        padding: 15px;
        margin-bottom: 20px;
    }

    /* Custom form elements */
    .custom-form label {
        color: #ffffff;
        margin-bottom: 8px;
    }

    .custom-form .form-control {
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
        padding: 10px 15px;
    }

    .custom-form .form-control:focus {
        border-color: #ff6b00;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.25);
    }

    .convert-btn {
        background-color: #ff6b00;
        border: none;
        color: white;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .convert-btn:hover {
        background-color: #e65c00;
        transform: translateY(-2px);
    }

    .time-block {
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .time-block-title {
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: #ff6b00;
    }

    /* Recent transactions */
    .transaction-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 0;
    }

    .transaction-item:last-child {
        border-bottom: none;
    }

    .transaction-date {
        color: #999;
        font-size: 0.9rem;
    }

    .transaction-amount {
        color: #ff6b00;
        font-weight: 600;
    }

    .transaction-type {
        font-size: 0.9rem;
        background-color: rgba(0, 0, 0, 0.2);
        padding: 3px 8px;
        border-radius: 10px;
    }

    /* Radio button styles */
    .conversion-direction {
        margin-bottom: 20px;
    }

    .conversion-direction .btn-group {
        width: 100%;
    }

    .conversion-direction .btn {
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        transition: all 0.3s ease;
    }

    .conversion-direction .btn:hover {
        background-color: rgba(255, 107, 0, 0.2);
    }

    .conversion-direction .btn.active {
        background-color: #ff6b00;
        color: #000;
    }

    .conversion-limit-info {
        background-color: rgba(70, 130, 180, 0.1);
        border-left: 3px solid #4682B4;
        padding: 15px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .balance-container {
            grid-template-columns: 1fr;
        }
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
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Dropdown button when user is logged in -->
                <div class="ms-auto dropdown">
                  <button class="btn btn-sign-in dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['username']; ?>
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
    <div class="page-container">
        <h1 class="section-title">Your Balance</h1>

        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- Balance Display -->
        <div class="balance-container">
                    <div class="balance-card">
                        <div class="balance-type">
                            Standard PC Time
                            <span class="pc-type-badge standard-badge">Standard</span>
                        </div>
                        <div class="balance-hours">
                            <?php echo $current_balance['standard_formatted']['hours']; ?> hrs 
                            <?php echo $current_balance['standard_formatted']['minutes']; ?> mins
                        </div>
                        <a href="Topup.php?pcType=standard" class="top-up-btn">Top-up</a>
                    </div>

                    <div class="balance-card">
                        <div class="balance-type">
                            Premium PC Time
                            <span class="pc-type-badge premium-badge">Premium</span>
                        </div>
                        <div class="balance-hours">
                            <?php echo $current_balance['premium_formatted']['hours']; ?> hrs 
                            <?php echo $current_balance['premium_formatted']['minutes']; ?> mins
                        </div>
                        <a href="Topup.php?pcType=premium" class="top-up-btn">Top-up</a>
                    </div>
                </div>
        
        <!-- Balance Transfer Section -->
        <h3 class="section-title">Transfer Balance</h3>
        
        <div class="transfer-container">
            <div class="conversion-info">
                <p class="mb-0"><strong>Conversion Rates:</strong></p>
                <ul class="mb-0 mt-2">
                    <li>3 hours of Standard PC time = 2 hours of Premium PC time</li>
                    <li>2 hours of Premium PC time = 3 hours of Standard PC time</li>
                </ul>
                <p class="mb-0 mt-2"><small>Note: Only multiples of 3 hours can be converted from Standard to Premium, and multiples of 2 hours from Premium to Standard. Any excess hours will remain in your original balance.</small></p>
            </div>
            
            <form class="custom-form" method="POST" action="">
                <div class="conversion-direction mb-4">
                    <label class="form-label">Conversion Direction:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="conversion_type" id="standard_to_premium" value="standard_to_premium" checked>
                        <label class="btn" for="standard_to_premium">Standard → Premium</label>
                        
                        <input type="radio" class="btn-check" name="conversion_type" id="premium_to_standard" value="premium_to_standard">
                        <label class="btn" for="premium_to_standard">Premium → Standard</label>
                    </div>
                </div>
                
                <div class="row mb-4">
                <div class="col-md-6">
                    <div class="time-block" id="from-block">
                        <div class="time-block-title">From: Standard PC</div>
                        <p>Available: <strong>
                            <?php echo $current_balance['standard_formatted']['hours']; ?> hrs 
                            <?php echo $current_balance['standard_formatted']['minutes']; ?> mins
                        </strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="time-block" id="to-block">
                        <div class="time-block-title">To: Premium PC</div>
                        <p>Current: <strong>
                            <?php echo $current_balance['premium_formatted']['hours']; ?> hrs 
                            <?php echo $current_balance['premium_formatted']['minutes']; ?> mins
                        </strong></p>
                    </div>
                </div>
            </div>
                
                <div class="mb-4">
                    <label for="hours_to_convert" class="form-label">Hours to convert:</label>
                    <input type="number" class="form-control" id="hours_to_convert" name="hours_to_convert" min="1" value="1" required>
                </div>
                
                <div class="conversion-limit-info">
                    <p class="mb-0"><strong>Daily Conversion Limit:</strong> You have <?php echo $remaining_conversions; ?> conversions remaining today</p>
                    <p class="mb-0 mt-1"><small>Limit resets at <?php echo $reset_time; ?></small></p>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
                    <button type="submit" name="convert" class="convert-btn">Convert Balance</button>
                </div>
            </form>
        </div>
        
        <!-- Recent Transactions Section -->
        <h3 class="section-title">Recent Transactions</h3>
        
        <div class="bg-dark p-4 rounded">
            <!-- This would typically be populated from the database -->
            <div class="transaction-item d-flex justify-content-between align-items-center">
                <div>
                    <div>Top-up Standard PC</div>
                    <div class="transaction-date">Apr 3, 2025</div>
                </div>
                <div class="text-end">
                    <div class="transaction-amount">+2 hours</div>
                    <span class="transaction-type">Purchase</span>
                </div>
            </div>
            
            <div class="transaction-item d-flex justify-content-between align-items-center">
                <div>
                    <div>Balance Transfer</div>
                    <div class="transaction-date">Apr 2, 2025</div>
                </div>
                <div class="text-end">
                    <div class="transaction-amount">-3 Standard / +2 Premium</div>
                    <span class="transaction-type">Conversion</span>
                </div>
            </div>
            
            <div class="transaction-item d-flex justify-content-between align-items-center">
                <div>
                    <div>Top-up Premium PC</div>
                    <div class="transaction-date">Mar 29, 2025</div>
                </div>
                <div class="text-end">
                    <div class="transaction-amount">+5 hours</div>
                    <span class="transaction-type">Purchase</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Script to update the form based on conversion direction
    document.addEventListener('DOMContentLoaded', function() {
        const standardToPremuimRadio = document.getElementById('standard_to_premium');
        const premiumToStandardRadio = document.getElementById('premium_to_standard');
        const fromBlock = document.getElementById('from-block');
        const toBlock = document.getElementById('to-block');
        const hoursInput = document.getElementById('hours_to_convert');
        
        // Initial max setting
        hoursInput.max = <?php echo $current_balance['standard']; ?>;
        
        function updateBlocks() {
            if (standardToPremuimRadio.checked) {
                fromBlock.querySelector('.time-block-title').textContent = 'From: Standard PC';
                fromBlock.querySelector('strong').textContent = '<?php echo $current_balance['standard_formatted']['hours']; ?> hrs <?php echo $current_balance['standard_formatted']['minutes']; ?> mins';
                
                toBlock.querySelector('.time-block-title').textContent = 'To: Premium PC';
                toBlock.querySelector('strong').textContent = '<?php echo $current_balance['premium_formatted']['hours']; ?> hrs <?php echo $current_balance['premium_formatted']['minutes']; ?> mins';
                
                hoursInput.max = <?php echo $current_balance['standard']; ?>;
            } else {
                fromBlock.querySelector('.time-block-title').textContent = 'From: Premium PC';
                fromBlock.querySelector('strong').textContent = '<?php echo $current_balance['premium_formatted']['hours']; ?> hrs <?php echo $current_balance['premium_formatted']['minutes']; ?> mins';
                
                toBlock.querySelector('.time-block-title').textContent = 'To: Standard PC';
                toBlock.querySelector('strong').textContent = '<?php echo $current_balance['standard_formatted']['hours']; ?> hrs <?php echo $current_balance['standard_formatted']['minutes']; ?> mins';
                
                hoursInput.max = <?php echo $current_balance['premium']; ?>;
            }
        }
        
        standardToPremuimRadio.addEventListener('change', updateBlocks);
        premiumToStandardRadio.addEventListener('change', updateBlocks);
    });
    </script>
</body>

</html>