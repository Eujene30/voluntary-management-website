<?php
session_start();
require('../config/db.php');
require('../config/admin_menu.php');

$role = 'organiser';


$stmt = $conn->prepare('SELECT n.created_at, e.eventName, u.username FROM notifications n 
                        INNER JOIN events e ON n.event_id = e.event_id
                        INNER JOIN users u ON n.organiserID = u.user_id 
                        WHERE n.role = ?
                        ORDER BY n.created_at DESC');
$stmt->bind_param('s', $role);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/admin_users.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
        <h1 class="notifications">Notifications</h1>
        <div class="notification-container">
            <?php 
            if($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Notification</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<div class='notification'>";
                    echo "<p> Notification: - <b><i>" . $row['username'] . "</i></b> has submitted for an event to review <b><i>" . $row['eventName'] . "</i></b>  on <i>" . $row['created_at'] . "</i></p>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<p>No notifications found.</p>";
            }

            $stmt->close();
            $result->close();
            ?>
        </div>
 

    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>

</body>
</html>