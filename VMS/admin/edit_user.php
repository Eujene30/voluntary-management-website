<?php
session_start();
require('../config/db.php');
require('../config/admin_menu.php');


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

if (!isset($_GET['id'])) {
    echo "User ID parameter missing";
    exit;
}

$user_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT user_id FROM users WHERE email='$email' AND user_id != '$user_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Email already exists";
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }

    $sql = "SELECT user_id FROM users WHERE username='$username' AND user_id != '$user_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Username already exists";
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already exists";
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }

    $sql = "UPDATE users SET username='$username', email='$email', password='$password', role='$role' WHERE user_id='$user_id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "User updated successfully";
        header("Location: admin_users.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating user: " . mysqli_error($conn);
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }
    mysqli_close($conn);
} else {
    $sql = "SELECT * FROM users WHERE user_id='$user_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo "User not found";
        exit;
    }

    $user = mysqli_fetch_assoc($result);

    mysqli_close($conn);
?>


<?php
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/edit_user.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <form class="edit-user" method="POST">
        <table>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <tr>
                <td><label>Username:</label></td>
                <td><input type="text" name="username" value="<?php echo $user['username']; ?>"></td>
            </tr>
            <tr>
                <td><label>Email:</label></td>
                <td><input type="email" name="email" value="<?php echo $user['email']; ?>"></td>
            </tr>
            <tr>
                <td><label>Password:</label></td>
                <td><input type="password" name="password" value="<?php echo $user['password']; ?>"></td>
            </tr>
            <tr>
                <td><label>Role:</label></td>
                <td>
                    <select name="role">
                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                        <option value="organiser" <?php if ($user['role'] == 'organiser') echo 'selected'; ?>>Organiser</option>
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </td>
            </tr>
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