<?php
header("Content-Type: application/json");
session_start();
include("connect.php");

// Ensure the user is logged in.
if (!isset($_SESSION['email'])) {
    echo json_encode([
        "success" => false,
        "error"   => "User not logged in."
    ]);
    exit;
}

$userEmail = $_SESSION['email'];

// Read the raw POST data.
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Validate that we have an array of activity data.
if (!isset($data['activityData']) || !is_array($data['activityData'])) {
    echo json_encode([
        "success" => false,
        "error"   => "Invalid input data."
    ]);
    exit;
}

$currentDate = date('Y-m-d H:i:s');

// Prepare an INSERT statement. Using prepared statements enhances security.
$stmt = $conn->prepare("INSERT INTO activity_log (email, activity_type, quantity, created_at) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "error"   => "Prepare statement failed: " . $conn->error
    ]);
    exit;
}

// Loop through each activity and insert it into the database.
foreach ($data['activityData'] as $activity) {
    if (isset($activity['activityType']) && isset($activity['quantity'])) {
        // Trim values for cleanliness.
        $activityType = trim($activity['activityType']);
        $quantity     = trim($activity['quantity']);
        
        // Bind parameters (using s - string; i - integer; s - string).
        // email (string), activity_type (string), quantity (int), created_at (string)
        $stmt->bind_param("ssis", $userEmail, $activityType, $quantity, $currentDate);
        
        if (!$stmt->execute()) {
            echo json_encode([
                "success" => false,
                "error"   => "Insert failed: " . $stmt->error
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "success" => false,
            "error"   => "Missing activity fields."
        ]);
        exit;
    }
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true]);
?>