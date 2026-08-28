<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: student_rooms.php");
    exit();
}

$student_id = $_GET['id'];

/* Get student */

$student_sql = "SELECT *
                FROM students
                WHERE student_id = '$student_id'";

$student_result = mysqli_query($conn, $student_sql);

if (mysqli_num_rows($student_result) != 1) {
    header("Location: student_rooms.php");
    exit();
}

$student = mysqli_fetch_assoc($student_result);

$error = "";

if (isset($_POST['allocate'])) {

    $room_id = $_POST['room_id'];

    /* Get selected room */

    $room_sql = "SELECT *
                 FROM rooms
                 WHERE room_id = '$room_id'";

    $room_result = mysqli_query($conn, $room_sql);

    if (mysqli_num_rows($room_result) == 1) {

        $room = mysqli_fetch_assoc($room_result);

        $available =
            $room['capacity'] - $room['occupied'];

        if ($available > 0) {

            /* Allocate room to student */

            $update_student = "UPDATE students
                               SET room_id = '$room_id'
                               WHERE student_id = '$student_id'
                               AND room_id IS NULL";

            if (mysqli_query($conn, $update_student)) {

                if (mysqli_affected_rows($conn) > 0) {

                    /* Increase room occupancy */

                    $update_room = "UPDATE rooms
                                    SET occupied = occupied + 1
                                    WHERE room_id = '$room_id'
                                    AND occupied < capacity";

                    mysqli_query($conn, $update_room);

                    header("Location: student_rooms.php");
                    exit();

                } else {

                    $error = "This student already has a room.";
                }

            } else {

                $error = "Room allocation failed.";
            }

        } else {

            $error = "This room is full.";
        }

    } else {

        $error = "Invalid room selected.";
    }
}


/* Get available rooms */

$rooms_sql = "SELECT *
              FROM rooms
              WHERE occupied < capacity
              ORDER BY room_number";

$rooms_result = mysqli_query($conn, $rooms_sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Allocate Room</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body align="center">

<h1>Allocate Room</h1>

<p>

    Student:

    <strong>
        <?php echo htmlspecialchars($student['name']); ?>
    </strong>

</p>

<p>

    Course:

    <?php echo htmlspecialchars($student['course']); ?>

</p>

<?php if ($error != "") { ?>

    <p>
        <?php echo $error; ?>
    </p>

<?php } ?>

<form method="POST">

    <label>Select Available Room</label>

    <br><br>

    <select name="room_id" required>

        <option value="">
            Select Room
        </option>

        <?php while ($room = mysqli_fetch_assoc($rooms_result)) { ?>

            <?php
            $available =
                $room['capacity'] - $room['occupied'];
            ?>

            <option value="<?php echo $room['room_id']; ?>">

                <?php
                echo $room['room_number'];
                echo " - ";
                echo $room['room_type'];
                echo " - Capacity: ";
                echo $room['capacity'];
                echo " - Available: ";
                echo $available;
                echo " - Geyser: ";
                echo $room['geyser'];
                ?>

            </option>

        <?php } ?>

    </select>

    <br><br>

    <button type="submit" name="allocate">
        Allocate Room
    </button>

</form>

<br>

<a href="student_rooms.php">
    Back
</a>

</body>

</html>