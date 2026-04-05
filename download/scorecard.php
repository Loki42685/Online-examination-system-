<?php
session_start();
include("config.php");

if(!isset($_SESSION['student_id'])){
header("Location: student-login.php");
exit();
}

$student_id = $_SESSION['student_id'];

/* Fetch student name */

$student = mysqli_query($conn,"SELECT name FROM students WHERE id='$student_id'");
$student_data = mysqli_fetch_assoc($student);
$name = $student_data['name'];

/* Fetch published results */

$query = mysqli_query($conn,"
SELECT results.*, exams.exam_name
FROM results
JOIN exams ON results.exam_id = exams.id
WHERE results.student_id='$student_id'
AND results.is_published=1
");
?>

<!DOCTYPE html>
<html>

<head>

<title>Scorecard</title>

<style>

body{
font-family:'Segoe UI',sans-serif;
background:#eef5ff;
padding:40px;
}

.container{
max-width:900px;
margin:auto;
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th, td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#000428;
color:white;
}

.download{
display:inline-block;
margin-top:20px;
background:#000428;
color:white;
padding:10px 18px;
border-radius:6px;
text-decoration:none;
}

.back{
display:block;
margin-top:20px;
text-align:center;
text-decoration:none;
color:#000428;
}

</style>

</head>

<body>

<div class="container">

<h2>Student Scorecard</h2>

<p><b>Name:</b> <?php echo $name; ?></p>
<p><b>Student ID:</b> <?php echo $student_id; ?></p>

<table>

<tr>
<th>Exam</th>
<th>Marks</th>
<th>Percentage</th>
<th>Grade</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>
<td><?php echo $row['exam_name']; ?></td>
<td><?php echo $row['total_marks']; ?> / <?php echo $row['max_marks']; ?></td>
<td><?php echo $row['percentage']; ?>%</td>
<td><?php echo $row['grade']; ?></td>
</tr>

<?php } ?>

</table>

<a class="download" href="download-scorecard.php">Download Full Scorecard PDF</a>

<a class="back" href="student-dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>