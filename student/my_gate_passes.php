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
        FROM gate_passes
        WHERE student_id = '$student_id'
        ORDER BY pass_id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Gate Passes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>My Gate Passes</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="gate_pass.php">Request Gate Pass</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Pass ID</th>
    <th>Pass Type</th>
    <th>From Date</th>
    <th>To Date</th>
    <th>Reason</th>
    <th>Requested On</th>
    <th>Status</th>
    <th>Approved On</th>
</tr>

<?php

if (mysqli_num_rows($result) > 0) {

    while ($pass = mysqli_fetch_assoc($result)) {

?>

<tr>

    <td><?php echo $pass['pass_id']; ?></td>

    <td><?php echo $pass['pass_type']; ?></td>

    <td><?php echo $pass['from_date']; ?></td>

    <td><?php echo $pass['to_date']; ?></td>

    <td><?php echo $pass['reason']; ?></td>

    <td><?php echo $pass['created_at']; ?></td>

    <td><?php echo $pass['status']; ?></td>

    <td>
        <?php
        if ($pass['approved_at'] != NULL) {
            echo $pass['approved_at'];
        } else {
            echo "-";
        }
        ?>
    </td>

</tr>

<?php
    }

} else {
?>

<tr>
    <td colspan="8">
        No gate pass requests found.
    </td>
</tr>

<?php
}
?>

</table>

</body>
</html>