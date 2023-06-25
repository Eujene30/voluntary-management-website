<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is "grey" role
$isGrey = ($_SESSION['user_role'] === 'grey');

// Retrieve the orders made by sales
$conn = mysqli_connect('localhost', 'root', '', 'scm');
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$query = "SELECT * FROM grey_orders WHERE status = 'Approved'";
$result = mysqli_query($conn, $query);

$notification = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notification = "New order: Order ID " . $row['id'] . " - Customer Name: " . $row['customer_name'] . " - Status: " . $row['status'];
        $notifications[] = $notification;
    }
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Grey</title>
  <link rel="stylesheet" href="notifications.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
<nav>
            <div class="logo">
                <i class="bx bx-menu menu-icon"></i>
                <span class="logo-name">Grey Dashboard</span>
            </div>
            <?php if (isset($username)) { ?>
                <div class="welcome-message right-align">Welcome <?php echo $username; ?> !</div>
            <?php } ?>
            <div class="sidebar">
                <div class="logo">
                <i class="bx bx-menu menu-icon"></i>
                <span class="logo-name">Grey Dashboard</span>
                </div>
                <div class="sidebar-content">
                <ul class="lists">
                    <li class="list">
                    <a href="grey_dashboard.php" class="nav-link">
                        <i class="bx bx-home-alt icon"></i>
                        <span class="link">Dashboard</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="#" class="nav-link">
                        <i class="bx bx-bar-chart-alt-2 icon"></i>
                        <span class="link">Revenue</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="#" class="nav-link">
                        <i class="bx bx-bell icon"></i>
                        <span class="link">Notifications</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="#" class="nav-link">
                        <i class="bx bx-message-rounded icon"></i>
                        <span class="link">Messages</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="#" class="nav-link">
                        <i class="bx bx-pie-chart-alt-2 icon"></i>
                        <span class="link">Analytics</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="grey_products.php" class="nav-link">
                        <i class="bx bx-barcode icon"></i>
                        <span class="link">Grey Products</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="grey_orders.php" class="nav-link">
                        <i class="bx bx-folder-open icon"></i>
                        <span class="link">Grey Orders</span>
                    </a>
                    </li>
                </ul>
                <div class="bottom-cotent">
                    <li class="list">
                    <a href="#" class="nav-link">
                        <i class="bx bx-cog icon"></i>
                        <span class="link">Settings</span>
                    </a>
                    </li>
                    <li class="list">
                    <a href="logout.php" class="nav-link">
                        <i class="bx bx-log-out icon"></i>
                        <form method="post" action="logout.php">
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
    <script src="navbar.js"></script>


    <h1>Notifications</h1>

        

    <label>
        <input type="checkbox" class="alertCheckbox" autocomplete="off" />
        <div class="alert success">
            <span class="alertClose">X</span>
            <div class="alertText">
                <?php
                if (!empty($notifications)) {
                    foreach ($notifications as $notification) {
                        // Split the message into ID and customer name
                        $parts = explode(" - ", $notification);
                        $id = $parts[0];
                        $customerName = $parts[1];
                        
                        // Display each message in a separate box
                        echo "<div class='notification success'>
                                <div class='notification-id'>$id</div>
                                <div class='notification-customer'>$customerName</div>
                            </div>";
                    }
                } else {
                    echo "<p>No new notifications.</p>";
                }
                ?>
                <br class="clear"/>
            </div>
        </div>
    </label>

    </body>
</html>