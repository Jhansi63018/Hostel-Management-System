<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$student_id = $_GET['id'];

$sql = "SELECT *
        FROM students
        WHERE student_id = '$student_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: students.php");
    exit();
}

$student = mysqli_fetch_assoc($result);

if (isset($_POST['update_student'])) {

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    $sql = "UPDATE students
            SET name = '$name',
                gender = '$gender',
                course = '$course',
                year = '$year',
                mobile = '$mobile',
                email = '$email'
            WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $sql)) {

        // Also update name in users table
        $user_id = $student['user_id'];

        $user_sql = "UPDATE users
                     SET name = '$name'
                     WHERE user_id = '$user_id'";

        mysqli_query($conn, $user_sql);

        header("Location: students.php");
        exit();
    }

    $error = "Student could not be updated.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body align="center">

<h1>Edit Student</h1>

<a href="students.php">Back to Students</a>

<br><br>

<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>

<form method="POST">

    <label>Name</label>
    <br>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($student['name']); ?>"
        required
    >

    <br><br>

    <label>Gender</label>
    <br>

    <select name="gender" required>

        <option value="Male"
        <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>
            Male
        </option>

        <option value="Female"
        <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>
            Female
        </option>

    </select>

    <br><br>

    <label>Course</label>
    <br>

    <input
        type="text"
        name="course"
        value="<?php echo htmlspecialchars($student['course']); ?>"
        required
    >

    <br><br>

    <label>Year</label>
    <br>

    <input
        type="text"
        name="year"
        value="<?php echo htmlspecialchars($student['year']); ?>"
        required
    >

    <br><br>

    <label>Mobile</label>
    <br>

    <input
        type="text"
        name="mobile"
        value="<?php echo htmlspecialchars($student['mobile']); ?>"
        required
    >

    <br><br>

    <label>Email</label>
    <br>

    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($student['email']); ?>"
        required
    >

    <br><br>

    <button type="submit" name="update_student">
        Update Student
    </button>

</form>

</body>

</html>