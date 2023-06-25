<?php
require('../config/db.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT * FROM notifications WHERE user_id = ?');
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    echo "<div class='notification'>";
    echo "<p> Notification: " . $row['message'] . " - Created at: " . $row['created_at'] . "</p>";
    echo "</div>";
}

?>