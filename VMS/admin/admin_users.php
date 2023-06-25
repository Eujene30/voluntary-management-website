<?php
session_start();
require('../config/db.php');
require('../config/admin_menu.php');

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/event_approval.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <h1 class="view-events">All Users</h1>
    <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Username</th>
            <th>Password</th>
            <th>Roles</th>
            <th>Actions</th> 
            <th></th>
            <th><a href="add_user.php" class="add-user-btn">Add User</a></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM users");
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>
                <a href='edit_user.php?id=" . $row['user_id'] . "'><i class='bi bi-pencil-square'></i></a>
                <form method='post' action='delete_user.php' style='display: inline;'>
                    <input type='hidden' name='user_id' value='" . $row['user_id'] . "'>
                    <button type='submit' class='delete-btn'><i class='bi bi-trash'></i></button>
                </form>
                    </td>";
                echo "</tr>";
            }
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>

</body>

  
</html>