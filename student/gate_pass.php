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

if (isset($_POST['submit_pass'])) {

    $pass_type = $_POST['pass_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $reason = $_POST['reason'];

    if ($to_date < $from_date) {

        $message = "To date cannot be before From date.";

    } else {

        $sql = "INSERT INTO gate_passes
                (student_id, pass_type, from_date, to_date, reason, status)
                VALUES
                (
                    '{$student['student_id']}',
                    '$pass_type',
                    '$from_date',
                    '$to_date',
                    '$reason',
                    'Pending'
                )";

        if (mysqli_query($conn, $sql)) {

            $message = "Gate pass request submitted successfully.";

        } else {

            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Digital Gate Pass</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Digital Gate Pass</h1>

<a href="dashboard.php">Dashboard</a> |
<a href="my_gate_passes.php">My Gate Passes</a> |
<a href="../logout.php">Logout</a>

<br><br>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>Pass Type</label>

    <select name="pass_type" required>

        <option value="">Select Pass Type</option>

        <option value="Outing">
            Outing
        </option>

        <option value="Home Visit">
            Home Visit
        </option>

        <option value="Emergency">
            Emergency
        </option>

        <option value="Other">
            Other
        </option>

    </select>

    <br><br>

    <label>From Date</label>

    <input type="date"
           name="from_date"
           required>

    <br><br>

    <label>To Date</label>

    <input type="date"
           name="to_date"
           required>

    <br><br>

    <label>Reason</label>

    <br>

    <textarea
        name="reason"
        rows="5"
        cols="40"
        required></textarea>

    <br><br>

    <button type="submit" name="submit_pass">
        Submit Gate Pass
    </button>

</form>

</body>
</html>