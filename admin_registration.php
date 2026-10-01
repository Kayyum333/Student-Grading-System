<?php

include "db.php";

$error = [];

$success = "";

$fullName = "";
$userName = "";
$email = "";
$password = "";
$confirmPassword = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST["fullName"] ?? "");
    $userName = trim($_POST["userName"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";


    // =========================
    // FULL NAME VALIDATION
    // =========================

    if ($fullName == "") {

        $error["fullName"] = "Full name is required.";

    }

    elseif (!preg_match("/^[a-zA-Z ]+$/", $fullName)) {

        $error["fullName"] =
            "Full name should contain only letters and spaces.";

    }


    // =========================
    // USERNAME VALIDATION
    // =========================

    if ($userName == "") {

        $error["userName"] = "Username is required.";

    }

    elseif (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $userName)) {

        $error["userName"] =
            "Username must be 4-20 characters and contain only letters, numbers and underscore.";

    }


    // =========================
    // EMAIL VALIDATION
    // =========================

    if ($email == "") {

        $error["email"] = "Email is required.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error["email"] =
            "Please enter a valid email address.";

    }


    // =========================
    // PASSWORD VALIDATION
    // =========================

    if ($password == "") {

        $error["password"] = "Password is required.";

    }

    elseif (strlen($password) < 6) {

        $error["password"] =
            "Password must be at least 6 characters.";

    }

    elseif (strlen($password) > 15) {

        $error["password"] =
            "Password cannot exceed 15 characters.";

    }


    // =========================
    // CONFIRM PASSWORD
    // =========================

    if ($confirmPassword == "") {

        $error["confirmPassword"] =
            "Please confirm your password.";

    }

    elseif ($password != $confirmPassword) {

        $error["confirmPassword"] =
            "Passwords do not match.";

    }


    // =========================
    // CHECK DATABASE
    // =========================

    if (empty($error)) {


        // Check duplicate username

        $checkUsername =
            "SELECT * FROM admin WHERE userName = '$userName'";

        $usernameResult =
            mysqli_query($conn, $checkUsername);


        if (mysqli_num_rows($usernameResult) > 0) {

            $error["userName"] =
                "Username already exists.";

        }


        // Check duplicate email

        $checkEmail =
            "SELECT * FROM admin WHERE email = '$email'";

        $emailResult =
            mysqli_query($conn, $checkEmail);


        if (mysqli_num_rows($emailResult) > 0) {

            $error["email"] =
                "Email already exists.";

        }

    }


    // =========================
    // INSERT ADMIN
    // =========================

    if (empty($error)) {

        $sql = "INSERT INTO admin
                (userName, email, fullName, p_word, confirm_pass)
                VALUES
                ('$userName', '$email', '$fullName',
                 '$password', '$confirmPassword')";


        if (mysqli_query($conn, $sql)) {

            $success =
                "Admin registered successfully!";


            // Clear fields after successful registration

            $fullName = "";
            $userName = "";
            $email = "";
            $password = "";
            $confirmPassword = "";

        }

        else {

            $error["database"] =
                "Registration failed: " . mysqli_error($conn);

        }

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Registration</title>


    <link rel="stylesheet" href="style.css">
</head>


<body>


<div class="login-container">


    <div class="login-box">


        <h1>Student Grading System</h1>

        <h2>Admin Registration</h2>


        <!-- SUCCESS MESSAGE -->

        <?php if ($success != "") { ?>

            <div class="success-message">

                <?php echo $success; ?>

            </div>

        <?php } ?>


        <!-- DATABASE ERROR -->

        <?php if (isset($error["database"])) { ?>

            <div class="database-error">

                <?php echo $error["database"]; ?>

            </div>

        <?php } ?>


        <form method="POST">


            <!-- =========================
                 FULL NAME
                 ========================= -->

            <div class="form-group">

                <label>Full Name</label>


                <input
                    type="text"
                    id="fullName"
                    name="fullName"
                    class="validate"
                    data-type="fullname"
                    placeholder="Enter full name"
                    value="<?php echo htmlspecialchars($fullName); ?>"
                >


                <div
                    id="fullNameError"
                    class="field-error"
                >

                    <?php

                    if (isset($error["fullName"])) {

                        echo "⚠ " . $error["fullName"];

                    }

                    ?>

                </div>

            </div>


            <!-- =========================
                 USERNAME
                 ========================= -->

            <div class="form-group">

                <label>Username</label>


                <input
                    type="text"
                    id="userName"
                    name="userName"
                    class="validate"
                    data-type="username"
                    placeholder="Enter username"
                    value="<?php echo htmlspecialchars($userName); ?>"
                >


                <div
                    id="userNameError"
                    class="field-error"
                >

                    <?php

                    if (isset($error["userName"])) {

                        echo "⚠ " . $error["userName"];

                    }

                    ?>

                </div>

            </div>


            <!-- =========================
                 EMAIL
                 ========================= -->

            <div class="form-group">

                <label>Email</label>


                <input
                    type="email"
                    id="email"
                    name="email"
                    class="validate"
                    data-type="email"
                    placeholder="Enter email"
                    value="<?php echo htmlspecialchars($email); ?>"
                >


                <div
                    id="emailError"
                    class="field-error"
                >

                    <?php

                    if (isset($error["email"])) {

                        echo "⚠ " . $error["email"];

                    }

                    ?>

                </div>

            </div>


            <!-- =========================
                 PASSWORD
                 ========================= -->

            <div class="form-group">

                <label>Password</label>


                <input
                    type="password"
                    id="password"
                    name="password"
                    class="validate"
                    data-type="password"
                    placeholder="Enter password"
                >


                <div
                    id="passwordError"
                    class="field-error"
                >

                    <?php

                    if (isset($error["password"])) {

                        echo "⚠ " . $error["password"];

                    }

                    ?>

                </div>

            </div>


            <!-- =========================
                 CONFIRM PASSWORD
                 ========================= -->

            <div class="form-group">

                <label>Confirm Password</label>


                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    class="validate"
                    data-type="confirmPassword"
                    placeholder="Enter password again"
                >


                <div
                    id="confirmPasswordError"
                    class="field-error"
                >

                    <?php

                    if (isset($error["confirmPassword"])) {

                        echo "⚠ " . $error["confirmPassword"];

                    }

                    ?>

                </div>

            </div>


            <!-- REGISTER BUTTON -->

            <button type="submit">

                Register

            </button>


        </form>


        <p class="login-note">

            Already have an account?

            <a href="admin_login.php">

                Login

            </a>

        </p>


    </div>


</div>


<!-- =========================
     VALIDATION JAVASCRIPT
     ========================= -->

<script src="validation.js"></script>


</body>

</html>