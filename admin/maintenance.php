<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT
            maintenance_tickets.*,
            students.name
        FROM maintenance_tickets
        INNER JOIN students
        ON maintenance_tickets.student_id = students.student_id
        ORDER BY maintenance_tickets.ticket_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Maintenance Tickets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Maintenance Tickets</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Ticket ID</th>
    <th>Student</th>
    <th>Category</th>
    <th>Priority</th>
    <th>Description</th>
    <th>Created</th>
    <th>SLA Due</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php

while ($ticket = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td><?php echo $ticket['ticket_id']; ?></td>

    <td><?php echo $ticket['name']; ?></td>

    <td><?php echo $ticket['category']; ?></td>

    <td><?php echo $ticket['priority']; ?></td>

    <td><?php echo $ticket['description']; ?></td>

    <td><?php echo $ticket['created_at']; ?></td>

    <td><?php echo $ticket['due_time']; ?></td>

    <td><?php echo $ticket['status']; ?></td>

    <td>

        <a href="update_ticket.php?id=<?php echo $ticket['ticket_id']; ?>">
            Update
        </a>

    </td>

</tr>

<?php
}
?>

</table>

</body>
</html>