<?php
session_start();
include "config/database.php";

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users 
            WHERE username = '$username' 
            AND password = '$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        }

        if ($user['role'] == 'student') {
            header("Location: student/dashboard.php");
            exit();
        }

    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Hostel & Mess Logistics System</title>
    <link rel="stylesheet" href="css/style.css">
  <style>
  input {
    width:100%;
}
button {
    width: auto;
 
}
</style>
</head>

<body align="center">

<div class="login-container">

    <h1>Hostel Management System</h1>

    <h2>Login</h2>

    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login"  >Login</button>

    </form>

</div>

</body>
</html>