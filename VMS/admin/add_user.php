<?php
session_start();
require('../config/db.php');
require('../config/admin_menu.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}
  
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../config/error.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $role = $_POST['role'];

    $sql = "SELECT user_id FROM users WHERE email='$email' AND user_id != '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Email already exists";
        header("Location: add_user.php");
        exit();
    }

    $sql = "SELECT user_id FROM users WHERE username='$username' AND user_id != '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Username already exists";
        header("Location: add_user.php");
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists";
        header("Location: add_user.php");
        exit();
    }
    
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    mysqli_query($conn, $query);
    header("Location: admin_users.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/add_user.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <form method="POST">
        <table>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <label>Username:</label>
            <input type="text" name="username" required>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Role:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="organiser">Organiser</option>
                <option value="admin">Admin</option>
            </select>
            
            <tr>
                <td colspan="2"><input type="submit" value="Save Changes"></td>
            </tr>
        </table>
    </form>

    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>
</body>

  
</html>