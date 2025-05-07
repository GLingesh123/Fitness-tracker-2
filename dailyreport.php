<?php
// dailyreport.php

session_start();
include("connect.php");

// Ensure the user is logged in.
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION['email'];

/*
  This query aggregates (sums) all records in activity_log for a given day.
  For each day, it sums the quantity for each activity type. If two or more
  records are available (e.g., the user logged Running twice), their quantities
  will be added together.
*/
$query = "
SELECT a.date, 
       a.running, 
       a.walking, 
       a.swimming, 
       a.cycling, 
       a.gym, 
       d.tot_calories
FROM (
    SELECT 
        DATE(created_at) AS date,
        SUM(CASE WHEN activity_type = 'Running' THEN quantity ELSE 0 END) AS running,
        SUM(CASE WHEN activity_type = 'Walking' THEN quantity ELSE 0 END) AS walking,
        SUM(CASE WHEN activity_type = 'Swimming' THEN quantity ELSE 0 END) AS swimming,
        SUM(CASE WHEN activity_type = 'Cycling' THEN quantity ELSE 0 END) AS cycling,
        SUM(CASE WHEN activity_type = 'Gym' THEN quantity ELSE 0 END) AS gym
    FROM activity_log
    WHERE email = ?
    GROUP BY DATE(created_at)
) AS a
LEFT JOIN (
    SELECT date, tot_calories 
    FROM daily_calories 
    WHERE email = ?
) AS d 
ON a.date = d.date
ORDER BY a.date DESC
";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ss", $userEmail, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $reportData = [];
    while ($row = $result->fetch_assoc()) {
        $reportData[] = $row;
    }
    $stmt->close();
} else {
    die("Prepare failed: " . $conn->error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daily Report</title>
  <link rel="stylesheet" href="Style.css">
  <style>
    /* Global Styling */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: url("img/pexels-willpicturethis-1954524.jpg") no-repeat center center fixed;
      background-size: cover;
      overflow-y: auto;
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
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
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
    /* Right Sidebar */
    .sidebar-right {
      position: fixed;
      top: 0;
      right: 0;
      width: 250px;
      height: 100%;
      background: rgba(255, 255, 255, 0.9);
      display: flex;
      flex-direction: column;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar-right .top,
    .sidebar-right .bottom {
      flex: 1;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-weight: bold;
      font-size: 1.2rem;
      background: rgba(139, 115, 247, 0.1);
      margin: 10px;
      border-radius: 8px;
      transition: 0.3s;
    }
    .sidebar-right .top:hover,
    .sidebar-right .bottom:hover {
      background: #8b73f7;
      color: white;
    }
    /* Main Content - Positioned between sidebars */
    .main-content {
      margin: 20px auto;
      width: calc(100% - 540px);
      max-width: 900px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      border-radius: 12px;
      overflow-y: auto;
    }
    @media (max-width: 1024px) {
      .main-content {
        width: 90%;
      }
      .sidebar-left, .sidebar-right {
        width: 200px;
      }
    }
    @media (max-width: 768px) {
      .sidebar-left, .sidebar-right {
        position: relative;
        width: 100%;
        height: auto;
      }
      .main-content {
        width: 100%;
        margin: 10px;
      }
    }
    /* Table Styling */
    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }
    table, th, td {
      border: 1px solid #ddd;
    }
    th, td {
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    caption {
      font-size: 1.5em;
      margin-bottom: 10px;
    }
    /* Toggle Buttons */
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

  <!-- Right Sidebar -->
  <div class="sidebar-right" id="rightSidebar">
    <div class="top">
      <p><strong>Progress</strong></p>
    </div>
    <div class="bottom">
      <p><strong>Daily Tasks</strong></p>
    </div>
  </div>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1>Daily Report</h1>
    <table>
      <caption>Activity Data and Total Calories Burned</caption>
      <thead>
        <tr>
          <th>Date</th>
          <th>Running</th>
          <th>Walking</th>
          <th>Swimming</th>
          <th>Cycling</th>
          <th>Gym</th>
          <th>Tot Calories Burned</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($reportData) > 0): ?>
          <?php foreach ($reportData as $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['date']); ?></td>
              <td><?php echo htmlspecialchars($row['running']); ?></td>
              <td><?php echo htmlspecialchars($row['walking']); ?></td>
              <td><?php echo htmlspecialchars($row['swimming']); ?></td>
              <td><?php echo htmlspecialchars($row['cycling']); ?></td>
              <td><?php echo htmlspecialchars($row['gym']); ?></td>
              <td><?php echo ($row['tot_calories'] !== null) ? htmlspecialchars($row['tot_calories']) : "N/A"; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>

  <!-- Toggle Buttons -->
  <button class="toggle-btn left-toggle" id="leftToggle">☰</button>
  <button class="toggle-btn right-toggle" id="rightToggle">Progress</button>

  <script>
    // Sidebar toggle functions
    const leftSidebarEl = document.getElementById("leftSidebar");
    const rightSidebarEl = document.getElementById("rightSidebar");
    const leftToggleEl = document.getElementById("leftToggle");
    const rightToggleEl = document.getElementById("rightToggle");

    function toggleSidebar(sidebar, button, isLeft) {
      if (sidebar.style[isLeft ? 'left' : 'right'] === "0px") {
        sidebar.style[isLeft ? 'left' : 'right'] = "-250px";
        button.innerHTML = isLeft ? "☰" : "Progress";
      } else {
        sidebar.style[isLeft ? 'left' : 'right'] = "0px";
        button.innerHTML = "✖";
      }
      // Close the other sidebar if open
      if (isLeft) {
        rightSidebarEl.style.right = "-250px";
        rightToggleEl.innerHTML = "Progress";
      } else {
        leftSidebarEl.style.left = "-250px";
        leftToggleEl.innerHTML = "☰";
      }
    }

    leftToggleEl.addEventListener("click", () => toggleSidebar(leftSidebarEl, leftToggleEl, true));
    rightToggleEl.addEventListener("click", () => toggleSidebar(rightSidebarEl, rightToggleEl, false));
  </script>
</body>
</html>