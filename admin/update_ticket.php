<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: maintenance.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT *
        FROM maintenance_tickets
        WHERE ticket_id = '$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: maintenance.php");
    exit();
}

$ticket = mysqli_fetch_assoc($result);

if (isset($_POST['update_status'])) {

    $status = $_POST['status'];

    $update = "UPDATE maintenance_tickets
               SET status = '$status'
               WHERE ticket_id = '$id'";

    if (mysqli_query($conn, $update)) {
        header("Location: maintenance.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Ticket</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Update Maintenance Ticket</h1>

<p>
    Ticket ID:
    <strong><?php echo $ticket['ticket_id']; ?></strong>
</p>

<p>
    Category:
    <?php echo $ticket['category']; ?>
</p>

<p>
    Priority:
    <?php echo $ticket['priority']; ?>
</p>

<p>
    Description:
    <?php echo $ticket['description']; ?>
</p>

<form method="POST">

    <label>Status</label>

    <select name="status">

        <option value="Pending"
        <?php if ($ticket['status'] == 'Pending') echo 'selected'; ?>>
            Pending
        </option>

        <option value="In Progress"
        <?php if ($ticket['status'] == 'In Progress') echo 'selected'; ?>>
            In Progress
        </option>

        <option value="Resolved"
        <?php if ($ticket['status'] == 'Resolved') echo 'selected'; ?>>
            Resolved
        </option>

    </select>

    <br><br>

    <button type="submit" name="update_status">
        Update Status
    </button>

</form>

<br>

<a href="maintenance.php">Back to Tickets</a>

</body>
</html>