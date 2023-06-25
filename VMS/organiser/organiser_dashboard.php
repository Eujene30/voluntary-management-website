<?php
require('../config/db.php');
require('../config/menu.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}
  
if ($_SESSION['user_role'] !== 'organiser') {
    header("Location: ../config/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Voluntree</title>
  <link rel="stylesheet" href="../css/organiser_dashboard.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>

    <div class="sidenav">
        <a href="create_event.php">Create Event</a>
        <a href="view_event.php">View Events</a>
        <a href="notification_organiser.php">Notification</a>
        <a href="event_management.php">Event Management</a>
        <a href="volunteer_approval.php">Volunteer Approval</a>
    </div>
</body>
</html>