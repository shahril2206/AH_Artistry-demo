<?php
session_start();
include("../../config/connection.php");
include("../server.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v<?php echo time(); ?>" type="text/css">
</head>
<body>
    <form action="" method="post" class="login-form">
        <h3>ADMIN LOGIN</h3>
        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="username" required>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>