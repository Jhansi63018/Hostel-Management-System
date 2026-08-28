<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT
            students.student_id,
            students.name,
            students.course,
            students.year,
            rooms.room_number,
            rooms.room_type
        FROM students
        LEFT JOIN rooms
        ON students.room_id = rooms.room_id
        ORDER BY students.student_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Room Allocation</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Student Room Allocation</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="students.php">Students</a> |
<a href="rooms.php">Rooms</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Student ID</th>
    <th>Student Name</th>
    <th>Course</th>
    <th>Year</th>
    <th>Room</th>
    <th>Room Type</th>
    <th>Action</th>
</tr>

<?php while ($student = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td>
        <?php echo $student['student_id']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($student['name']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($student['course']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($student['year']); ?>
    </td>

    <td>

        <?php

        if ($student['room_number'] != NULL) {
            echo htmlspecialchars($student['room_number']);
        } else {
            echo "Not Allocated";
        }

        ?>

    </td>

    <td>

        <?php

        if ($student['room_type'] != NULL) {
            echo htmlspecialchars($student['room_type']);
        } else {
            echo "-";
        }

        ?>

    </td>

    <td>

        <?php if ($student['room_number'] == NULL) { ?>

            <a href="allocate_room.php?id=<?php echo $student['student_id']; ?>">
                Allocate Room
            </a>

        <?php } else { ?>

            <a href="remove_room.php?id=<?php echo $student['student_id']; ?>">
                Remove Room
            </a>

        <?php } ?>

    </td>

</tr>

<?php } ?>

</table>

</body>

</html>