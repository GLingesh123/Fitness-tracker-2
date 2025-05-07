<?php 
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$sql    = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
$user   = $result->fetch_assoc();

// Define total fields considered for profile completion
$mandatory_fields = ['age', 'current_weight', 'target_weight', 'health_condition', 'firstName', 'lastName'];
$total_fields   = count($mandatory_fields);
$filled_fields  = 0;

foreach ($mandatory_fields as $field) {
    if (!empty($user[$field])) {
        $filled_fields++;
    }
}

// Calculate profile completion percentage, ensuring it does not exceed 100%
$profile_percentage = min(round(($filled_fields / $total_fields) * 100), 100);

if (isset($_POST['updateProfile'])) {
    $age             = htmlspecialchars($_POST['age']);
    $current_weight  = htmlspecialchars($_POST['current_weight']);
    $target_weight   = htmlspecialchars($_POST['target_weight']);
    $health_condition = htmlspecialchars($_POST['health_condition']);

    $updateQuery = "UPDATE users 
                    SET age='$age', 
                        current_weight='$current_weight', 
                        target_weight='$target_weight', 
                        health_condition='$health_condition' 
                    WHERE email='$email'";

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
  <title>Profile</title>
  <!-- Google Fonts for enhanced typography -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    /* Base Styling */
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

    /* Transparent container with shadow for readability */
    .container {
      width: 90%;
      max-width: 1000px;
      background: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      margin-left: 500px;
    }

    h2, h3 {
      color: #333;
      margin-bottom: 15px;
      text-align: center;
    }

    /* Ordered display for the profile info */
    .profile-info p {
      font-size: 1.1rem;
      margin: 5px 0;
      color: #444;
      text-align: left;
    }

    .profile-progress {
      background: #e6e6e6;
      border-radius: 10px;
      height: 30px;
      width: 100%;
      overflow: hidden;
      margin: 20px 0;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(135deg, #67d7e5, #8b73f7);
      color: white;
      line-height: 30px;
      text-align: center;
      width: <?php echo $profile_percentage; ?>%;
      border-radius: 10px;
    }

    /* Form styling */
    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-size: 1rem;
      color: #333;
      margin-bottom: 5px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      outline: none;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 10px;
    }

    input:focus, select:focus {
      border-color: #8b73f7;
      box-shadow: 0 0 5px rgba(139,115,247,0.5);
    }

    button {
      width: 100%;
      padding: 10px;
      background: linear-gradient(135deg, #8b73f7, #67d7e5);
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 10px;
    }

    button:hover {
      background: linear-gradient(135deg, #67d7e5, #8b73f7);
    }

    /* Options section styling */
    .more-options {
      margin-top: 25px;
      text-align: center;
    }

    .more-options .options a {
      display: inline-block;
      padding: 10px 20px;
      margin: 5px;
      color: white;
      background: linear-gradient(135deg, #67d7e5, #8b73f7);
      border-radius: 6px;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .more-options .options a:hover {
      background: linear-gradient(135deg, #8b73f7, #67d7e5);
    }
    /* Overlay */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }
    
    /* Left Sidebar */
.sidebar-left {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100%;
  background: rgba(255, 255, 255, 0.9);
  padding: 20px;
  display: flex;
  flex-direction: column;
  box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

.sidebar-left a {
  display: block;
  padding: 15px;
  text-decoration: none;
  color: #333;
  background: rgba(139, 115, 247, 0.1);
  border-left: 4px solid #8b73f7;
  margin-bottom: 10px;
  text-align: center;
  border-radius: 5px;
  transition: 0.3s;
}

.sidebar-left a:hover {
  background: #8b73f7;
  color: white;
}

/* Right Sidebar - Split into Two Rows */
.sidebar-right {
  position: fixed;
  top: 0;
  right: 0;
  width: 250px;
  height: 100%;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  flex-direction: column;
  box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}

.sidebar-right .top, .sidebar-right .bottom {
  flex: 1;
  padding: 20px;
  border-bottom: 2px solid #ccc;
  text-align: center;
}

.sidebar-right .bottom {
  border-bottom: none;
}

/* Main Content - Centered & Transparent */
main.main-content {
  margin: 0 auto;
  width: 60%;
  padding: 20px;
  background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  position: relative;
  z-index: 10;
}

/* Activity Section */
.activity-section {
  background: rgba(219, 233, 169, 0.9);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  text-align: left;
}

/* Buttons */
.toggle-btn {
  position: fixed;
  top: 20px;
  background: #8b73f7;
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
  z-index: 1000;
}

.left-toggle { left: 20px; }
.right-toggle { right: 20px; }
  </style>
</head>
<body>
   <!-- Left Sidebar -->
   <nav class="sidebar-left" id="leftSidebar">
    <a href="UserDashboard.php">Dashboard</a>
    <a href="profile.php">Profile</a>
    <a href="dailyreport.php">Daily Report</a>
    <a href="logout.php">Log out</a>
  </nav>

  
  <div class="overlay"></div>
  <div class="container">
    <!-- Profile Information Section -->
    <section class="profile-info">
      <h2>Your Profile</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName']) . " " . htmlspecialchars($user['lastName']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Profile Completion:</strong> <?php echo $profile_percentage; ?>%</p>
      <div class="profile-progress">
        <div class="progress-bar"><?php echo $profile_percentage; ?>%</div>
      </div>
    </section>

    <!-- Update Profile Section -->
    <section class="update-profile">
      <h3>Update Profile</h3>
      <form method="POST">
        <div class="form-group">
          <label>Age:</label>
          <input type="number" name="age" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Current Weight (kg):</label>
          <input type="number" step="0.1" name="current_weight" value="<?php echo htmlspecialchars($user['current_weight'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Target Weight (kg):</label>
          <input type="number" step="0.1" name="target_weight" value="<?php echo htmlspecialchars($user['target_weight'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
          <label>Health Condition:</label>
          <select name="health_condition" required>
            <option value="" disabled <?php echo empty($user['health_condition']) ? 'selected' : ''; ?>>Select Your Condition</option>
            <option value="Healthy" <?php if ($user['health_condition'] == 'Healthy') echo 'selected'; ?>>Healthy</option>
            <option value="Overweight" <?php if ($user['health_condition'] == 'Overweight') echo 'selected'; ?>>Overweight</option>
            <option value="Underweight" <?php if ($user['health_condition'] == 'Underweight') echo 'selected'; ?>>Underweight</option>
            <option value="Diabetic" <?php if ($user['health_condition'] == 'Diabetic') echo 'selected'; ?>>Diabetic</option>
            <option value="Heart Issues" <?php if ($user['health_condition'] == 'Heart Issues') echo 'selected'; ?>>Heart Issues</option>
          </select>
        </div>
        <button type="submit" name="updateProfile">Update Profile</button>
      </form>
    </section>

    <!-- More Options Section -->
    <section class="more-options">
      <h3>More Options</h3>
      <div class="options">
        <a href="edit_profile.php">Edit Profile</a>
        <a href="change_password.php">Change Password</a>
      </div>
    </section>
  </div>
  
</body>
</html>