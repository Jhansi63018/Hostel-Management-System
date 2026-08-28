<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

/* Get student and room details */
$sql = "SELECT 
            students.student_id,
            students.name,
            students.course,
            students.year,
            students.room_id,
            rooms.room_number,
            rooms.room_type,
            rooms.capacity,
            rooms.occupied,
            rooms.geyser
        FROM students
        LEFT JOIN rooms
        ON students.room_id = rooms.room_id
        WHERE students.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Student Dashboard</h1>

<p align="center">
    Welcome, <strong><?php echo $student['name']; ?></strong>!
</p>

<hr>

<h2>My Room</h2>

<?php if ($student['room_id'] == NULL) { ?>

    <p>Room Not Allocated Yet.</p>

<?php } else { ?>

    <?php
    $available = $student['capacity'] - $student['occupied'];
    ?>

    <table align="center" border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>Room Number</th>
            <td><?php echo $student['room_number']; ?></td>
        </tr>

        <tr>
            <th>Room Type</th>
            <td><?php echo $student['room_type']; ?></td>
        </tr>

        <tr>
            <th>Total Capacity</th>
            <td><?php echo $student['capacity']; ?> Members</td>
        </tr>

        <tr>
            <th>Occupied</th>
            <td><?php echo $student['occupied']; ?> Members</td>
        </tr>

        <tr>
            <th>Available</th>
            <td><?php echo $available; ?> Members</td>
        </tr>

        <tr>
            <th>Geyser</th>
            <td><?php echo $student['geyser']; ?></td>
        </tr>

    </table>

<?php } ?>

<br>


<h2>Student Services</h2>

<ul align="center">

    <li>
        <a href="maintenance.php">
            Raise Maintenance Ticket
        </a>
    </li>

    <li>
        <a href="my_tickets.php">
            My Maintenance Tickets
        </a>
    </li>

    <li>
        <a href="gate_pass.php">
            Request Digital Gate Pass
        </a>
    </li>

    <li>
        <a href="my_gate_passes.php">
            My Gate Passes
        </a>
    </li>

</ul>
<br>

<a href="../logout.php">Logout</a>

</body>

</html>