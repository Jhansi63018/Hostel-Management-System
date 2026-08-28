<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$student_sql = "SELECT student_id
                FROM students
                WHERE user_id = '$user_id'";

$student_result = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_result);

$student_id = $student['student_id'];

$sql = "SELECT *
        FROM maintenance_tickets
        WHERE student_id = '$student_id'
        ORDER BY ticket_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Maintenance Tickets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>My Maintenance Tickets</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="maintenance.php">Raise Ticket</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Ticket ID</th>
    <th>Category</th>
    <th>Priority</th>
    <th>Description</th>
    <th>Created</th>
    <th>SLA Due</th>
    <th>Status</th>
</tr>

<?php

while ($ticket = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td>
        <?php echo $ticket['ticket_id']; ?>
    </td>

    <td>
        <?php echo $ticket['category']; ?>
    </td>

    <td>
        <?php echo $ticket['priority']; ?>
    </td>

    <td>
        <?php echo $ticket['description']; ?>
    </td>

    <td>
        <?php echo $ticket['created_at']; ?>
    </td>

    <td>
        <?php echo $ticket['due_time']; ?>
    </td>

    <td>
        <?php echo $ticket['status']; ?>
    </td>

</tr>

<?php
}
?>

</table>

</body>
</html>