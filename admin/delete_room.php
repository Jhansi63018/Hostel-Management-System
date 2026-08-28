<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM rooms WHERE room_id = '$id'";

mysqli_query($conn, $sql);

header("Location: rooms.php");
exit();
?>