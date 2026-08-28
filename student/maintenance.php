<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$student_sql = "SELECT student_id, name
                FROM students
                WHERE user_id = '$user_id'";

$student_result = mysqli_query($conn, $student_sql);
$student = mysqli_fetch_assoc($student_result);

$message = "";

if (isset($_POST['submit_ticket'])) {

    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $description = $_POST['description'];

    /* Set SLA hours based on priority */

    if ($priority == "Low") {
        $sla_hours = 48;
    }
    elseif ($priority == "Medium") {
        $sla_hours = 24;
    }
    elseif ($priority == "High") {
        $sla_hours = 12;
    }
    else {
        $sla_hours = 4;
    }

    $created_at = date("Y-m-d H:i:s");

    $due_time = date(
        "Y-m-d H:i:s",
        strtotime("+$sla_hours hours")
    );

    $sql = "INSERT INTO maintenance_tickets
            (student_id, category, priority, sla_hours,
             description, created_at, due_time, status)
            VALUES
            (
                '{$student['student_id']}',
                '$category',
                '$priority',
                '$sla_hours',
                '$description',
                '$created_at',
                '$due_time',
                'Pending'
            )";

    if (mysqli_query($conn, $sql)) {

        $message = "Maintenance ticket submitted successfully.";

    } else {

        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Maintenance Ticket</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Raise Maintenance Ticket</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="my_tickets.php">My Tickets</a> |
<a href="../logout.php">Logout</a>

<br><br>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>Category</label>

    <select name="category" required>

        <option value="">Select Category</option>

        <option value="Electrical">
            Electrical
        </option>

        <option value="Plumbing">
            Plumbing
        </option>

        <option value="Cleaning">
            Cleaning
        </option>

        <option value="Furniture">
            Furniture
        </option>

        <option value="Internet">
            Internet
        </option>

        <option value="Other">
            Other
        </option>

    </select>

    <br><br>

    <label>Priority</label>

    <select name="priority" required>

        <option value="Low">
            Low - 48 Hours
        </option>

        <option value="Medium" selected>
            Medium - 24 Hours
        </option>

        <option value="High">
            High - 12 Hours
        </option>

        <option value="Urgent">
            Urgent - 4 Hours
        </option>

    </select>

    <br><br>

    <label>Problem Description</label>

    <br>

    <textarea
        name="description"
        rows="5"
        cols="40"
        required></textarea>

    <br><br>

    <button type="submit" name="submit_ticket">
        Submit Ticket
    </button>

</form>

</body>
</html>