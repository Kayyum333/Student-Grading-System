<?php

include "db.php";


// Count students
$student_sql = "SELECT COUNT(*) AS total_students FROM stud";

$student_result = mysqli_query($conn, $student_sql);

$student_data = mysqli_fetch_assoc($student_result);

$total_students = $student_data["total_students"];


// Count teachers
$teacher_sql = "SELECT COUNT(*) AS total_teachers FROM teacher";

$teacher_result = mysqli_query($conn, $teacher_sql);

$teacher_data = mysqli_fetch_assoc($teacher_result);

$total_teachers = $teacher_data["total_teachers"];


// Count subjects
$subject_sql = "SELECT COUNT(*) AS total_subjects FROM sub";

$subject_result = mysqli_query($conn, $subject_sql);

$subject_data = mysqli_fetch_assoc($subject_result);

$total_subjects = $subject_data["total_subjects"];

?>

<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>

    <div class="dashboard">


        <!-- Sidebar -->

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



        <!-- Main Content -->

        <div class="main-content">

            <h1>Welcome, Admin</h1>

            <p>Manage the Student Grading System</p>



            <!-- Dashboard Cards -->

            <div class="cards">


                <!-- Students -->

                <div class="card">

                    <h3>Students</h3>

                    <p>
                        <?php echo $total_students; ?>
                    </p>

                </div>



                <!-- Teachers -->

                <div class="card">

                    <h3>Teachers</h3>

                    <p>
                        <?php echo $total_teachers; ?>
                    </p>

                </div>



                <!-- Subjects -->

                <div class="card">

                    <h3>Subjects</h3>

                    <p>
                        <?php echo $total_subjects; ?>
                    </p>

                </div>


            </div>

        </div>

    </div>

</body>

</html>