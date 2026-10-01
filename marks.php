<?php

session_start();


// =====================================================
// TEACHER LOGIN CHECK
// =====================================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");
    exit();

}

include 'db.php';

$message = "";


// =====================================================
// SAVE / UPDATE MARKS
// =====================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $class = trim($_POST["class"] ?? "");
    $subname = trim($_POST["subname"] ?? "");

    // Student data
    $student_ids = $_POST["stud_id"] ?? [];
    $student_names = $_POST["stud_name"] ?? [];
    $student_marks = $_POST["marks"] ?? [];


    // =================================================
    // VALIDATE CLASS
    // =================================================

    $valid_classes = [
        "TYBCA-A",
        "TYBCA-B"
    ];

    if ($class == "") {

        $message = "Please select a class.";

    }

    elseif (!in_array($class, $valid_classes)) {

        $message = "Invalid class selected.";

    }


    // =================================================
    // VALIDATE SUBJECT
    // =================================================

    elseif ($subname == "") {

        $message = "Please select a subject.";

    }


    // =================================================
    // VALIDATE STUDENT ARRAYS
    // =================================================

    elseif (
        !is_array($student_ids) ||
        !is_array($student_names) ||
        !is_array($student_marks)
    ) {

        $message = "Invalid student data.";

    }

    elseif (
        count($student_ids) != count($student_names) ||
        count($student_ids) != count($student_marks)
    ) {

        $message = "Invalid marks data.";

    }

    elseif (count($student_ids) == 0) {

        $message = "No students available to enter marks.";

    }

    else {

        $success = true;


        // =================================================
        // VALIDATE EACH STUDENT
        // =================================================

        for ($i = 0; $i < count($student_ids); $i++) {

            $stud_id = trim($student_ids[$i]);
            $stud_name = trim($student_names[$i]);
            $marks = trim($student_marks[$i]);


            // ---------------------------------------------
            // Student ID validation
            // ---------------------------------------------

            if ($stud_id == "" || !ctype_digit($stud_id)) {

                $message = "Invalid Student ID.";
                $success = false;
                break;
            }


            // ---------------------------------------------
            // Student name validation
            // ---------------------------------------------

            if ($stud_name == "") {

                $message = "Student name cannot be empty.";
                $success = false;
                break;
            }


            // ---------------------------------------------
            // Marks empty validation
            // ---------------------------------------------

            if ($marks === "") {

                $message = "Please enter marks for all students.";
                $success = false;
                break;
            }


            // ---------------------------------------------
            // Marks numeric validation
            // ---------------------------------------------

            if (!ctype_digit($marks)) {

                $message = "Marks must be a whole number.";
                $success = false;
                break;
            }


            // Convert marks to integer
            $marks = (int)$marks;


            // ---------------------------------------------
            // Marks range validation
            // ---------------------------------------------

            if ($marks < 0 || $marks > 100) {

                $message = "Marks must be between 0 and 100.";
                $success = false;
                break;
            }


            // =================================================
            // CHECK WHETHER STUDENT EXISTS IN SELECTED CLASS
            // =================================================

            $student_check_sql = "SELECT stud_id
                                  FROM stud
                                  WHERE stud_id = '$stud_id'
                                  AND class = '$class'";

            $student_check_result =
                mysqli_query($conn, $student_check_sql);


            if (!$student_check_result) {

                $message =
                    "Error checking student: " .
                    mysqli_error($conn);

                $success = false;
                break;
            }


            if (mysqli_num_rows($student_check_result) == 0) {

                $message =
                    "Student ID " .
                    $stud_id .
                    " does not belong to " .
                    $class .
                    ".";

                $success = false;
                break;
            }


            // =================================================
            // CHECK EXISTING MARKS
            // =================================================

            $check_sql = "SELECT marks_id
                          FROM marks
                          WHERE stud_id = '$stud_id'
                          AND class = '$class'
                          AND subname = '$subname'";


            $check_result = mysqli_query(
                $conn,
                $check_sql
            );


            if (!$check_result) {

                $message =
                    "Error checking existing marks: " .
                    mysqli_error($conn);

                $success = false;
                break;
            }


            // =================================================
            // UPDATE EXISTING MARKS
            // =================================================

            if (mysqli_num_rows($check_result) > 0) {

                $sql = "UPDATE marks SET

                        stud_name = '$stud_name',
                        marks = '$marks'

                        WHERE stud_id = '$stud_id'
                        AND class = '$class'
                        AND subname = '$subname'";


            }

            // =================================================
            // INSERT NEW MARKS
            // =================================================

            else {

                $sql = "INSERT INTO marks
                        (
                            stud_id,
                            stud_name,
                            class,
                            subname,
                            marks
                        )

                        VALUES
                        (
                            '$stud_id',
                            '$stud_name',
                            '$class',
                            '$subname',
                            '$marks'
                        )";
            }


            // =================================================
            // EXECUTE QUERY
            // =================================================

            if (!mysqli_query($conn, $sql)) {

                $message =
                    "Error saving marks: " .
                    mysqli_error($conn);

                $success = false;
                break;
            }
        }


        // =================================================
        // SUCCESS MESSAGE
        // =================================================

        if ($success) {

            $message = "Marks saved successfully!";
        }

    }
}


// =====================================================
// LOAD SELECTED CLASS AND SUBJECT
// =====================================================

$selected_class = trim($_GET["class"] ?? "");
$selected_subject = trim($_GET["subname"] ?? "");

$students = [];


// =====================================================
// LOAD STUDENTS
// =====================================================

if (
    $selected_class != "" &&
    $selected_subject != ""
) {

    $student_sql = "SELECT *
                    FROM stud
                    WHERE class = '$selected_class'
                    ORDER BY stud_id";


    $student_result = mysqli_query(
        $conn,
        $student_sql
    );


    if ($student_result) {

        while ($row = mysqli_fetch_assoc($student_result)) {

            $students[] = $row;
        }

    } else {

        $message =
            "Error loading students: " .
            mysqli_error($conn);
    }
}


// =====================================================
// GET EXISTING MARKS
// =====================================================

$existing_marks = [];


if (
    $selected_class != "" &&
    $selected_subject != ""
) {

    $marks_sql = "SELECT *
                  FROM marks
                  WHERE class = '$selected_class'
                  AND subname = '$selected_subject'";


    $marks_result = mysqli_query(
        $conn,
        $marks_sql
    );


    if ($marks_result) {

        while ($row = mysqli_fetch_assoc($marks_result)) {

            $existing_marks[$row["stud_id"]] =
                $row["marks"];
        }

    } else {

        $message =
            "Error loading existing marks: " .
            mysqli_error($conn);
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

    <title>Marks Entry</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>


<div class="dashboard">


    <!-- =================================================
         SIDEBAR
         ================================================= -->

    <div class="sidebar">

        <h2>
            Student Grading System
        </h2>


        <a href="teacher_dashboard.php">
            Dashboard
        </a>


        <a href="marks.php" class="active">
            Marks
        </a>


        <a href="result.php">
            Results
        </a>


        <a href="teacher_logout.php" class="logout">
            Logout
        </a>

    </div>


    <!-- =================================================
         MAIN CONTENT
         ================================================= -->

    <div class="main-content">


        <h1>
            Marks Entry
        </h1>


        <p>
            Enter marks for students
        </p>


        <!-- =================================================
             MESSAGE
             ================================================= -->

        <?php

        if ($message != "") {

            echo "<p>" .
                 htmlspecialchars($message) .
                 "</p>";

        }

        ?>


        <!-- =================================================
             CLASS AND SUBJECT SELECTION
             ================================================= -->

        <div class="form-box">


            <h2>
                Select Class and Subject
            </h2>


            <form
                method="GET"
                action="marks.php"
            >


                <div class="form-row">


                    <!-- CLASS -->

                    <div class="form-group">

                        <label>
                            Class
                        </label>


                        <select
                            name="class"
                            required
                        >

                            <option value="">
                                Select Class
                            </option>


                            <option
                                value="TYBCA-A"
                                <?php

                                if (
                                    $selected_class ==
                                    "TYBCA-A"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                TYBCA-A
                            </option>


                            <option
                                value="TYBCA-B"
                                <?php

                                if (
                                    $selected_class ==
                                    "TYBCA-B"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                TYBCA-B
                            </option>


                        </select>

                    </div>


                    <!-- SUBJECT -->

                    <div class="form-group">

                        <label>
                            Subject
                        </label>


                        <select
                            name="subname"
                            required
                        >

                            <option value="">
                                Select Subject
                            </option>


                            <option
                                value="Java"
                                <?php

                                if (
                                    $selected_subject ==
                                    "Java"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                Java
                            </option>


                            <option
                                value="Cloud Computing"
                                <?php

                                if (
                                    $selected_subject ==
                                    "Cloud Computing"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                Cloud Computing
                            </option>


                            <option
                                value="Software Engineering"
                                <?php

                                if (
                                    $selected_subject ==
                                    "Software Engineering"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                Software Engineering
                            </option>


                            <option
                                value="UI/UX Design"
                                <?php

                                if (
                                    $selected_subject ==
                                    "UI/UX Design"
                                ) {

                                    echo "selected";
                                }

                                ?>
                            >
                                UI/UX Design
                            </option>


                        </select>

                    </div>


                    <!-- SEARCH -->

                    <div class="form-group search-button-group">

                        <label>
                            &nbsp;
                        </label>


                        <button
                            type="submit"
                            class="search-button"
                        >
                            Search
                        </button>

                    </div>


                </div>


            </form>


        </div>


        <!-- =================================================
             STUDENT MARKS TABLE
             ================================================= -->

        <?php

        if (
            !empty($selected_class) &&
            !empty($selected_subject)
        ) {

        ?>


        <div class="table-box">


            <h2>
                Student Marks
            </h2>


            <form
                method="POST"
                action="marks.php"
            >


                <!-- Hidden Class -->

                <input
                    type="hidden"
                    name="class"
                    value="<?php

                    echo htmlspecialchars(
                        $selected_class
                    );

                    ?>"
                >


                <!-- Hidden Subject -->

                <input
                    type="hidden"
                    name="subname"
                    value="<?php

                    echo htmlspecialchars(
                        $selected_subject
                    );

                    ?>"
                >


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
                                Marks
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    if (count($students) > 0) {


                        foreach (
                            $students as $student
                        ) {

                            $sid =
                                $student["stud_id"];

                            $sname =
                                $student["stud_name"];


                            // Existing marks
                            $current_marks = "";


                            if (
                                isset(
                                    $existing_marks[$sid]
                                )
                            ) {

                                $current_marks =
                                    $existing_marks[$sid];

                            }

                    ?>


                        <tr>


                            <!-- STUDENT ID -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $sid
                                );

                                ?>


                                <input
                                    type="hidden"
                                    name="stud_id[]"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $sid
                                    );

                                    ?>"
                                >

                            </td>


                            <!-- STUDENT NAME -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $sname
                                );

                                ?>


                                <input
                                    type="hidden"
                                    name="stud_name[]"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $sname
                                    );

                                    ?>"
                                >

                            </td>


                            <!-- MARKS -->

                            <td>

                                <input
                                    type="number"
                                    name="marks[]"
                                    class="marks-input"
                                    placeholder="Enter marks"
                                    min="0"
                                    max="100"
                                    step="1"
                                    value="<?php

                                    echo htmlspecialchars(
                                        $current_marks
                                    );

                                    ?>"
                                    required
                                >

                            </td>


                        </tr>


                    <?php

                        }

                    } else {

                    ?>


                        <tr>

                            <td colspan="3">

                                No students found for
                                <?php

                                echo htmlspecialchars(
                                    $selected_class
                                );

                                ?>.

                            </td>

                        </tr>


                    <?php

                    }

                    ?>


                    </tbody>


                </table>


                <!-- =================================================
                     BUTTONS
                     ================================================= -->

                <?php

                if (count($students) > 0) {

                ?>


                <div class="form-buttons">


                    <button
                        type="submit"
                        class="save-button"
                    >
                        Save Marks
                    </button>


                    <button
                        type="reset"
                        class="clear-button"
                    >
                        Clear
                    </button>


                </div>


                <?php

                }

                ?>


            </form>


        </div>


        <?php

        }

        ?>


    </div>


</div>


</body>

</html>