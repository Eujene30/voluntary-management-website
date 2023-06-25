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

$role = 'user';
$organiserID = $_SESSION['user_id'];

$stmt_admin = $conn->prepare('SELECT n.created_at, e.eventName, u.username, e.approvalStatus FROM notifications n 
                        INNER JOIN events e ON n.event_id = e.event_id
                        INNER JOIN users u ON n.user_id = u.user_id 
                        WHERE n.role = "admin" AND n.organiserID = ? 
                        ORDER BY n.created_at DESC');
$stmt_admin->bind_param('i', $organiserID);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

$stmt_user = $conn->prepare('SELECT n.created_at, e.eventName, u.username FROM notifications n 
                        INNER JOIN events e ON n.event_id = e.event_id
                        INNER JOIN users u ON n.user_id = u.user_id 
                        WHERE n.role = "user" AND n.organiserID = ? 
                        ORDER BY n.created_at DESC');
$stmt_user->bind_param('i', $organiserID);
$stmt_user->execute();
$result_user = $stmt_user->get_result();


?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/view_event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>

    <div class="container">
        <div class="sidebar"></div>

        <div class="events">
            <h1 class="notifications">Notifications</h1>
            <div class="notification-container">
                <?php 
                    if($result_admin->num_rows > 0) {
                        while($row = $result_admin->fetch_assoc()) {
                            echo "<div class='notification'>";
                            echo "<p> Notification: - <b><i>" . $row['username'] . "</i></b> has " .$row['approvalStatus']. " for event <b><i>" . $row['eventName'] . "</i></b> on <i>" . $row['created_at'] . "</i></p>";
                            
                            if(!empty($row['message'])){
                                echo "<p><b>Admin: </b> " .$row['message']. "</p>";
                            }
                            
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No notifications found.</p>";
                    }

               
                    if($result_user->num_rows > 0) {
                        while($row = $result_user->fetch_assoc()) {
                            echo "<div class='notification'>";
                            echo "<p> Notification: - <b><i>" . $row['username'] . "</i></b> has registered for event <b><i>" . $row['eventName'] . "</i></b> on <i>" . $row['created_at'] . "</i></p>";
                            
                            if(!empty($row['message'])){
                                echo "<p><b>Admin: </b> " .$row['message']. "</p>";
                            }
                            
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No notifications found.</p>";
                    }

                   
                ?>

                <?php
                    $stmt_admin->close();
                    $result_admin->close();
                    $stmt_user->close();
                    $result_user->close();

                ?>
            </div>
        </div>
       
    </div>
</body>
</html>