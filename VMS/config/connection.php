<?php
session_start();
require ('../config/db.php');


if (isset($_SESSION['user_id'])) {
  if ($_SESSION['user_role'] === 'user') {
    header("Location: ./homepage.php");
  } else if ($_SESSION['user_role'] === 'organiser') {
    header("Location: ./homepage.php");
  } else if ($_SESSION['user_role'] === 'admin') {
    header("Location: ./admin/admin_dashboard.php");
  }
  exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_role'] = $user['role']; 

        switch ($user['role']) {
            case 'user':
                $_SESSION['message'] = "You are now logged in as a sales user";
                header("Location: ./homepage.php");
                break;
            case 'organiser':
                $_SESSION['message'] = "You are now logged in as a grey user";
                header("Location: ./homepage.php");
                break;
			case 'admin':
				$_SESSION['message'] = "You are now logged in as an admin user";
				header("Location: ../admin/admin_dashboard.php");
				break;	
            default:
                $_SESSION['message'] = "Unknown user role";
                header("Location: ../config/error.php");
                break;
        }
        exit();
    } else {
        $_SESSION['message'] = "Username/password combination incorrect";
    }
}


?>