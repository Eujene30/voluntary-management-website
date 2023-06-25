<?php
session_start();
require('../config/db.php');

if (isset($_POST['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    mysqli_query($conn, "DELETE FROM users WHERE user_id = '$user_id'");
    header("Location: admin_users.php");
    exit();
}
?>