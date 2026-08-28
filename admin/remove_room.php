<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: student_rooms.php");
    exit();
}

$student_id = $_GET['id'];

/* Get current room */

$sql = "SELECT room_id
        FROM students
        WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: student_rooms.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

$room_id = $student['room_id'];

if ($room_id != NULL) {

    /* Remove room from student */

    $update_student = "UPDATE students
                       SET room_id = NULL
                       WHERE student_id = '$student_id'";

    mysqli_query($conn, $update_student);

    /* Decrease occupied count */

    $update_room = "UPDATE rooms
                    SET occupied = occupied - 1
                    WHERE room_id = '$room_id'
                    AND occupied > 0";

    mysqli_query($conn, $update_room);
}

header("Location: student_rooms.php");
exit();

?>