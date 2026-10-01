<?php

session_start();


// =====================================================
// CHECK TEACHER LOGIN
// =====================================================

if (!isset($_SESSION["teacher_id"])) {

    header("Location: teacher_login.php");
    exit();

}


// =====================================================
// GET TEACHER INFORMATION FROM SESSION
// =====================================================

$teacher_name = $_SESSION["teacher_name"];
$teacher_subject = $_SESSION["teacher_subject"];
$teacher_class_teacher = $_SESSION["teacher_class_teacher"];

?>


<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Teacher Dashboard</title>

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


        <a href="marks.php">
            Marks
        </a>


        <a href="result.php">
            Results
        </a>


        <a href="teacher_logout.php"
           class="logout">
            Logout
        </a>

    </div>


    <!-- =================================================
         MAIN CONTENT
         ================================================= -->

    <div class="main-content">


        <h1>
            Welcome, <?php echo htmlspecialchars($teacher_name); ?>
        </h1>


        <p>
            Teacher Dashboard
        </p>


        <!-- =================================================
             TEACHER INFORMATION
             ================================================= -->

        <div class="form-box">


            <h2>
                Teacher Information
            </h2>


            <div class="form-row">


                <div class="form-group">

                    <label>
                        Teacher Name
                    </label>


                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($teacher_name); ?>"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>
                        Subject
                    </label>


                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($teacher_subject); ?>"
                        readonly
                    >

                </div>


            </div>


            <div class="form-row">


                <div class="form-group">

                    <label>
                        Class Teacher
                    </label>


                    <input
                        type="text"
                        value="<?php

                        if (
                            empty($teacher_class_teacher)
                        ) {

                            echo "No";

                        } else {

                            echo htmlspecialchars(
                                $teacher_class_teacher
                            );

                        }

                        ?>"
                        readonly
                    >

                </div>


            </div>


        </div>


        <!-- =================================================
             DASHBOARD CARDS
             ================================================= -->

        <div class="cards">


            <div class="card">

                <h3>
                    Subject
                </h3>

                <p>
                    <?php
                    echo htmlspecialchars($teacher_subject);
                    ?>
                </p>

            </div>


            <div class="card">

                <h3>
                    Class Teacher
                </h3>

                <p>

                    <?php

                    if (empty($teacher_class_teacher)) {

                        echo "No";

                    } else {

                        echo htmlspecialchars(
                            $teacher_class_teacher
                        );

                    }

                    ?>

                </p>

            </div>


            <div class="card">

                <h3>
                    Marks Entry
                </h3>

                <p>
                    <a href="marks.php">
                        Enter Marks
                    </a>
                </p>

            </div>


        </div>


    </div>


</div>


</body>

</html>