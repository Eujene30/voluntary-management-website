<?php
session_start();
require ('db.php');


$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : "";

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>
    <nav>
    <div class="menu">
      <div class="logo">
        <a href="../homepage.php">Voluntree</a>
      </div>
      <ul>
        <li><a href="../config/homepage.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="../user/all_events.php">Events</a></li>
        <li><a href="../user/feedbacks.php">Feedback</a></li>
        <?php if (!isset($_SESSION['user_id'])) { ?>
          <li><a href="../config/login.php">Login</a></li>
          <li><a href="../config/register.php">Register</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') { ?>
          <li><a href="../user/user_notification.php">Notification</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'organiser') { ?>
          <li><a href="../organiser/organiser_dashboard.php">Manage Event</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') { ?>
          <li><a href="../admin/admin_dashboard.php">Dashboard</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
          <li class="welcome-message">
            <a href="#">
              <i class="bx bx-user icon"></i>
              <?php echo $username; ?>
            </a>
          </li>
          <li>
            <a href="../config/logout.php">
              <form method="post" action="../config/logout.php">
                <input type="submit" value="logout" style="display: none;">
              </form>
              <span class="logout">Logout</span>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </nav>


  <script src="../js/dropdown-menu.js"></script>



     
   
</body>
</html>