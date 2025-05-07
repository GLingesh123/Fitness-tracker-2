<?php
session_start();
include("connect.php");

// Retrieve the user's first name from the session (adjust as needed)
$userName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : "User";
?>
<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fit Journey</title>
  <link rel="stylesheet" href="Style.css">
  <style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: url("img/pexels-willpicturethis-1954524.jpg") no-repeat center center fixed;
    background-size: cover;
    overflow-y: auto;
}

  /* Left Sidebar */
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
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
}

/* Right Sidebar Sections */
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
/* Activity Entry Styling */
.activity-section{
  margin-top: 30px;
}
.activity-entry {
  background: rgba(255, 255, 255, 0.9);
  padding: 15px;
  margin-top: 20px;
  margin-bottom: 15px;
  border-radius: 10px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
  transition: 0.3s ease-in-out;
}

.activity-entry:hover {
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

/* Labels */
.activity-entry label {
  font-size: 1rem;
  font-weight: bold;
  color: #444;
  display: block;
  margin-bottom: 5px;
}

/* Dropdown Select Styling */
.activity-entry select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background: #fff;
  transition: border-color 0.3s ease;
}

.activity-entry select:focus {
  border-color: #8b73f7;
  outline: none;
}

/* Input Field */
.activity-entry input[type="number"] {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background: rgba(219, 233, 169, 0.2);
  transition: border-color 0.3s ease, background 0.3s ease;
}

.activity-entry input[type="number"]:focus {
  border-color: #8b73f7;
  outline: none;
  background: rgba(219, 233, 169, 0.4);
}

/* Form Buttons Layout */
.form-buttons {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  margin-top: 15px;
}

/* Beautiful Button Styling */
.form-buttons button, .add-activity-btn {
  flex: 1;
  background: linear-gradient(135deg, #67d7e5, #8b73f7);
  color: white;
  font-size: 1rem;
  font-weight: bold;
  padding: 12px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.3s ease;
  text-align: center;
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
}

/* Button Hover Effect */
.form-buttons button:hover, .add-activity-btn:hover {
  background: linear-gradient(135deg, #8b73f7, #67d7e5);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
  transform: translateY(-2px);
}

/* Individual Button Colors */
.add-activity-btn {
  background: linear-gradient(135deg, #FF9A8B, #FF6A88);
}

.add-activity-btn:hover {
  background: linear-gradient(135deg, #FF6A88, #FF3A68);
}

/* Responsive Design */
@media (max-width: 768px) {
  .form-buttons {
    flex-direction: column;
  }
}
.features {
    margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.feature-box {
    background-color:rgb(172, 157, 243);
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

<?php
session_start();
include("connect.php");

// Retrieve the user's first name from the session (adjust as needed)
$userName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fit Journey</title>
  <link rel="stylesheet" href="Style.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    /* Activity Entry Styling */
    .activity-section {
      margin-top: 30px;
    }
    .activity-entry {
      background: rgba(255, 255, 255, 0.9);
      padding: 15px;
      margin-top: 20px;
      margin-bottom: 15px;
      border-radius: 10px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
      transition: 0.3s ease-in-out;
    }
    .activity-entry:hover {
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    .activity-entry label {
      font-size: 1rem;
      font-weight: bold;
      color: #444;
      display: block;
      margin-bottom: 5px;
    }
    .activity-entry select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      background: #fff;
      transition: border-color 0.3s ease;
    }
    .activity-entry select:focus {
      border-color: #8b73f7;
      outline: none;
    }
    .activity-entry input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      background: rgba(219, 233, 169, 0.2);
      transition: border-color 0.3s ease, background 0.3s ease;
    }
    .activity-entry input[type="number"]:focus {
      border-color: #8b73f7;
      outline: none;
      background: rgba(219, 233, 169, 0.4);
    }
    .form-buttons {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      margin-top: 15px;
    }
    .form-buttons button,
    .add-activity-btn {
      flex: 1;
      background: linear-gradient(135deg, #67d7e5, #8b73f7);
      color: white;
      font-size: 1rem;
      font-weight: bold;
      padding: 12px;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s ease;
      text-align: center;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }
    .form-buttons button:hover,
    .add-activity-btn:hover {
      background: linear-gradient(135deg, #8b73f7, #67d7e5);
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
      transform: translateY(-2px);
    }
    .add-activity-btn {
      background: linear-gradient(135deg, #FF9A8B, #FF6A88);
    }
    .add-activity-btn:hover {
      background: linear-gradient(135deg, #FF6A88, #FF3A68);
    }
    @media (max-width: 768px) {
      .form-buttons {
        flex-direction: column;
      }
    }
    .features {
      margin-top: 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
    }
    .feature-box {
      background-color: rgb(172, 157, 243);
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-box h3 {
      font-size: 24px;
      margin-bottom: 15px;
      color: #1a1a2e;
    }
    .feature-box p {
      font-size: 16px;
      color: #666;
    }
    .feature-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 40px;
      }
      .hero .center-text {
        font-size: 18px;
      }
      .hero .btn {
        padding: 12px 20px;
        font-size: 16px;
      }
      .features {
        grid-template-columns: 1fr;
      }
    }
    /* Custom SweetAlert2 Styles (optional) */
    .my-swal-popup {
      border-radius: 15px;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  <!-- Overlay -->
  <div class="overlay"></div>
  <!-- Left Sidebar -->
  <nav class="sidebar-left" id="leftSidebar">
    <a href="#">Dashboard</a>
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
    <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>Your personal fitness tracker provides the following features:</p>
    <section class="about">
      <div class="features">
        <div class="feature-box">
          <h3>📊 Predict Calories</h3>
          <p>Enter your activity and let our AI predict how many calories you burn daily.</p>
        </div>
        <div class="feature-box">
          <h3>🏋️ Workout Plans</h3>
          <p>Personalized workouts based on your age and health condition.</p>
        </div>
        <div class="feature-box">
          <h3>🍎 Food Intake</h3>
          <p>Track your meals and adjust daily goals accordingly.</p>
        </div>
        <div class="feature-box">
          <h3>⏰ Daily Reminders</h3>
          <p>Receive email reminders for pending workouts to stay on track.</p>
        </div>
      </div>
    </section>
    <!-- Activity Entry Section -->
    <section class="activity-section">
      <h2>Log Your Activity</h2>
      <form id="activityForm" method="POST" action="log_activity.php">
        <div id="activityEntries">
          <!-- Activity entry rows will be appended here -->
        </div>
        <button type="button" class="add-activity-btn" id="addActivity">+ Add Activity</button>
        <div class="form-buttons">
          <button type="submit">Submit Activity</button>
          <button type="button" onclick="trackMeal()">Track My Meal</button>
          <button type="button" onclick="suggestedActivities()">Suggested Activities</button>
        </div>
      </form>
    </section>
  </main>
  <!-- Toggle Buttons -->
  <button class="toggle-btn left-toggle" id="leftToggle">☰</button>
  <button class="toggle-btn right-toggle" id="rightToggle">Progress</button>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // Activity Entry JS
    const measurementMapping = { 
      "Walking": "steps", 
      "Running": "kms", 
      "Cycling": "kms", 
      "Swimming": "mins", 
      "Gym": "mins" 
    };
    let activityCount = 0;
    function addActivityEntry() {
      const entries = document.querySelectorAll('.activity-entry');
      if (entries.length > 0) {
        const lastEntry = entries[entries.length - 1];
        const quantityInput = lastEntry.querySelector('.activity-quantity');
        if (!quantityInput || quantityInput.value.trim() === "") {
          alert("Please complete the current activity entry before adding another.");
          return;
        }
      }
      activityCount++;
      const activityDiv = document.createElement("div");
      activityDiv.className = "activity-entry";
      activityDiv.innerHTML = `
        <label for="activityType_${activityCount}">Activity Type:</label>
        <select name="activityType[]" id="activityType_${activityCount}" onchange="onActivityTypeChange(this, ${activityCount})" required>
          <option value="">Select Activity</option>
          <option value="Walking">Walking</option>
          <option value="Running">Running</option>
          <option value="Cycling">Cycling</option>
          <option value="Swimming">Swimming</option>
          <option value="Gym">Gym</option>
        </select>
        <div id="activityDetail_${activityCount}" style="display:none;">
          <label id="quantityLabel_${activityCount}" for="activityQuantity_${activityCount}"></label>
          <input type="number" name="activityQuantity[]" id="activityQuantity_${activityCount}" class="activity-quantity" min="0" required>
        </div>
      `;
      document.getElementById("activityEntries").appendChild(activityDiv);
    }
    function onActivityTypeChange(selectElem, entryId) {
      const selectedValue = selectElem.value;
      const detailDiv = document.getElementById("activityDetail_" + entryId);
      const quantityLabel = document.getElementById("quantityLabel_" + entryId);
      if (selectedValue && measurementMapping[selectedValue]) {
        quantityLabel.innerText = `Enter number of ${measurementMapping[selectedValue]}:`;
        detailDiv.style.display = "block";
      } else {
        detailDiv.style.display = "none";
      }
    }
    window.onload = function() {
      addActivityEntry();
    };
    document.getElementById("addActivity").addEventListener("click", addActivityEntry);

    // Form Submission with Confirmation using SweetAlert2
    document.getElementById("activityForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const entries = document.querySelectorAll('.activity-entry');
      let activityData = [];
      entries.forEach(entry => {
        const activityType = entry.querySelector('select[name="activityType[]"]').value;
        const quantity = entry.querySelector('.activity-quantity').value;
        if (activityType && quantity) {
          activityData.push({ activityType, quantity });
        }
      });
      const payload = { activityData: activityData };

      Swal.fire({
        title: 'Confirm Submission',
        html: '<p style="font-size: 1.1rem;">Are you sure you want to submit these activity details?</p>',
        icon: 'warning',
        background: '#f7f7f7',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'No, review again',
        customClass: {
          popup: 'my-swal-popup'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('log_activity.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              sessionStorage.setItem('activityData', JSON.stringify(activityData));
              window.location.href = "DisplayActivity.html";
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: "Error storing activity data: " + result.error,
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Submission Failed',
              text: "Error submitting activity data.",
            });
          });
        }
      });
    });
    // Dummy functions for trackMeal() and suggestedActivities()
    function trackMeal() {
      window.location.href = "track_meal.php";
    }
    function suggestedActivities() {
      window.location.href = "suggested_activities.php";
    }
  </script>
</body>
</html>