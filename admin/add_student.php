<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../config/database.php";

$message = "";
$generated_username = "";
$generated_password = "";

if (isset($_POST['add_student'])) {

    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $course = trim($_POST['course']);
    $year = trim($_POST['year']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);

    /*
     * Create username from student's name
     * Example: Jhansi Patil -> jhansi
     */

    $name_parts = explode(" ", strtolower($name));

    $base_username = preg_replace(
        "/[^a-z0-9]/",
        "",
        $name_parts[0]
    );

    $username = $base_username;
    $count = 1;

    // Check if username already exists
    while (true) {

        $check = "SELECT user_id
                  FROM users
                  WHERE username = '$username'";

        $check_result = mysqli_query($conn, $check);

        if (mysqli_num_rows($check_result) == 0) {
            break;
        }

        $count++;

        $username = $base_username . $count;
    }

    /*
     * Generate temporary password
     */

    $generated_password =
        strtoupper(substr($base_username, 0, 2))
        . "@"
        . rand(1000, 9999);

    /*
     * Create user account
     */

    $user_sql = "INSERT INTO users
                (name, username, password, role)
                VALUES
                ('$name',
                 '$username',
                 '$generated_password',
                 'student')";

    if (mysqli_query($conn, $user_sql)) {

        $user_id = mysqli_insert_id($conn);

        /*
         * Create student record
         */

        $student_sql = "INSERT INTO students
                        (user_id, name, gender, course,
                         year, mobile, email, room_id)
                        VALUES
                        ('$user_id',
                         '$name',
                         '$gender',
                         '$course',
                         '$year',
                         '$mobile',
                         '$email',
                         NULL)";

        if (mysqli_query($conn, $student_sql)) {

            $message = "Student added successfully!";

            $generated_username = $username;

        } else {

            $message = "Student details could not be saved.";
        }

    } else {

        $message = "Account could not be created.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Student</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body align="center">

<h1>Add Student</h1>

<a href="students.php">Back to Students</a>

<br><br>

<?php if ($message != "") { ?>

    <p>
        <?php echo $message; ?>
    </p>

<?php } ?>

<?php if ($generated_username != "") { ?>

    <div>

        <h3>Student Login Credentials</h3>

        <p>
            <strong>Username:</strong>
            <?php echo $generated_username; ?>
        </p>

        <p>
            <strong>Temporary Password:</strong>
            <?php echo $generated_password; ?>
        </p>

        <p>
            Give these credentials to the student.
        </p>

    </div>

<?php } ?>

<form method="POST">

    <label>Name</label>

    <input
        type="text"
        name="name"
        required
    >

    <br><br>

    <label>Gender</label>

    <select name="gender" required>

        <option value="">
            Select Gender
        </option>

        <option value="Male">
            Male
        </option>

        <option value="Female">
            Female
        </option>

    </select>

    <br><br>

    <label>Course</label>

    <input
        type="text"
        name="course"
        required
    >

    <br><br>

    <label>Year</label>

    <input
        type="text"
        name="year"
        required
    >

    <br><br>

    <label>Mobile</label>

    <input
        type="text"
        name="mobile"
        required
    >

    <br><br>

    <label>Email</label>

    <input
        type="email"
        name="email"
        required
    >

    <br><br>

    <button type="submit" name="add_student">
        Add Student
    </button>

</form>

</body>

</html>