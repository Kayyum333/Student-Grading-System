<?php

session_start();


// =====================================================
// CHECK TEACHER LOGIN
// =====================================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");
    exit();

}

include 'db.php';

$message = "";


// =====================================================
// VARIABLES
// =====================================================

$stud_id = "";
$class = "";
$semester = "";

$student_name = "";

$subjects = [];

$total_marks = 0;
$total_max_marks = 0;

$percentage = 0;

$overall_grade = "";
$final_status = "";

$valid_request = true;


// =====================================================
// GET VALUES FROM URL
// =====================================================

if (isset($_GET["stud_id"])) {

    $stud_id = trim($_GET["stud_id"]);

}

if (isset($_GET["class"])) {

    $class = trim($_GET["class"]);

}

if (isset($_GET["semester"])) {

    $semester = trim($_GET["semester"]);

}


// =====================================================
// VALIDATE STUDENT ID
// =====================================================

if ($stud_id !== "") {

    if (!ctype_digit($stud_id)) {

        $message = "Student ID must contain numbers only.";

        $valid_request = false;

    } elseif ((int)$stud_id <= 0) {

        $message = "Student ID must be greater than 0.";

        $valid_request = false;

    }

}


// =====================================================
// VALIDATE CLASS
// =====================================================

$allowed_classes = [

    "TYBCA-A",
    "TYBCA-B"

];

if ($class !== "") {

    if (!in_array($class, $allowed_classes, true)) {

        $message = "Please select a valid class.";

        $valid_request = false;

    }

}


// =====================================================
// VALIDATE SEMESTER
// =====================================================

$allowed_semesters = [

    "5",
    "6"

];

if ($semester !== "") {

    if (!in_array($semester, $allowed_semesters, true)) {

        $message = "Please select a valid semester.";

        $valid_request = false;

    }

}


// =====================================================
// GRADE CALCULATION
// =====================================================

function calculateGrade($percentage)
{

    if ($percentage >= 90) {

        return "A+";

    } elseif ($percentage >= 80) {

        return "A";

    } elseif ($percentage >= 70) {

        return "B+";

    } elseif ($percentage >= 60) {

        return "B";

    } elseif ($percentage >= 50) {

        return "C";

    } elseif ($percentage >= 40) {

        return "D";

    } else {

        return "F";

    }

}


// =====================================================
// VIEW RESULT
// =====================================================

if (

    $valid_request &&
    !empty($stud_id) &&
    !empty($class) &&
    !empty($semester)

) {


    // =================================================
    // CHECK STUDENT
    // =================================================

    $student_sql = "

        SELECT stud_name

        FROM stud

        WHERE stud_id = '$stud_id'

        AND class = '$class'

    ";


    $student_result = mysqli_query(

        $conn,

        $student_sql

    );


    // =================================================
    // DATABASE ERROR
    // =================================================

    if (!$student_result) {

        $message = "Unable to fetch student information.";

    }


    // =================================================
    // STUDENT NOT FOUND
    // =================================================

    elseif (mysqli_num_rows($student_result) == 0) {

        $message =

            "Student ID does not belong to the selected class.";

    }


    // =================================================
    // STUDENT FOUND
    // =================================================

    else {


        $student_row = mysqli_fetch_assoc(

            $student_result

        );


        $student_name = $student_row["stud_name"];


        // =============================================
        // FETCH MARKS AND SUBJECT INFORMATION
        // =============================================

        $marks_sql = "

            SELECT

                m.stud_id,
                m.stud_name,
                m.class,
                m.subname,
                m.marks,

                s.semester,
                s.max_mark,
                s.min_mark

            FROM marks m

            INNER JOIN sub s

            ON m.subname = s.subname

            WHERE m.stud_id = '$stud_id'

            AND m.class = '$class'

            AND s.semester = '$semester'

            ORDER BY m.subname

        ";


        $marks_result = mysqli_query(

            $conn,

            $marks_sql

        );


        // =============================================
        // DATABASE ERROR
        // =============================================

        if (!$marks_result) {

            $message = "Unable to fetch marks.";

        }


        // =============================================
        // NO MARKS FOUND
        // =============================================

        elseif (mysqli_num_rows($marks_result) == 0) {

            $message =

                "No marks found for this student, class and semester.";

        }


        // =============================================
        // MARKS FOUND
        // =============================================

        else {


            while (

                $row = mysqli_fetch_assoc(

                    $marks_result

                )

            ) {


                $subject_name =

                    $row["subname"];


                $obtained_marks =

                    (int)$row["marks"];


                $maximum_marks =

                    (int)$row["max_mark"];


                $passing_marks =

                    (int)$row["min_mark"];


                // =====================================
                // VALIDATE MARKS
                // =====================================

                if ($maximum_marks <= 0) {

                    continue;

                }


                if (

                    $obtained_marks < 0 ||

                    $obtained_marks > $maximum_marks

                ) {

                    continue;

                }


                // =====================================
                // SUBJECT PERCENTAGE
                // =====================================

                $subject_percentage =

                    (

                        $obtained_marks /

                        $maximum_marks

                    ) * 100;


                // =====================================
                // SUBJECT GRADE
                // =====================================

                $subject_grade =

                    calculateGrade(

                        $subject_percentage

                    );


                // =====================================
                // SUBJECT STATUS
                // =====================================

                if (

                    $obtained_marks >=

                    $passing_marks

                ) {

                    $subject_status = "Pass";

                } else {

                    $subject_status = "Fail";

                }


                // =====================================
                // STORE SUBJECT
                // =====================================

                $subjects[] = [

                    "subname" =>

                        $subject_name,

                    "marks" =>

                        $obtained_marks,

                    "max_mark" =>

                        $maximum_marks,

                    "min_mark" =>

                        $passing_marks,

                    "grade" =>

                        $subject_grade,

                    "status" =>

                        $subject_status

                ];


                // =====================================
                // TOTAL MARKS
                // =====================================

                $total_marks +=

                    $obtained_marks;


                $total_max_marks +=

                    $maximum_marks;

            }


            // =========================================
            // NO VALID SUBJECT RECORDS
            // =========================================

            if (count($subjects) == 0) {

                $message =

                    "No valid subject marks found.";

            }


            // =========================================
            // CALCULATE FINAL RESULT
            // =========================================

            else {


                if ($total_max_marks > 0) {

                    $percentage =

                        (

                            $total_marks /

                            $total_max_marks

                        ) * 100;

                } else {

                    $percentage = 0;

                }


                // =====================================
                // OVERALL GRADE
                // =====================================

                $overall_grade =

                    calculateGrade(

                        $percentage

                    );


                // =====================================
                // FINAL STATUS
                // =====================================

                $final_status = "Pass";


                foreach (

                    $subjects as $subject

                ) {

                    if (

                        $subject["status"] == "Fail"

                    ) {

                        $final_status = "Fail";

                        break;

                    }

                }


                // =====================================
                // CHECK EXISTING RESULT
                // =====================================

                $check_result_sql = "

                    SELECT result_id

                    FROM result

                    WHERE stud_id = '$stud_id'

                    AND class = '$class'

                    AND semester = '$semester'

                ";


                $check_result = mysqli_query(

                    $conn,

                    $check_result_sql

                );


                if (!$check_result) {

                    $message =

                        "Unable to check existing result.";

                }


                // =====================================
                // RESULT ALREADY EXISTS
                // =====================================

                elseif (

                    mysqli_num_rows(

                        $check_result

                    ) > 0

                ) {


                    $percentage_value =

                        number_format(

                            $percentage,

                            2,

                            '.',

                            ''

                        );


                    $update_sql = "

                        UPDATE result

                        SET

                            sname = '$student_name',

                            total_mark = '$total_marks',

                            percent = '$percentage_value',

                            grade = '$overall_grade',

                            status = '$final_status'

                        WHERE stud_id = '$stud_id'

                        AND class = '$class'

                        AND semester = '$semester'

                    ";


                    if (!mysqli_query(

                        $conn,

                        $update_sql

                    )) {

                        $message =

                            "Unable to update result.";

                    }

                }


                // =====================================
                // NEW RESULT
                // =====================================

                else {


                    $percentage_value =

                        number_format(

                            $percentage,

                            2,

                            '.',

                            ''

                        );


                    $insert_sql = "

                        INSERT INTO result

                        (

                            stud_id,

                            sname,

                            class,

                            semester,

                            total_mark,

                            percent,

                            grade,

                            status

                        )

                        VALUES

                        (

                            '$stud_id',

                            '$student_name',

                            '$class',

                            '$semester',

                            '$total_marks',

                            '$percentage_value',

                            '$overall_grade',

                            '$final_status'

                        )

                    ";


                    if (!mysqli_query(

                        $conn,

                        $insert_sql

                    )) {

                        $message =

                            "Unable to save result.";

                    }

                }

            }

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

    <title>Student Result</title>

    <link

        rel="stylesheet"

        href="style.css"

    >

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


        <a href="marks.php">
            Marks
        </a>


        <a

            href="result.php"

            class="active"

        >
            Results
        </a>


        <a

            href="teacher_logout.php"

            class="logout"

        >
            Logout
        </a>

    </div>



    <!-- =================================================
         MAIN CONTENT
         ================================================= -->

    <div class="main-content">


        <h1>
            Student Result
        </h1>


        <p>
            View student marks and final results
        </p>


        <!-- =================================================
             ERROR MESSAGE
             ================================================= -->

        <?php

        if ($message != "") {

        ?>

            <div class="error">

                <?php

                echo htmlspecialchars($message);

                ?>

            </div>

        <?php

        }

        ?>


        <!-- =================================================
             STUDENT INFORMATION
             ================================================= -->

        <div class="form-box">


            <h2>
                Student Information
            </h2>


            <form

                method="GET"

                action="result.php"

            >


                <!-- Student ID + Name -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Student ID
                        </label>


                        <input

                            type="text"

                            name="stud_id"

                            placeholder="Enter student ID"

                            value="<?php

                                echo htmlspecialchars(

                                    $stud_id

                                );

                            ?>"

                            required

                            inputmode="numeric"

                            pattern="[0-9]+"

                            title="Student ID must contain numbers only"

                        >


                    </div>



                    <div class="form-group">


                        <label>
                            Student Name
                        </label>


                        <input

                            type="text"

                            value="<?php

                                echo htmlspecialchars(

                                    $student_name

                                );

                            ?>"

                            placeholder="Student name"

                            readonly

                        >


                    </div>


                </div>



                <!-- Class + Semester -->

                <div class="form-row">


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

                                    $class == "TYBCA-A"

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

                                    $class == "TYBCA-B"

                                ) {

                                    echo "selected";

                                }

                                ?>

                            >

                                TYBCA-B

                            </option>


                        </select>


                    </div>



                    <div class="form-group">


                        <label>
                            Semester
                        </label>


                        <select

                            name="semester"

                            required

                        >


                            <option value="">
                                Select Semester
                            </option>


                            <option

                                value="5"

                                <?php

                                if (

                                    $semester == "5"

                                ) {

                                    echo "selected";

                                }

                                ?>

                            >

                                Semester 5

                            </option>


                            <option

                                value="6"

                                <?php

                                if (

                                    $semester == "6"

                                ) {

                                    echo "selected";

                                }

                                ?>

                            >

                                Semester 6

                            </option>


                        </select>


                    </div>


                </div>



                <!-- Buttons -->

                <div class="form-buttons">


                    <button

                        type="submit"

                        class="view-button"

                    >

                        View Result

                    </button>


                    <button

                        type="reset"

                        class="clear-button"

                        onclick="window.location='result.php'; return false;"

                    >

                        Clear

                    </button>


                </div>


            </form>


        </div>



        <!-- =================================================
             RESULT DETAILS
             ================================================= -->

        <?php

        if (

            !empty($stud_id) &&

            !empty($class) &&

            !empty($semester) &&

            count($subjects) > 0

        ) {

        ?>


        <div class="table-box">


            <h2>
                Result Details
            </h2>


            <table>


                <thead>


                    <tr>


                        <th>
                            Subject
                        </th>


                        <th>
                            Marks
                        </th>


                        <th>
                            Grade
                        </th>


                        <th>
                            Status
                        </th>


                    </tr>


                </thead>


                <tbody>


                <?php

                foreach (

                    $subjects as $subject

                ) {

                ?>


                    <tr>


                        <td>

                            <?php

                            echo htmlspecialchars(

                                $subject["subname"]

                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $subject["marks"];

                            ?>

                            /

                            <?php

                            echo $subject["max_mark"];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $subject["grade"];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $subject["status"];

                            ?>

                        </td>


                    </tr>


                <?php

                }

                ?>


                </tbody>


            </table>


        </div>



        <!-- =================================================
             FINAL RESULT
             ================================================= -->

        <div class="form-box">


            <h2>
                Final Result
            </h2>


            <!-- Total + Percentage -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Total Marks
                    </label>


                    <input

                        type="text"

                        value="<?php

                            echo $total_marks .

                                 " / " .

                                 $total_max_marks;

                        ?>"

                        readonly

                    >


                </div>



                <div class="form-group">


                    <label>
                        Percentage
                    </label>


                    <input

                        type="text"

                        value="<?php

                            echo number_format(

                                $percentage,

                                2

                            ) . "%";

                        ?>"

                        readonly

                    >


                </div>


            </div>



            <!-- Overall Grade + Status -->

            <div class="form-row">


                <div class="form-group">


                    <label>
                        Overall Grade
                    </label>


                    <input

                        type="text"

                        value="<?php

                            echo htmlspecialchars(

                                $overall_grade

                            );

                        ?>"

                        readonly

                    >


                </div>



                <div class="form-group">


                    <label>
                        Final Status
                    </label>


                    <input

                        type="text"

                        value="<?php

                            echo htmlspecialchars(

                                $final_status

                            );

                        ?>"

                        readonly

                    >


                </div>


            </div>



            <!-- Print -->

            <div class="form-buttons">


                <a

                    href="print_result.php?stud_id=<?php

                        echo urlencode($stud_id);

                    ?>&class=<?php

                        echo urlencode($class);

                    ?>&semester=<?php

                        echo urlencode($semester);

                    ?>"

                    class="print-result-button"

                >

                    Print Result

                </a>


            </div>


        </div>


        <?php

        }

        ?>


    </div>


</div>


</body>

</html>