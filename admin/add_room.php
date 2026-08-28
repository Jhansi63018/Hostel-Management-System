<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";

if (isset($_POST['add_room'])) {

    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];

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

    $sql = "INSERT INTO rooms
            (room_number, room_type, geyser, capacity, occupied)
            VALUES
            ('$room_number', '$room_type', '$geyser', '$capacity', 0)";

    if (mysqli_query($conn, $sql)) {
        header("Location: rooms.php");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Room</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Add Room</h1>

<a href="rooms.php">Back to Rooms</a>

<br><br>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>Room Number</label>
    <input type="text" name="room_number" required>

    <br><br>

    <label>Room Type</label>

    <select name="room_type" required>

        <option value="">Select Room Type</option>

        <option value="Deluxe">
            Deluxe - 5 Members - Geyser
        </option>

        <option value="Normal">
            Normal - 8 Members - No Geyser
        </option>

        <option value="AC">
            AC - 4 Members - Geyser
        </option>

        <option value="Big Room">
            Big Room - 20 Members - No Geyser
        </option>

    </select>

    <br><br>

    <button type="submit" name="add_room">
        Add Room
    </button>

</form>

</body>
</html>