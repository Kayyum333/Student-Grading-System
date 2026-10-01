<?php

include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");


    // =========================
    // SERVER-SIDE VALIDATION
    // =========================

    // Username validation
    if ($username == "") {

        $error = "Username is required.";

    } elseif (strlen($username) < 3) {

        $error = "Username must contain at least 3 characters.";

    } elseif (strlen($username) > 20) {

        $error = "Username cannot exceed 20 characters.";

    } elseif (!preg_match("/^[A-Za-z0-9_]+$/", $username)) {

        $error = "Username can contain only letters, numbers and underscore.";

    }


    // Password validation
    elseif ($password == "") {

        $error = "Password is required.";

    } elseif (strlen($password) < 6) {

        $error = "Password must contain at least 6 characters.";

    } elseif (strlen($password) > 15) {

        $error = "Password cannot exceed 15 characters.";

    }


    // =========================
    // DATABASE AUTHENTICATION
    // =========================

    else {

        $sql = "SELECT * FROM admin WHERE userName = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $username);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);


        if (mysqli_num_rows($result) == 1) {

            $admin = mysqli_fetch_assoc($result);


            // Check password
            if ($password == $admin["p_word"]) {

                header("Location: admin_dashboard.php");
                exit();

            } else {

                $error = "Invalid username or password.";

            }

        } else {

            $error = "Invalid username or password.";

        }


        mysqli_stmt_close($stmt);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - Student Grading System</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>

    <div class="login-container">

        <div class="login-box">

            <h1>Student Grading System</h1>

            <h2>Admin Login</h2>


            <form method="POST" id="adminLoginForm">


                <!-- =========================
                     USERNAME
                ========================== -->

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter username"
                        class="validate"
                        data-type="username"
                    >

                    <span
                        id="usernameError"
                        class="field-error"
                    >
                        <?php

                        // Show PHP username validation error
                        if (
                            $error == "Username is required." ||
                            $error == "Username must contain at least 3 characters." ||
                            $error == "Username cannot exceed 20 characters." ||
                            $error == "Username can contain only letters, numbers and underscore."
                        ) {
                            echo htmlspecialchars($error);
                        }

                        ?>
                    </span>

                </div>


                <!-- =========================
                     PASSWORD
                ========================== -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        class="validate"
                        data-type="password"
                    >

                    <span
                        id="passwordError"
                        class="field-error"
                    >
                        <?php

                        // Show PHP password/database error
                        if (
                            $error == "Password is required." ||
                            $error == "Password must contain at least 6 characters." ||
                            $error == "Password cannot exceed 15 characters." ||
                            $error == "Invalid username or password."
                        ) {
                            echo htmlspecialchars($error);
                        }

                        ?>
                    </span>

                </div>


                <!-- Registration Link -->

                <div class="login-register">

                    <a href="admin_registration.php">
                        Don't have an account?
                    </a>

                </div>


                <!-- Login Button -->

                <button type="submit">
                    Login
                </button>


            </form>

        </div>

    </div>


    <!-- JavaScript Validation -->

    <script src="validation.js"></script>

</body>

</html>