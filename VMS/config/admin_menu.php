<?php
session_start();
require('../config/db.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}
  
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../config/error.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/admin_dashboard.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
    <nav>
        <div class="logo" >
            <i class="bx bx-menu menu-icon"></i>
            <span class="logo-name" onclick="backFunction()">Admin Dashboard</span>
        </div>
            <?php if (isset($username)) { ?>
                <div class="welcome-message right-align">Welcome <?php echo $username; ?> !</div>
            <?php } ?>
            <div class="sidebar">
                <div class="logo">
                <i class="bx bx-menu menu-icon"></i>
                <span class="logo-name">Admin Dashboard</span>
                </div>
                <div class="sidebar-content">
                <ul class="lists">
                    <li class="list">
                    <a href="../config/homepage.php" class="nav-link">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="link">Home Page</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="homepage_slideshows.php" class="nav-link">
                        <i class="bx bx-bar-chart-alt-2 icon"></i>
                        <span class="link">Slideshows</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="notification_admin.php" class="nav-link">
                        <i class="bx bx-bell icon"></i>
                        <span class="link">Notifications</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="../admin/admin_users.php" class="nav-link">
                        <i class="bx bx-user icon"></i>
                        <span class="link">Users</span>
                    </a>
                    </li>
                    <li class="list">
                        <div class="dropdown nav-link">
                            <i class="bx bx-folder-open icon"></i>
                            <span class="dropbtn">Manage Event </span>
                            <div class="dropdown-content">
                                <a href="event_approval.php">Event Approval</a>
                                <a href="view_events.php">View Events</a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="bottom-content">
                
                    <li class="list">
                    <a href="../config/logout.php" class="nav-link">
                        <i class="bx bx-log-out icon"></i>
                        <form method="post" action="../config/logout.php">
                            <input type="submit" value="Logout" style="display: none;">
                        </form>
                        <span class="link logout">Logout</span>
                    </a>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <section class="overlay"></section>
    <script src="../js/navbar.js"></script>
    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>
</body>
</html>