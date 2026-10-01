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


// =====================================================
// GET DATA FROM URL
// =====================================================

$stud_id = $_GET["stud_id"] ?? "";
$class = $_GET["class"] ?? "";
$semester = $_GET["semester"] ?? "";


// =====================================================
// CHECK REQUIRED DATA
// =====================================================

if (
    empty($stud_id) ||
    empty($class) ||
    empty($semester)
) {

    die("Invalid result information.");

}


// =====================================================
// GRADE FUNCTION
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
// GET STUDENT INFORMATION
// =====================================================

$student_sql = "SELECT
                    stud_id,
                    stud_name,
                    class,
                    aca_yr,
                    divi
                FROM stud
                WHERE stud_id = '$stud_id'
                AND class = '$class'
                LIMIT 1";

$student_result = mysqli_query(
    $conn,
    $student_sql
);


if (
    !$student_result ||
    mysqli_num_rows($student_result) == 0
) {

    die("Student not found.");

}


$student = mysqli_fetch_assoc(
    $student_result
);


// =====================================================
// STUDENT DATA
// =====================================================

$student_name = $student["stud_name"];
$student_class = $student["class"];
$academic_year = $student["aca_yr"];
$division = $student["divi"];


// =====================================================
// GET MARKS AND SUBJECT INFORMATION
// =====================================================

$marks_sql = "SELECT
                m.subname,
                m.marks,
                s.max_mark,
                s.min_mark

              FROM marks m

              INNER JOIN sub s
              ON m.subname = s.subname

              WHERE m.stud_id = '$stud_id'
              AND m.class = '$class'
              AND s.semester = '$semester'

              ORDER BY m.subname";


$marks_result = mysqli_query(
    $conn,
    $marks_sql
);


if (!$marks_result) {

    die(
        "Unable to fetch result details: "
        . mysqli_error($conn)
    );

}


// =====================================================
// CALCULATE RESULT
// =====================================================

$subjects = [];

$total_marks = 0;
$total_max_marks = 0;

$final_status = "PASS";


while (
    $row = mysqli_fetch_assoc($marks_result)
) {

    $subject_name =
        $row["subname"];

    $obtained_marks =
        (int)$row["marks"];

    $max_mark =
        (int)$row["max_mark"];

    $min_mark =
        (int)$row["min_mark"];


    // -----------------------------------------------
    // SUBJECT PERCENTAGE
    // -----------------------------------------------

    if ($max_mark > 0) {

        $subject_percentage =
            ($obtained_marks / $max_mark) * 100;

    } else {

        $subject_percentage = 0;

    }


    // -----------------------------------------------
    // SUBJECT GRADE
    // -----------------------------------------------

    $subject_grade =
        calculateGrade(
            $subject_percentage
        );


    // -----------------------------------------------
    // SUBJECT STATUS
    // -----------------------------------------------

    if ($obtained_marks >= $min_mark) {

        $subject_status = "Pass";

    } else {

        $subject_status = "Fail";

        $final_status = "FAIL";

    }


    // -----------------------------------------------
    // STORE SUBJECT
    // -----------------------------------------------

    $subjects[] = [

        "subname" =>
            $subject_name,

        "marks" =>
            $obtained_marks,

        "max_mark" =>
            $max_mark,

        "grade" =>
            $subject_grade,

        "status" =>
            $subject_status

    ];


    // -----------------------------------------------
    // TOTAL
    // -----------------------------------------------

    $total_marks +=
        $obtained_marks;

    $total_max_marks +=
        $max_mark;

}


// =====================================================
// CHECK WHETHER MARKS EXIST
// =====================================================

if (count($subjects) == 0) {

    die(
        "No marks found for this student."
    );

}


// =====================================================
// OVERALL PERCENTAGE
// =====================================================

if ($total_max_marks > 0) {

    $percentage =
        ($total_marks / $total_max_marks) * 100;

} else {

    $percentage = 0;

}


// =====================================================
// OVERALL GRADE
// =====================================================

$overall_grade =
    calculateGrade($percentage);


// =====================================================
// GET SAVED RESULT IF AVAILABLE
// =====================================================

$result_sql = "SELECT *
               FROM result
               WHERE stud_id = '$stud_id'
               AND class = '$class'
               AND semester = '$semester'
               LIMIT 1";

$result_data =
    mysqli_query(
        $conn,
        $result_sql
    );


// If a saved result exists, use its final status.
// Otherwise use the calculated status.

if (
    $result_data &&
    mysqli_num_rows($result_data) > 0
) {

    $saved_result =
        mysqli_fetch_assoc($result_data);

    $final_status =
        strtoupper(
            $saved_result["status"]
        );

}


// =====================================================
// CURRENT DATE
// =====================================================

$result_date =
    date("d-m-Y");

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Student Result
    </title>


    <link
        rel="stylesheet"
        href="style.css"
    >


    <!-- =================================================
         PRINT CSS
         ================================================= -->

    <style>

        @media print {

            .print-button {
                display: none;
            }

            .result-page {
                width: 100%;
                margin: 0;
                padding: 20px;
            }

            body {
                background: white;
            }

        }

    </style>

</head>


<body>


<div class="result-page">


    <!-- =================================================
         HEADER
         ================================================= -->

    <div class="result-header">

        <h1>
            Vidya Pratishthan's Institute of
            Information Technology
        </h1>


        <h2>
            STUDENT RESULT
        </h2>

    </div>



    <!-- =================================================
         STUDENT INFORMATION
         ================================================= -->

    <div class="student-info">


        <div>

            <strong>
                Student ID:
            </strong>

            <span>

                <?php

                echo htmlspecialchars(
                    $stud_id
                );

                ?>

            </span>

        </div>


        <div>

            <strong>
                Student Name:
            </strong>

            <span>

                <?php

                echo htmlspecialchars(
                    $student_name
                );

                ?>

            </span>

        </div>


        <div>

            <strong>
                Course:
            </strong>

            <span>
                TYBCA
            </span>

        </div>


        <div>

            <strong>
                Semester:
            </strong>

            <span>

                Semester
                <?php

                echo htmlspecialchars(
                    $semester
                );

                ?>

            </span>

        </div>


        <div>

            <strong>
                Class:
            </strong>

            <span>

                <?php

                echo htmlspecialchars(
                    $student_class
                );

                ?>

            </span>

        </div>


        <div>

            <strong>
                Academic Year:
            </strong>

            <span>

                <?php

                echo htmlspecialchars(
                    $academic_year
                );

                ?>

            </span>

        </div>


        <div>

            <strong>
                Division:
            </strong>

            <span>

                <?php

                echo htmlspecialchars(
                    $division
                );

                ?>

            </span>

        </div>


    </div>



    <!-- =================================================
         MARKS TABLE
         ================================================= -->

    <table class="result-table">


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
            $subjects
            as $subject
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



    <!-- =================================================
         FINAL RESULT
         ================================================= -->

    <div class="final-result">


        <h3>
            Final Result
        </h3>


        <p>

            <strong>
                Total Marks:
            </strong>

            <?php

            echo $total_marks;

            ?>

            /

            <?php

            echo $total_max_marks;

            ?>

        </p>


        <p>

            <strong>
                Percentage:
            </strong>

            <?php

            echo number_format(
                $percentage,
                2
            );

            ?>%

        </p>


        <p>

            <strong>
                Overall Grade:
            </strong>

            <?php

            echo $overall_grade;

            ?>

        </p>


        <p>

            <strong>
                Final Status:
            </strong>

            <?php

            echo $final_status;

            ?>

        </p>


    </div>



    <!-- =================================================
         SIGNATURES
         ================================================= -->

    <div class="signatures">


        <div>

            <p>
                ________________________
            </p>

            <strong>
                Class Teacher
            </strong>

        </div>


        <div>

            <p>
                ________________________
            </p>

            <strong>
                Principal
            </strong>

        </div>


    </div>



    <!-- =================================================
         DATE
         ================================================= -->

    <div class="result-date">

        <p>

            <strong>
                Date:
            </strong>

            <?php

            echo $result_date;

            ?>

        </p>

    </div>



    <!-- =================================================
         PRINT BUTTON
         ================================================= -->

    <div class="print-button">

        <button onclick="window.print()">
            Print / Save as PDF
        </button>

    </div>

</div>


</body>

</html>