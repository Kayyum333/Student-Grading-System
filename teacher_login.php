<?php

session_start();

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($username) || empty($password)) {

        $message = "Please enter username and password.";

    } else {

        $sql = "SELECT * FROM teacher
                WHERE userName = '$username'
                AND p_word = '$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {

            $teacher = mysqli_fetch_assoc($result);

            // Store teacher information in session
            $_SESSION["teacher_id"] = $teacher["t_id"];
            $_SESSION["teacher_name"] = $teacher["trname"];
            $_SESSION["teacher_username"] = $teacher["userName"];
            $_SESSION["teacher_subject"] = $teacher["sub_spec"];
            $_SESSION["teacher_class_teacher"] = $teacher["class_teacher"];

            // Redirect to teacher dashboard
            header("Location: teacher_dashboard.php");
            exit();

        } else {

            $message = "Invalid username or password.";

        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Teacher Login</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>


<div class="login-container">


    <div class="login-box">


        <h1>Teacher Login</h1>

        <p>Student Grading System</p>


        <!-- PHP Error Message -->

        <?php if ($message != "") { ?>

            <div class="error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <form method="POST"
              action="teacher_login.php"
              id="teacherLoginForm">


            <!-- Username -->

            <div class="form-group">

                <label>
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    id="username"
                    placeholder="Enter username"
                    class="validate"
                    data-type="username"
                    autocomplete="username"
                >

                <span class="field-error" id="usernameError"></span>

            </div>


            <!-- Password -->

            <div class="form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter password"
                    class="validate"
                    data-type="password"
                    autocomplete="current-password"
                >

                <span class="field-error" id="passwordError"></span>

            </div>


            <button type="submit">
                Login
            </button>


        </form>


        <p class="login-note">

            Login using your teacher account

        </p>


    </div>


</div>


<!-- Common JavaScript Validation -->

<script src="js/validation.js"></script>


</body>

</html>