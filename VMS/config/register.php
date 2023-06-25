<?php
require ('../config/db.php');
require ('../config/connection.php');
require ('../config/menu.php');


?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration Page</title>
	<link rel="stylesheet" type="text/css" href="../css/register.css">
</head>
<body>
    <div class="container">
		<h1>Register</h1>
		<?php if (isset($_SESSION['error'])) { ?>
			<div style="background-color: red; color: white; padding: 10px;">
				<?php echo $_SESSION['error']; ?>
			</div>
		<?php } ?>
		<form action="register.php" method="post">
			<div class="form-group">
				<label for="username">Username:</label>
				<input type="text" name="username" id="username" required>
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" required>
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" required>
			</div>
			<div class="form-group">
				<label for="confirm_password">Confirm Password:</label>
				<input type="password" name="confirm_password" id="confirm_password" required>
			</div>
            <div class="form-group">
				<input type="submit" value="Register" name="register">
			</div>
			
		</form>
		<p>Already have an account? <a href="../config/login.php">Login here</a></p>
	</div>
</body>
</html>

<?php

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = ($_POST['password']); 
    $confirm_password = ($_POST['confirm_password']);

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Password and confirm password fields do not match.";
    } else {
        $query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            if ($user['username'] === $username) {
                $_SESSION['error'] = "Username already exists.";
            }

            if ($user['email'] === $email) {
                $_SESSION['error'] = "Email already exists.";
            }
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['success'] = "User registered successfully.";
                header("Location: ../config/login.php");
                exit();
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
        }
    }

    if (isset($_SESSION['error'])) {
        echo '<div style="background-color: red; color: white; padding: 10px;">';
        echo $_SESSION['error'];
        echo '</div>';
        unset($_SESSION['error']);
    }
}

?>
