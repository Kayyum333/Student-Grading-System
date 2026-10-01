<?php

include 'db.php';

$message = "";
$errors = [];


// =====================================================
// ADD / UPDATE / DELETE SUBJECT
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $sub_id = trim($_POST["sub_id"] ?? "");
    $subname = trim($_POST["subname"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $semester = trim($_POST["semester"] ?? "");
    $max_mark = trim($_POST["max_mark"] ?? "");
    $min_mark = trim($_POST["min_mark"] ?? "");


    // =================================================
    // DELETE SUBJECT
    // =================================================

    if (isset($_POST["delete_subject"])) {

        // Delete only requires Subject ID
        if ($sub_id === "") {

            $errors["sub_id"] = "Subject ID is required.";

        } elseif (!preg_match("/^[0-9]+$/", $sub_id)) {

            $errors["sub_id"] = "Subject ID must contain numbers only.";

        } else {

            $sql = "DELETE FROM sub
                    WHERE sub_id = '$sub_id'";

            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $message = "Subject deleted successfully!";

                } else {

                    $message = "Subject not found!";
                }

            } else {

                $message = "Delete failed: " . mysqli_error($conn);
            }
        }
    }


    // =================================================
    // UPDATE SUBJECT
    // =================================================

    elseif (isset($_POST["update_subject"])) {

        // Subject ID
        if ($sub_id === "") {

            $errors["sub_id"] = "Subject ID is required.";

        } elseif (!preg_match("/^[0-9]+$/", $sub_id)) {

            $errors["sub_id"] = "Subject ID must contain numbers only.";
        }


        // Subject Name
        if ($subname === "") {

            $errors["subname"] = "Subject name is required.";

        } elseif (!preg_match("/^[A-Za-z ]+$/", $subname)) {

            $errors["subname"] =
                "Subject name must contain letters and spaces only.";
        }


        // Course
        if ($course === "") {

            $errors["course"] = "Please select a course.";
        }


        // Semester
        if ($semester === "") {

            $errors["semester"] = "Please select a semester.";

        } elseif (!in_array($semester, ["5", "6"])) {

            $errors["semester"] = "Please select a valid semester.";
        }


        // Maximum Marks
        if ($max_mark === "") {

            $errors["max_mark"] = "Maximum marks are required.";

        } elseif (!is_numeric($max_mark) || $max_mark <= 0) {

            $errors["max_mark"] =
                "Maximum marks must be greater than 0.";
        }


        // Passing Marks
        if ($min_mark === "") {

            $errors["min_mark"] = "Passing marks are required.";

        } elseif (!is_numeric($min_mark) || $min_mark <= 0) {

            $errors["min_mark"] =
                "Passing marks must be greater than 0.";
        }


        // Passing marks cannot exceed maximum marks
        if (
            $max_mark !== "" &&
            $min_mark !== "" &&
            is_numeric($max_mark) &&
            is_numeric($min_mark) &&
            $max_mark > 0 &&
            $min_mark > 0 &&
            $min_mark > $max_mark
        ) {

            $errors["min_mark"] =
                "Passing marks cannot be greater than maximum marks.";
        }


        // If there are no validation errors, update
        if (empty($errors)) {

            $sql = "UPDATE sub SET

                    subname = '$subname',
                    course = '$course',
                    semester = '$semester',
                    max_mark = '$max_mark',
                    min_mark = '$min_mark'

                    WHERE sub_id = '$sub_id'";


            if (mysqli_query($conn, $sql)) {

                if (mysqli_affected_rows($conn) > 0) {

                    $message = "Subject updated successfully!";

                } else {

                    $message = "No changes were made.";
                }

            } else {

                $message = "Update failed: " . mysqli_error($conn);
            }
        }
    }


    // =================================================
    // ADD SUBJECT
    // =================================================

    else {

        // Subject ID
        if ($sub_id === "") {

            $errors["sub_id"] = "Subject ID is required.";

        } elseif (!preg_match("/^[0-9]+$/", $sub_id)) {

            $errors["sub_id"] = "Subject ID must contain numbers only.";
        }


        // Subject Name
        if ($subname === "") {

            $errors["subname"] = "Subject name is required.";

        } elseif (!preg_match("/^[A-Za-z ]+$/", $subname)) {

            $errors["subname"] =
                "Subject name must contain letters and spaces only.";
        }


        // Course
        if ($course === "") {

            $errors["course"] = "Please select a course.";
        }


        // Semester
        if ($semester === "") {

            $errors["semester"] = "Please select a semester.";

        } elseif (!in_array($semester, ["5", "6"])) {

            $errors["semester"] = "Please select a valid semester.";
        }


        // Maximum Marks
        if ($max_mark === "") {

            $errors["max_mark"] = "Maximum marks are required.";

        } elseif (!is_numeric($max_mark) || $max_mark <= 0) {

            $errors["max_mark"] =
                "Maximum marks must be greater than 0.";
        }


        // Passing Marks
        if ($min_mark === "") {

            $errors["min_mark"] = "Passing marks are required.";

        } elseif (!is_numeric($min_mark) || $min_mark <= 0) {

            $errors["min_mark"] =
                "Passing marks must be greater than 0.";
        }


        // Passing marks cannot exceed maximum marks
        if (
            $max_mark !== "" &&
            $min_mark !== "" &&
            is_numeric($max_mark) &&
            is_numeric($min_mark) &&
            $max_mark > 0 &&
            $min_mark > 0 &&
            $min_mark > $max_mark
        ) {

            $errors["min_mark"] =
                "Passing marks cannot be greater than maximum marks.";
        }


        // If there are no validation errors, insert
        if (empty($errors)) {

            $sql = "INSERT INTO sub
                    (
                        sub_id,
                        subname,
                        course,
                        semester,
                        max_mark,
                        min_mark
                    )

                    VALUES
                    (
                        '$sub_id',
                        '$subname',
                        '$course',
                        '$semester',
                        '$max_mark',
                        '$min_mark'
                    )";


            if (mysqli_query($conn, $sql)) {

                $message = "Subject added successfully!";

            } else {

                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}


// =====================================================
// SEARCH SUBJECT
// =====================================================

if (isset($_GET["search"]) && $_GET["search"] != "") {

    $search = $_GET["search"];

    $sql = "SELECT * FROM sub

            WHERE sub_id LIKE '%$search%'
            OR subname LIKE '%$search%'
            OR course LIKE '%$search%'";

} else {

    $sql = "SELECT * FROM sub";
}


$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Subject Management</title>

    <link rel="stylesheet" href="style.css">

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

        <h1>Subject Management</h1>

        <p>Manage subject information</p>


        <!-- Successful database messages only -->

        <?php if ($message != "") { ?>

            <p><?php echo htmlspecialchars($message); ?></p>

        <?php } ?>


        <!-- =================================================
             SUBJECT FORM
             ================================================= -->

        <div class="form-box">

            <h2>Subject Details</h2>


            <form
                method="POST"
                action="subject.php"
                id="subjectForm"
                novalidate
            >


                <!-- Subject ID and Name -->

                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Subject ID
                        </label>

                        <input
                            type="text"
                            id="sub_id"
                            name="sub_id"
                            placeholder="Enter subject ID"
                            inputmode="numeric"
                            value="<?php echo htmlspecialchars($sub_id); ?>"
                        >

                        <span
                            id="sub_idError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["sub_id"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Subject Name
                        </label>

                        <input
                            type="text"
                            id="subname"
                            name="subname"
                            placeholder="Enter subject name"
                            value="<?php echo htmlspecialchars($subname); ?>"
                        >

                        <span
                            id="subnameError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["subname"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- Course and Semester -->

                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Course
                        </label>

                        <select
                            id="course"
                            name="course"
                        >

                            <option value="">
                                Select Course
                            </option>

                            <option
                                value="TYBCA"
                                <?php
                                echo ($course == "TYBCA")
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                TYBCA
                            </option>

                        </select>

                        <span
                            id="courseError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["course"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Semester
                        </label>

                        <select
                            id="semester"
                            name="semester"
                        >

                            <option value="">
                                Select Semester
                            </option>

                            <option
                                value="5"
                                <?php
                                echo ($semester == "5")
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Semester 5
                            </option>

                            <option
                                value="6"
                                <?php
                                echo ($semester == "6")
                                    ? "selected"
                                    : "";
                                ?>
                            >
                                Semester 6
                            </option>

                        </select>

                        <span
                            id="semesterError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["semester"] ?? ""
                            );
                            ?>
                        </span>

                    </div>

                </div>


                <!-- Maximum and Passing Marks -->

                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Maximum Marks
                        </label>

                        <input
                            type="number"
                            id="max_mark"
                            name="max_mark"
                            placeholder="Enter maximum marks"
                            inputmode="numeric"
                            value="<?php echo htmlspecialchars($max_mark); ?>"
                        >

                        <span
                            id="max_markError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["max_mark"] ?? ""
                            );
                            ?>
                        </span>

                    </div>


                    <div class="form-group">

                        <label>
                            Passing Marks
                        </label>

                        <input
                            type="number"
                            id="min_mark"
                            name="min_mark"
                            placeholder="Enter passing marks"
                            inputmode="numeric"
                            value="<?php echo htmlspecialchars($min_mark); ?>"
                        >

                        <span
                            id="min_markError"
                            class="error-message"
                        >
                            <?php
                            echo htmlspecialchars(
                                $errors["min_mark"] ?? ""
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
                        Add Subject
                    </button>


                    <button
                        type="submit"
                        name="update_subject"
                        class="update-button"
                    >
                        Update
                    </button>


                    <button
                        type="submit"
                        name="delete_subject"
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
                action="subject.php"
            >

                <input
                    type="text"
                    name="search"
                    placeholder="Search by Subject ID, Name or Course"
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
             SUBJECT TABLE
             ================================================= -->

        <div class="table-box">

            <h2>Subject List</h2>

            <table>

                <thead>

                    <tr>

                        <th>Subject ID</th>

                        <th>Subject Name</th>

                        <th>Course</th>

                        <th>Semester</th>

                        <th>Max Marks</th>

                        <th>Passing Marks</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                while ($row = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($row["sub_id"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["subname"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["course"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["semester"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["max_mark"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["min_mark"]); ?>
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


<script src="validation.js"></script>

</body>

</html>