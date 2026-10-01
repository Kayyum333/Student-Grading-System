<?php

include 'db.php';

$serverErrors = [];
$successMessage = "";


// =====================================================
// FORM SUBMISSION
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $stud_id   = trim($_POST["stud_id"] ?? "");
    $stud_name = trim($_POST["stud_name"] ?? "");
    $email     = trim($_POST["email"] ?? "");
    $mo_no     = trim($_POST["mo_no"] ?? "");
    $gender    = trim($_POST["gender"] ?? "");
    $dob       = trim($_POST["dob"] ?? "");
    $aca_yr    = trim($_POST["aca_yr"] ?? "");
    $divi      = trim($_POST["divi"] ?? "");
    $class     = trim($_POST["class"] ?? "");
    $addr      = trim($_POST["addr"] ?? "");


    // =================================================
    // DELETE STUDENT
    // =================================================

    if (isset($_POST["delete_student"])) {

        if ($stud_id == "") {

            $serverErrors["stud_id"] =
                "Student ID is required.";

        } else {

            $sql = "DELETE FROM stud
                    WHERE stud_id = '$stud_id'";


            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $successMessage =
                        "Student deleted successfully!";

                } else {

                    $serverErrors["stud_id"] =
                        "Student not found.";
                }

            } else {

                $serverErrors["stud_id"] =
                    "Delete failed: " . mysqli_error($conn);
            }
        }
    }


    // =================================================
    // UPDATE STUDENT
    // =================================================

    elseif (isset($_POST["update_student"])) {


        // -----------------------------
        // Student ID
        // -----------------------------

        if ($stud_id == "") {

            $serverErrors["stud_id"] =
                "Student ID is required.";
        }


        // -----------------------------
        // Student Name
        // -----------------------------

        if ($stud_name == "") {

            $serverErrors["stud_name"] =
                "Student name is required.";

        }
        elseif (!preg_match("/^[A-Za-z ]+$/", $stud_name)) {

            $serverErrors["stud_name"] =
                "Student name should contain only letters and spaces.";
        }


        // -----------------------------
        // Email
        // -----------------------------

        if ($email == "") {

            $serverErrors["email"] =
                "Email is required.";

        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $serverErrors["email"] =
                "Please enter a valid email address.";
        }


        // -----------------------------
        // Mobile
        // -----------------------------

        if ($mo_no == "") {

            $serverErrors["mo_no"] =
                "Mobile number is required.";

        }
        elseif (!preg_match("/^[0-9]{10}$/", $mo_no)) {

            $serverErrors["mo_no"] =
                "Mobile number must contain exactly 10 digits.";
        }


        // -----------------------------
        // Gender
        // -----------------------------

        if ($gender == "") {

            $serverErrors["gender"] =
                "Please select gender.";
        }


        // -----------------------------
        // Date of Birth
        // -----------------------------

        if ($dob == "") {

            $serverErrors["dob"] =
                "Date of birth is required.";
        }


        // -----------------------------
        // Academic Year
        // -----------------------------

        if ($aca_yr == "") {

            $serverErrors["aca_yr"] =
                "Academic year is required.";
        }


        // -----------------------------
        // Division
        // -----------------------------

        if ($divi == "") {

            $serverErrors["divi"] =
                "Please select division.";
        }


        // -----------------------------
        // Class
        // -----------------------------

        if ($class == "") {

            $serverErrors["class"] =
                "Please select class.";
        }


        // -----------------------------
        // Address
        // -----------------------------

        if ($addr == "") {

            $serverErrors["addr"] =
                "Address is required.";
        }


        // -----------------------------
        // UPDATE IF VALID
        // -----------------------------

        if (empty($serverErrors)) {

            $sql = "UPDATE stud SET

                    stud_name = '$stud_name',
                    dob       = '$dob',
                    class     = '$class',
                    aca_yr    = '$aca_yr',
                    divi      = '$divi',
                    gender    = '$gender',
                    addr      = '$addr',
                    email     = '$email',
                    mo_no     = '$mo_no'

                    WHERE stud_id = '$stud_id'";


            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $successMessage =
                        "Student updated successfully!";

                } else {

                    $successMessage =
                        "No changes were made.";
                }

            } else {

                $serverErrors["stud_id"] =
                    "Update failed: " . mysqli_error($conn);
            }
        }
    }


    // =================================================
    // ADD STUDENT
    // =================================================

    else {


        // -----------------------------
        // Student ID
        // -----------------------------

        if ($stud_id == "") {

            $serverErrors["stud_id"] =
                "Student ID is required.";
        }


        // -----------------------------
        // Student Name
        // -----------------------------

        if ($stud_name == "") {

            $serverErrors["stud_name"] =
                "Student name is required.";

        }
        elseif (!preg_match("/^[A-Za-z ]+$/", $stud_name)) {

            $serverErrors["stud_name"] =
                "Student name should contain only letters and spaces.";
        }


        // -----------------------------
        // Email
        // -----------------------------

        if ($email == "") {

            $serverErrors["email"] =
                "Email is required.";

        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $serverErrors["email"] =
                "Please enter a valid email address.";
        }


        // -----------------------------
        // Mobile
        // -----------------------------

        if ($mo_no == "") {

            $serverErrors["mo_no"] =
                "Mobile number is required.";

        }
        elseif (!preg_match("/^[0-9]{10}$/", $mo_no)) {

            $serverErrors["mo_no"] =
                "Mobile number must contain exactly 10 digits.";
        }


        // -----------------------------
        // Gender
        // -----------------------------

        if ($gender == "") {

            $serverErrors["gender"] =
                "Please select gender.";
        }


        // -----------------------------
        // Date of Birth
        // -----------------------------

        if ($dob == "") {

            $serverErrors["dob"] =
                "Date of birth is required.";
        }


        // -----------------------------
        // Academic Year
        // -----------------------------

        if ($aca_yr == "") {

            $serverErrors["aca_yr"] =
                "Academic year is required.";
        }


        // -----------------------------
        // Division
        // -----------------------------

        if ($divi == "") {

            $serverErrors["divi"] =
                "Please select division.";
        }


        // -----------------------------
        // Class
        // -----------------------------

        if ($class == "") {

            $serverErrors["class"] =
                "Please select class.";
        }


        // -----------------------------
        // Address
        // -----------------------------

        if ($addr == "") {

            $serverErrors["addr"] =
                "Address is required.";
        }


        // -----------------------------
        // INSERT IF VALID
        // -----------------------------

        if (empty($serverErrors)) {

            $sql = "INSERT INTO stud
                    (
                        stud_id,
                        stud_name,
                        dob,
                        class,
                        aca_yr,
                        divi,
                        gender,
                        addr,
                        email,
                        mo_no
                    )

                    VALUES
                    (
                        '$stud_id',
                        '$stud_name',
                        '$dob',
                        '$class',
                        '$aca_yr',
                        '$divi',
                        '$gender',
                        '$addr',
                        '$email',
                        '$mo_no'
                    )";


            if (mysqli_query($conn, $sql)) {

                $successMessage =
                    "Student added successfully!";

            } else {

                // Duplicate Student ID
                if (mysqli_errno($conn) == 1062) {

                    $serverErrors["stud_id"] =
                        "This Student ID already exists.";

                } else {

                    $serverErrors["stud_id"] =
                        "Error: " . mysqli_error($conn);
                }
            }
        }
    }
}


// =====================================================
// FETCH STUDENTS
// =====================================================

if (
    isset($_GET["search"]) &&
    trim($_GET["search"]) != ""
) {

    $search = trim($_GET["search"]);


    $sql = "SELECT *
            FROM stud
            WHERE stud_id LIKE '%$search%'
            OR stud_name LIKE '%$search%'";

}
else {

    $sql = "SELECT *
            FROM stud";
}


$result = mysqli_query($conn, $sql);

?>


<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Student Management</title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>


<body>


<div class="dashboard">


    <!-- =================================================
         SIDEBAR
    ================================================== -->

    <div class="sidebar">

        <h2>
            Student Grading System
        </h2>


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
    ================================================== -->

    <div class="main-content">


        <h1>
            Student Management
        </h1>


        <p>
            Manage student information
        </p>


        <!-- =================================================
             SUCCESS MESSAGE
        ================================================== -->

        <?php

        if ($successMessage != "") {

        ?>

            <div class="success-message">

                <?php
                echo $successMessage;
                ?>

            </div>

        <?php

        }

        ?>


        <!-- =================================================
             STUDENT FORM
        ================================================== -->

        <div class="form-box">


            <h2>
                Student Details
            </h2>


            <form
                method="POST"
                action="student.php"
                id="studentForm"
            >


                <!-- =================================================
                     STUDENT ID AND NAME
                ================================================== -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Student ID
                        </label>


                        <input
                            type="text"
                            name="stud_id"
                            id="stud_id"
                            class="validate"
                            data-type="student_id"
                            placeholder="Enter student ID"
                            value="<?php
                                echo htmlspecialchars(
                                    $stud_id ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="stud_idError"
                        ><?php
                            echo $serverErrors["stud_id"] ?? "";
                        ?></small>


                    </div>


                    <div class="form-group">


                        <label>
                            Student Name
                        </label>


                        <input
                            type="text"
                            name="stud_name"
                            id="stud_name"
                            class="validate"
                            data-type="student_name"
                            placeholder="Enter student name"
                            value="<?php
                                echo htmlspecialchars(
                                    $stud_name ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="stud_nameError"
                        ><?php
                            echo $serverErrors["stud_name"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     EMAIL AND MOBILE
                ================================================== -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Email
                        </label>


                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="validate"
                            data-type="email"
                            placeholder="Enter email"
                            value="<?php
                                echo htmlspecialchars(
                                    $email ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="emailError"
                        ><?php
                            echo $serverErrors["email"] ?? "";
                        ?></small>


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
                                echo htmlspecialchars(
                                    $mo_no ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="mo_noError"
                        ><?php
                            echo $serverErrors["mo_no"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     GENDER AND DOB
                ================================================== -->

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
                                if (($gender ?? "") == "Male")
                                    echo "selected";
                                ?>
                            >
                                Male
                            </option>


                            <option
                                value="Female"
                                <?php
                                if (($gender ?? "") == "Female")
                                    echo "selected";
                                ?>
                            >
                                Female
                            </option>


                            <option
                                value="Other"
                                <?php
                                if (($gender ?? "") == "Other")
                                    echo "selected";
                                ?>
                            >
                                Other
                            </option>


                        </select>


                        <small
                            class="error-message"
                            id="genderError"
                        ><?php
                            echo $serverErrors["gender"] ?? "";
                        ?></small>


                    </div>


                    <div class="form-group">


                        <label>
                            Date of Birth
                        </label>


                        <input
                            type="date"
                            name="dob"
                            id="dob"
                            class="validate"
                            data-type="dob"
                            value="<?php
                                echo htmlspecialchars(
                                    $dob ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="dobError"
                        ><?php
                            echo $serverErrors["dob"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     ACADEMIC YEAR AND DIVISION
                ================================================== -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Academic Year
                        </label>


                        <input
                            type="text"
                            name="aca_yr"
                            id="aca_yr"
                            class="validate"
                            data-type="academic_year"
                            placeholder="Enter academic year"
                            value="<?php
                                echo htmlspecialchars(
                                    $aca_yr ?? ""
                                );
                            ?>"
                        >


                        <small
                            class="error-message"
                            id="aca_yrError"
                        ><?php
                            echo $serverErrors["aca_yr"] ?? "";
                        ?></small>


                    </div>


                    <div class="form-group">


                        <label>
                            Division
                        </label>


                        <select
                            name="divi"
                            id="divi"
                            class="validate"
                            data-type="division"
                        >


                            <option value="">
                                Select Division
                            </option>


                            <option
                                value="A"
                                <?php
                                if (($divi ?? "") == "A")
                                    echo "selected";
                                ?>
                            >
                                A
                            </option>


                            <option
                                value="B"
                                <?php
                                if (($divi ?? "") == "B")
                                    echo "selected";
                                ?>
                            >
                                B
                            </option>


                        </select>


                        <small
                            class="error-message"
                            id="diviError"
                        ><?php
                            echo $serverErrors["divi"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     CLASS
                ================================================== -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Class
                        </label>


                        <select
                            name="class"
                            id="class"
                            class="validate"
                            data-type="student_class"
                        >


                            <option value="">
                                Select Class
                            </option>


                            <option
                                value="TYBCA-A"
                                <?php
                                if (($class ?? "") == "TYBCA-A")
                                    echo "selected";
                                ?>
                            >
                                TYBCA-A
                            </option>


                            <option
                                value="TYBCA-B"
                                <?php
                                if (($class ?? "") == "TYBCA-B")
                                    echo "selected";
                                ?>
                            >
                                TYBCA-B
                            </option>


                        </select>


                        <small
                            class="error-message"
                            id="classError"
                        ><?php
                            echo $serverErrors["class"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     ADDRESS
                ================================================== -->

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
                            placeholder="Enter student address"
                            rows="3"
                        ><?php
                            echo htmlspecialchars(
                                $addr ?? ""
                            );
                        ?></textarea>


                        <small
                            class="error-message"
                            id="addrError"
                        ><?php
                            echo $serverErrors["addr"] ?? "";
                        ?></small>


                    </div>


                </div>


                <!-- =================================================
                     BUTTONS
                ================================================== -->

                <div class="form-buttons">


                    <button
                        type="submit"
                        class="save-button"
                    >
                        Add Student
                    </button>


                    <button
                        type="submit"
                        name="update_student"
                        class="update-button"
                    >
                        Update
                    </button>


                    <button
                        type="submit"
                        name="delete_student"
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
        ================================================== -->

        <div class="search-box">


            <form
                method="GET"
                action="student.php"
            >


                <input
                    type="text"
                    name="search"
                    placeholder="Search by Student ID or Name"
                    value="<?php
                        echo htmlspecialchars(
                            $_GET["search"] ?? ""
                        );
                    ?>"
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
             STUDENT TABLE
        ================================================== -->

        <div class="table-box">


            <h2>
                Student List
            </h2>


            <table>


                <thead>

                    <tr>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Student Name
                        </th>

                        <th>
                            Class
                        </th>

                        <th>
                            Academic Year
                        </th>

                        <th>
                            Division
                        </th>

                        <th>
                            Gender
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Mobile
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {

                    ?>


                        <tr>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["stud_id"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["stud_name"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["class"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["aca_yr"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["divi"]
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
                                    $row["email"]
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


                        </tr>


                    <?php

                    }

                    ?>


                </tbody>


            </table>


        </div>


    </div>

</div>


<!-- =====================================================
     VALIDATION JAVASCRIPT
====================================================== -->

<script src="validation.js"></script>


</body>

</html>