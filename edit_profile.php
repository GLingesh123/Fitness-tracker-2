<?php
// Check if a session is already started before starting one.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (isset($_POST['updateProfile'])) {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName  = htmlspecialchars($_POST['lastName']);
    // You can include additional fields here if desired

    $updateQuery = "UPDATE users SET firstName='$firstName', lastName='$lastName' WHERE email='$email'";
    
    if ($conn->query($updateQuery) === TRUE) {
        echo "<script>alert('Profile Updated Successfully'); window.location.href='profile.php';</script>";
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
  <title>Edit Profile</title>
  <!-- Google Fonts -->
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
      max-width: 600px;
      background: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      margin: 50px auto;
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
      color: #333;
      margin-bottom: 8px;
      font-size: 1rem;
    }
    
    input[type="text"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
      transition: border 0.3s ease, box-shadow 0.3s ease;
      outline: none;
    }
    
    input[type="text"]:focus {
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
    <h2>Edit Profile</h2>
    <form method="POST">
      <div class="form-group">
        <label>First Name:</label>
        <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['firstName'] ?? ''); ?>" required>
      </div>
      <div class="form-group">
        <label>Last Name:</label>
        <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['lastName'] ?? ''); ?>" required>
      </div>
      <button type="submit" name="updateProfile" class="btn">Update Profile</button>
    </form>
  </div>
</body>
</html>