<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT 
            students.*,
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
    <title>Student Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Student Management</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="add_student.php">Add Student</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Course</th>
    <th>Year</th>
    <th>Mobile</th>
    <th>Email</th>
    <th>Room</th>
    <th>Room Type</th>
    <th>Action</th>
</tr>

<?php while ($student = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?php echo $student['student_id']; ?></td>

    <td><?php echo $student['name']; ?></td>

    <td><?php echo $student['gender']; ?></td>

    <td><?php echo $student['course']; ?></td>

    <td><?php echo $student['year']; ?></td>

    <td><?php echo $student['mobile']; ?></td>

    <td><?php echo $student['email']; ?></td>

    <td>
        <?php
        echo $student['room_number'] ?? "Not Allocated";
        ?>
    </td>

    <td>
        <?php
        echo $student['room_type'] ?? "-";
        ?>
    </td>

    <td>

        <a href="edit_student.php?id=<?php echo $student['student_id']; ?>">
            Edit
        </a>

        |

        <a href="delete_student.php?id=<?php echo $student['student_id']; ?>"
           onclick="return confirm('Delete this student?');">
            Delete
        </a>

    </td>

</tr>

<?php } ?>

</table>

</body>
</html>