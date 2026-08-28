<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Admin Dashboard</h1>

<p align="center">
    Welcome, <?php echo $_SESSION['name']; ?>!
</p>

<h3 align="center">Admin Management</h3>
    <a href="students.php">
        Student Management
    </a>
<br>
  <a href="rooms.php">Room Management</a><br>
 
    <a href="student_rooms.php">
        Student Room Allocation
    </a><br>

    <a href="maintenance.php">
        Maintenance Tickets
    </a><br>
    

    <a href="gate_passes.php">
        Gate Pass Management
    </a>
<br>


<a href="../logout.php">Logout</a>

</body>
</html>