<?php
// Check if session is already active before starting one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email  = $_SESSION['email'];
$sql    = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user   = $result->fetch_assoc();

if (isset($_POST['changePassword'])) {
    $current_password     = $_POST['current_password'];
    $new_password         = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Check if the current password matches the stored password (use password_verify)
    if (!password_verify($current_password, $user['password'])) {
        echo "<script>alert('Current password is incorrect'); window.location.href='change_password.php';</script>";
        exit();
    }

    // Check if the new passwords match
    if ($new_password !== $confirm_new_password) {
        echo "<script>alert('New passwords do not match'); window.location.href='change_password.php';</script>";
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database
    $updateQuery = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
    if ($conn->query($updateQuery) === TRUE) {
        echo "<script>alert('Password Changed Successfully'); window.location.href='profile.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
  <!-- Google Fonts for enhanced typography -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* Base styling and background */
    body {
      font-family: 'Roboto', sans-serif;
      background-image: url("img/pexels-willpicturethis-1954524.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      margin: 0;
      padding: 0;
    }
    
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }
    
    /* Transparent container styling */
    .container {
      width: 90%;
      max-width: 500px;
      background: rgba(255, 255, 255, 0.8);
      margin: 50px auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
    
    /* Form styling */
    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }
    
    label {
      display: block;
      font-size: 1rem;
      color: #333;
      margin-bottom: 8px;
    }
    
    input[type="password"] {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border 0.3s ease, box-shadow 0.3s ease;
      outline: none;
      margin-bottom: 10px;
    }
    
    input[type="password"]:focus {
      border-color: #8b73f7;
      box-shadow: 0 0 5px rgba(139,115,247,0.5);
    }
    
    button[type="submit"] {
      width: 100%;
      padding: 10px;
      background: linear-gradient(135deg, #8b73f7, #67d7e5);
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 1.1rem;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 10px;
    }
    
    button[type="submit"]:hover {
      background: linear-gradient(135deg, #67d7e5, #8b73f7);
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="container">
    <h2>Change Password</h2>
    <form method="POST">
      <div class="form-group">
        <label>Current Password:</label>
        <input type="password" name="current_password" required>
      </div>
      <div class="form-group">
        <label>New Password:</label>
        <input type="password" name="new_password" required>
      </div>
      <div class="form-group">
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_new_password" required>
      </div>
      <button type="submit" name="changePassword">Change Password</button>
    </form>
  </div>
</body>
</html>