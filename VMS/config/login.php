<?php
require ('../config/db.php');
require ('../config/connection.php');
require ('../config/menu.php');


?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../css/login.css">
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
  
	<div class="login">
		<h1>Login</h1>
		<form action="./login.php" method="post">
			<label for="username"></label>
			<input type="text" name="username" placeholder="Username" id="username" required>
			<label for="password"></label>
			<input type="password" name="password" placeholder="Password" id="password" required>
			<input type="submit" name="login" value="Login">
      <p>Don't have an account? <a href="../config/register.php">Register</a></p>
		</form>
	</div>
</body>
</html>
