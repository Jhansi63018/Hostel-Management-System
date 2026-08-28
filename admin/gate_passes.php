<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT
            gate_passes.*,
            students.name
        FROM gate_passes
        INNER JOIN students
        ON gate_passes.student_id = students.student_id
        ORDER BY gate_passes.pass_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gate Pass Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Gate Pass Management</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Pass ID</th>
    <th>Student</th>
    <th>Pass Type</th>
    <th>From Date</th>
    <th>To Date</th>
    <th>Reason</th>
    <th>Requested On</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php

while ($pass = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td><?php echo $pass['pass_id']; ?></td>

    <td><?php echo $pass['name']; ?></td>

    <td><?php echo $pass['pass_type']; ?></td>

    <td><?php echo $pass['from_date']; ?></td>

    <td><?php echo $pass['to_date']; ?></td>

    <td><?php echo $pass['reason']; ?></td>

    <td><?php echo $pass['created_at']; ?></td>

    <td><?php echo $pass['status']; ?></td>

    <td>

        <a href="update_gate_pass.php?id=<?php echo $pass['pass_id']; ?>">
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