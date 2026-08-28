<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: rooms.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM rooms WHERE room_id = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: rooms.php");
    exit();
}

$room = mysqli_fetch_assoc($result);

if (isset($_POST['update_room'])) {

    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $occupied=$_POST['occupied'];
    if ($room_type == "Deluxe") {
        $capacity = 5;
        $geyser = "Yes";
    }
    elseif ($room_type == "Normal") {
        $capacity = 8;
        $geyser = "No";
    }
    elseif ($room_type == "AC") {
        $capacity = 4;
        $geyser = "Yes";
    }
    elseif ($room_type == "Big Room") {
        $capacity = 20;
        $geyser = "No";
    }

    $sql = "UPDATE rooms SET
            room_number = '$room_number',
            room_type = '$room_type',
            geyser = '$geyser',
            capacity = '$capacity',
            occupied='$occupied'
            WHERE room_id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: rooms.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Room</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Edit Room</h1>

<a href="rooms.php">Back to Rooms</a>

<br><br>

<form method="POST">

    <label>Room Number</label>

    <input type="text"
           name="room_number"
           value="<?php echo $room['room_number']; ?>"
           required>

    <br><br>

    <label>Room Type</label>

    <select name="room_type" required>

        <option value="Deluxe"
        <?php if ($room['room_type'] == 'Deluxe') echo 'selected'; ?>>
            Deluxe - 5 Members - Geyser
        </option>

        <option value="Normal"
        <?php if ($room['room_type'] == 'Normal') echo 'selected'; ?>>
            Normal - 8 Members - No Geyser
        </option>

        <option value="AC"
        <?php if ($room['room_type'] == 'AC') echo 'selected'; ?>>
            AC - 4 Members - Geyser
        </option>

        <option value="Big Room"
        <?php if ($room['room_type'] == 'Big Room') echo 'selected'; ?>>
            Big Room - 20 Members - No Geyser
        </option>

    </select>
<br><br>
<label>Occupied</label>

    <input type="text"
           name="occupied"
           value="<?php echo $room['occupied']; ?>"
           required>


    <br><br>

    <button type="submit" name="update_room">
        Update Room
    </button>

</form>

</body>
</html>