<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: gate_passes.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT *
        FROM gate_passes
        WHERE pass_id = '$id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: gate_passes.php");
    exit();
}

$pass = mysqli_fetch_assoc($result);

if (isset($_POST['update_status'])) {

    $status = $_POST['status'];

    if ($status == "Approved") {

        $approved_at = date("Y-m-d H:i:s");

        $update = "UPDATE gate_passes
                   SET status = '$status',
                       approved_at = '$approved_at'
                   WHERE pass_id = '$id'";

    } else {

        $update = "UPDATE gate_passes
                   SET status = '$status',
                       approved_at = NULL
                   WHERE pass_id = '$id'";
    }

    if (mysqli_query($conn, $update)) {

        header("Location: gate_passes.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Gate Pass</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Update Gate Pass</h1>

<p>
    Pass ID:
    <strong><?php echo $pass['pass_id']; ?></strong>
</p>

<p>
    Pass Type:
    <?php echo $pass['pass_type']; ?>
</p>

<p>
    From:
    <?php echo $pass['from_date']; ?>
</p>

<p>
    To:
    <?php echo $pass['to_date']; ?>
</p>

<p>
    Reason:
    <?php echo $pass['reason']; ?>
</p>

<form method="POST">

    <label>Status</label>

    <select name="status" required>

        <option value="Pending"
        <?php
        if ($pass['status'] == 'Pending')
            echo 'selected';
        ?>>
            Pending
        </option>

        <option value="Approved"
        <?php
        if ($pass['status'] == 'Approved')
            echo 'selected';
        ?>>
            Approved
        </option>

        <option value="Rejected"
        <?php
        if ($pass['status'] == 'Rejected')
            echo 'selected';
        ?>>
            Rejected
        </option>

    </select>

    <br><br>

    <button type="submit" name="update_status">
        Update Status
    </button>

</form>

<br>

<a href="gate_passes.php">Back to Gate Passes</a>

</body>
</html>