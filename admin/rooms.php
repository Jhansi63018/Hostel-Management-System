<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$sql = "SELECT * FROM rooms ORDER BY room_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<h1>Room Management</h1>

<p>Welcome, <?php echo $_SESSION['name']; ?>!</p>

<a href="dashboard.php">Dashboard</a> |
<a href="add_room.php">Add Room</a> |
<a href="../logout.php">Logout</a>

<br><br>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>Room ID</th>
        <th>Room Number</th>
        <th>Room Type</th>
        <th>Capacity</th>
        <th>Occupied</th>
        <th>Available</th>
        <th>Geyser</th>
        <th>Action</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {

        while ($room = mysqli_fetch_assoc($result)) {

            $available = $room['capacity'] - $room['occupied'];
    ?>

    <tr>
        <td><?php echo $room['room_id']; ?></td>

        <td><?php echo $room['room_number']; ?></td>

        <td><?php echo $room['room_type']; ?></td>

        <td><?php echo $room['capacity']; ?></td>

        <td><?php echo $room['occupied']; ?></td>

        <td><?php echo $available; ?></td>

        <td><?php echo $room['geyser']; ?></td>

        <td>
            <a href="edit_room.php?id=<?php echo $room['room_id']; ?>">
                Edit
            </a>

            |

            <a href="delete_room.php?id=<?php echo $room['room_id']; ?>"
               onclick="return confirm('Are you sure you want to delete this room?');">
                Delete
            </a>
        </td>
    </tr>

    <?php
        }

    } else {
    ?>

    <tr>
        <td colspan="8">No rooms found.</td>
    </tr>

    <?php
    }
    ?>

</table>

</body>
</html>