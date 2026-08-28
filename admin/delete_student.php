<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$student_id = $_GET['id'];

/* Get student's user_id and room_id */

$sql = "SELECT user_id, room_id
        FROM students
        WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: students.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

$user_id = $student['user_id'];
$room_id = $student['room_id'];

/* If student has a room, reduce occupancy */

if ($room_id != NULL) {

    $room_sql = "UPDATE rooms
                 SET occupied = occupied - 1
                 WHERE room_id = '$room_id'
                 AND occupied > 0";

    mysqli_query($conn, $room_sql);
}

/* Delete student */

$delete_student = "DELETE FROM students
                   WHERE student_id = '$student_id'";

mysqli_query($conn, $delete_student);

/* Delete login account */

$delete_user = "DELETE FROM users
                WHERE user_id = '$user_id'
                AND role = 'student'";

mysqli_query($conn, $delete_user);

header("Location: students.php");
exit();

?>