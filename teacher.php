<?php

include 'db.php';

$message = "";
$errors = [];


/* =====================================================
   ADD / UPDATE / DELETE TEACHER
   ===================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $t_id = trim($_POST["t_id"] ?? "");
    $trname = trim($_POST["trname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $userName = trim($_POST["userName"] ?? "");
    $p_word = trim($_POST["p_word"] ?? "");
    $sub_spec = trim($_POST["sub_spec"] ?? "");
    $quali = trim($_POST["quali"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $mo_no = trim($_POST["mo_no"] ?? "");
    $addr = trim($_POST["addr"] ?? "");
    $class_teacher = trim($_POST["class_teacher"] ?? "");

    $role = "Teacher";


    /* =================================================
       DELETE TEACHER
       ================================================= */

    if (isset($_POST["delete_teacher"])) {

        if ($t_id == "") {

            $errors["t_id"] = "Teacher ID is required.";

        } else {

            $sql = "DELETE FROM teacher
                    WHERE t_id = '$t_id'";

            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $message = "Teacher deleted successfully!";

                } else {

                    $message = "Teacher not found!";
                }

            } else {

                $message = "Delete failed: " . mysqli_error($conn);
            }
        }
    }


    /* =================================================
       UPDATE TEACHER
       ================================================= */

    elseif (isset($_POST["update_teacher"])) {


        /* ---------------------------------------------
           VALIDATION
           --------------------------------------------- */

        if ($t_id == "") {

            $errors["t_id"] = "Teacher ID is required.";
        }


        if ($trname == "") {

            $errors["trname"] = "Teacher name is required.";

        } elseif (!preg_match("/^[A-Za-z ]+$/", $trname)) {

            $errors["trname"] =
                "Teacher name should contain only letters and spaces.";
        }


        if ($email == "") {

            $errors["email"] = "Email is required.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $errors["email"] =
                "Please enter a valid email address.";
        }


        if ($mo_no == "") {

            $errors["mo_no"] =
                "Mobile number is required.";

        } elseif (!preg_match("/^[0-9]{10}$/", $mo_no)) {

            $errors["mo_no"] =
                "Mobile number must contain exactly 10 digits.";
        }


        if ($gender == "") {

            $errors["gender"] =
                "Please select gender.";
        }


        if ($quali == "") {

            $errors["quali"] =
                "Qualification is required.";
        }


        if ($sub_spec == "") {

            $errors["sub_spec"] =
                "Please select subject.";
        }


        if ($userName == "") {

            $errors["userName"] =
                "Username is required.";

        } elseif (strlen($userName) < 3) {

            $errors["userName"] =
                "Username must be at least 3 characters.";

        } elseif (strlen($userName) > 20) {

            $errors["userName"] =
                "Username cannot exceed 20 characters.";

        } elseif (!preg_match("/^[A-Za-z0-9_]+$/", $userName)) {

            $errors["userName"] =
                "Username can contain only letters, numbers and underscore.";
        }


        if ($p_word == "") {

            $errors["p_word"] =
                "Password is required.";

        } elseif (strlen($p_word) < 6) {

            $errors["p_word"] =
                "Password must be at least 6 characters.";

        } elseif (strlen($p_word) > 15) {

            $errors["p_word"] =
                "Password cannot exceed 15 characters.";
        }


        if ($addr == "") {

            $errors["addr"] =
                "Address is required.";
        }


        /* ---------------------------------------------
           UPDATE ONLY IF THERE ARE NO ERRORS
           --------------------------------------------- */

        if (empty($errors)) {

            if ($class_teacher == "") {

                $class_teacher_sql = "NULL";

            } else {

                $class_teacher_sql =
                    "'" . $class_teacher . "'";
            }


            $sql = "UPDATE teacher SET

                    trname = '$trname',
                    email = '$email',
                    userName = '$userName',
                    p_word = '$p_word',
                    sub_spec = '$sub_spec',
                    quali = '$quali',
                    gender = '$gender',
                    mo_no = '$mo_no',
                    addr = '$addr',
                    role = '$role',
                    class_teacher = $class_teacher_sql

                    WHERE t_id = '$t_id'";


            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $message =
                        "Teacher updated successfully!";

                } else {

                    $message =
                        "No changes were made.";
                }

            } else {

                $message =
                    "Update failed: " . mysqli_error($conn);
            }
        }
    }


    /* =================================================
       ADD TEACHER
       ================================================= */

    else {


        /* ---------------------------------------------
           VALIDATION
           --------------------------------------------- */

        if ($t_id == "") {

            $errors["t_id"] =
                "Teacher ID is required.";
        }


        if ($trname == "") {

            $errors["trname"] =
                "Teacher name is required.";

        } elseif (!preg_match("/^[A-Za-z ]+$/", $trname)) {

            $errors["trname"] =
                "Teacher name should contain only letters and spaces.";
        }


        if ($email == "") {

            $errors["email"] =
                "Email is required.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $errors["email"] =
                "Please enter a valid email address.";
        }


        if ($mo_no == "") {

            $errors["mo_no"] =
                "Mobile number is required.";

        } elseif (!preg_match("/^[0-9]{10}$/", $mo_no)) {

            $errors["mo_no"] =
                "Mobile number must contain exactly 10 digits.";
        }


        if ($gender == "") {

            $errors["gender"] =
                "Please select gender.";
        }


        if ($quali == "") {

            $errors["quali"] =
                "Qualification is required.";
        }


        if ($sub_spec == "") {

            $errors["sub_spec"] =
                "Please select subject.";
        }


        if ($userName == "") {

            $errors["userName"] =
                "Username is required.";

        } elseif (strlen($userName) < 3) {

            $errors["userName"] =
                "Username must be at least 3 characters.";

        } elseif (strlen($userName) > 20) {

            $errors["userName"] =
                "Username cannot exceed 20 characters.";

        } elseif (!preg_match("/^[A-Za-z0-9_]+$/", $userName)) {

            $errors["userName"] =
                "Username can contain only letters, numbers and underscore.";
        }


        if ($p_word == "") {

            $errors["p_word"] =
                "Password is required.";

        } elseif (strlen($p_word) < 6) {

            $errors["p_word"] =
                "Password must be at least 6 characters.";

        } elseif (strlen($p_word) > 15) {

            $errors["p_word"] =
                "Password cannot exceed 15 characters.";
        }


        if ($addr == "") {

            $errors["addr"] =
                "Address is required.";
        }


        /* ---------------------------------------------
           INSERT ONLY IF THERE ARE NO ERRORS
           --------------------------------------------- */

        if (empty($errors)) {

            if ($class_teacher == "") {

                $class_teacher_sql = "NULL";

            } else {

                $class_teacher_sql =
                    "'" . $class_teacher . "'";
            }


            $sql = "INSERT INTO teacher
                    (
                        t_id,
                        trname,
                        email,
                        userName,
                        p_word,
                        sub_spec,
                        quali,
                        gender,
                        mo_no,
                        addr,
                        role,
                        class_teacher
                    )

                    VALUES
                    (
                        '$t_id',
                        '$trname',
                        '$email',
                        '$userName',
                        '$p_word',
                        '$sub_spec',
                        '$quali',
                        '$gender',
                        '$mo_no',
                        '$addr',
                        '$role',
                        $class_teacher_sql
                    )";


            if (mysqli_query($conn, $sql)) {

                $message =
                    "Teacher added successfully!";

            } else {

                $message =
                    "Error: " . mysqli_error($conn);
            }
        }
    }
}


/* =====================================================
   SEARCH
   ===================================================== */

if (
    isset($_GET["search"]) &&
    $_GET["search"] != ""
) {

    $search = trim($_GET["search"]);

    $sql = "SELECT * FROM teacher
            WHERE t_id LIKE '%$search%'
            OR trname LIKE '%$search%'
            OR userName LIKE '%$search%'
            OR sub_spec LIKE '%$search%'";

} else {

    $sql = "SELECT * FROM teacher";
}


$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Teacher Management</title>


    <link rel="stylesheet" href="style.css">


    <style>

        .error-message {

            display: block;

            color: #ff2d2d;

            font-size: 14px;

            margin-top: 5px;

            min-height: 18px;

        }


        .input-error {

            border: 1px solid #ff2d2d !important;

        }


        .server-message {

            margin-top: 15px;

            margin-bottom: 15px;

            font-size: 16px;

        }

    </style>


    <script src="validation.js?v=teacher3"></script>

</head>


<body>


<div class="dashboard">


    <!-- =================================================
         SIDEBAR
         ================================================= -->

    <div class="sidebar">

        <h2>Student Grading System</h2>


        <a href="admin_dashboard.php">
            Dashboard
        </a>


        <a href="student.php">
            Students
        </a>


        <a href="teacher.php">
            Teachers
        </a>


        <a href="subject.php">
            Subjects
        </a>


        <a href="index.html">
            Logout
        </a>

    </div>


    <!-- =================================================
         MAIN CONTENT
         ================================================= -->

    <div class="main-content">

        <h1>Teacher Management</h1>

        <p>
            Manage teacher information and assignments
        </p>


        <!-- =================================================
             SERVER SUCCESS / DATABASE MESSAGE ONLY
             ================================================= -->

        <?php

        if ($message != "") {

            echo '<p class="server-message">'
                . htmlspecialchars($message)
                . '</p>';

        }

        ?>


        <!-- =================================================
             TEACHER FORM
             ================================================= -->

        <div class="form-box">

            <h2>Teacher Details</h2>


            <form
                method="POST"
                action="teacher.php"
                id="teacherForm"
            >


                <!-- =================================================
                     TEACHER ID + NAME
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Teacher ID
                        </label>


                        <input
                            type="text"
                            name="t_id"
                            id="t_id"
                            class="validate"
                            data-type="teacherid"
                            placeholder="Enter teacher ID"
                            value="<?php
                                echo htmlspecialchars($t_id ?? '');
                            ?>"
                        >


                        <span
                            id="t_idError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["t_id"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Teacher Name
                        </label>


                        <input
                            type="text"
                            name="trname"
                            id="trname"
                            class="validate"
                            data-type="fullname"
                            placeholder="Enter teacher name"
                            value="<?php
                                echo htmlspecialchars($trname ?? '');
                            ?>"
                        >


                        <span
                            id="trnameError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["trname"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     EMAIL + MOBILE
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Email
                        </label>


                        <input
                            type="text"
                            name="email"
                            id="email"
                            class="validate"
                            data-type="email"
                            placeholder="Enter email"
                            value="<?php
                                echo htmlspecialchars($email ?? '');
                            ?>"
                        >


                        <span
                            id="emailError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["email"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Mobile
                        </label>


                        <input
                            type="text"
                            name="mo_no"
                            id="mo_no"
                            class="validate"
                            data-type="mobile"
                            placeholder="Enter 10-digit mobile number"
                            maxlength="10"
                            inputmode="numeric"
                            value="<?php
                                echo htmlspecialchars($mo_no ?? '');
                            ?>"
                        >


                        <span
                            id="mo_noError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["mo_no"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     GENDER + QUALIFICATION
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Gender
                        </label>


                        <select
                            name="gender"
                            id="gender"
                            class="validate"
                            data-type="gender"
                        >

                            <option value="">
                                Select Gender
                            </option>


                            <option
                                value="Male"
                                <?php
                                if (($gender ?? '') == "Male")
                                    echo "selected";
                                ?>
                            >
                                Male
                            </option>


                            <option
                                value="Female"
                                <?php
                                if (($gender ?? '') == "Female")
                                    echo "selected";
                                ?>
                            >
                                Female
                            </option>


                            <option
                                value="Other"
                                <?php
                                if (($gender ?? '') == "Other")
                                    echo "selected";
                                ?>
                            >
                                Other
                            </option>

                        </select>


                        <span
                            id="genderError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["gender"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Qualification
                        </label>


                        <input
                            type="text"
                            name="quali"
                            id="quali"
                            class="validate"
                            data-type="qualification"
                            placeholder="Enter qualification"
                            value="<?php
                                echo htmlspecialchars($quali ?? '');
                            ?>"
                        >


                        <span
                            id="qualiError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["quali"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     SUBJECT + CLASS TEACHER
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Subject
                        </label>


                        <select
                            name="sub_spec"
                            id="sub_spec"
                            class="validate"
                            data-type="subject"
                        >

                            <option value="">
                                Select Subject
                            </option>


                            <option
                                value="Java"
                                <?php
                                if (($sub_spec ?? '') == "Java")
                                    echo "selected";
                                ?>
                            >
                                Java
                            </option>


                            <option
                                value="Cloud Computing"
                                <?php
                                if (($sub_spec ?? '') == "Cloud Computing")
                                    echo "selected";
                                ?>
                            >
                                Cloud Computing
                            </option>


                            <option
                                value="Software Engineering"
                                <?php
                                if (($sub_spec ?? '') == "Software Engineering")
                                    echo "selected";
                                ?>
                            >
                                Software Engineering
                            </option>


                            <option
                                value="UI/UX Design"
                                <?php
                                if (($sub_spec ?? '') == "UI/UX Design")
                                    echo "selected";
                                ?>
                            >
                                UI/UX Design
                            </option>

                        </select>


                        <span
                            id="sub_specError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["sub_spec"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Is Class Teacher?
                        </label>


                        <select
                            name="class_teacher"
                            id="class_teacher"
                        >

                            <option
                                value=""
                                <?php
                                if (($class_teacher ?? '') == "")
                                    echo "selected";
                                ?>
                            >
                                Not a Class Teacher
                            </option>


                            <option
                                value="TYBCA-A"
                                <?php
                                if (($class_teacher ?? '') == "TYBCA-A")
                                    echo "selected";
                                ?>
                            >
                                TYBCA-A
                            </option>


                            <option
                                value="TYBCA-B"
                                <?php
                                if (($class_teacher ?? '') == "TYBCA-B")
                                    echo "selected";
                                ?>
                            >
                                TYBCA-B
                            </option>

                        </select>

                    </div>

                </div>


                <!-- =================================================
                     USERNAME + PASSWORD
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Username
                        </label>


                        <input
                            type="text"
                            name="userName"
                            id="userName"
                            class="validate"
                            data-type="username"
                            placeholder="Enter username"
                            value="<?php
                                echo htmlspecialchars($userName ?? '');
                            ?>"
                        >


                        <span
                            id="userNameError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["userName"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Password
                        </label>


                        <input
                            type="password"
                            name="p_word"
                            id="p_word"
                            class="validate"
                            data-type="password"
                            placeholder="Enter password"
                            value="<?php
                                echo htmlspecialchars($p_word ?? '');
                            ?>"
                        >


                        <span
                            id="p_wordError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["p_word"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     ADDRESS
                     ================================================= -->

                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Address
                        </label>


                        <textarea
                            name="addr"
                            id="addr"
                            class="validate"
                            data-type="address"
                            placeholder="Enter teacher address"
                            rows="3"
                        ><?php
                            echo htmlspecialchars($addr ?? '');
                        ?></textarea>


                        <span
                            id="addrError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["addr"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- =================================================
                     BUTTONS
                     ================================================= -->

                <div class="form-buttons">


                    <button
                        type="submit"
                        class="save-button"
                    >
                        Add Teacher
                    </button>


                    <button
                        type="submit"
                        name="update_teacher"
                        class="update-button"
                    >
                        Update
                    </button>


                    <button
                        type="submit"
                        name="delete_teacher"
                        class="delete-button"
                    >
                        Delete
                    </button>


                    <button
                        type="reset"
                        class="clear-button"
                    >
                        Clear
                    </button>

                </div>


            </form>

        </div>


        <!-- =================================================
             SEARCH
             ================================================= -->

        <div class="search-box">


            <form
                method="GET"
                action="teacher.php"
            >

                <input
                    type="text"
                    name="search"
                    placeholder="Search by Teacher ID, Name or Subject"
                >


                <button
                    type="submit"
                    class="search-button"
                >
                    Search
                </button>

            </form>

        </div>


        <!-- =================================================
             TEACHER TABLE
             ================================================= -->

        <div class="table-box">

            <h2>Teacher List</h2>


            <table>

                <thead>

                    <tr>

                        <th>Teacher ID</th>

                        <th>Teacher Name</th>

                        <th>Email</th>

                        <th>Username</th>

                        <th>Qualification</th>

                        <th>Gender</th>

                        <th>Mobile</th>

                        <th>Subject</th>

                        <th>Class Teacher</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                while (
                    $row = mysqli_fetch_assoc($result)
                ) {

                ?>

                    <tr>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["t_id"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["trname"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["email"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["userName"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["quali"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["gender"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["mo_no"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row["sub_spec"]
                            );
                            ?>
                        </td>


                        <td>

                            <?php

                            if (
                                $row["class_teacher"] == NULL ||
                                $row["class_teacher"] == ""
                            ) {

                                echo "No";

                            } else {

                                echo htmlspecialchars(
                                    $row["class_teacher"]
                                );

                            }

                            ?>

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>


    </div>

</div>


</body>

</html>