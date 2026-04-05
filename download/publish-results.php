<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
header("Location: teacher-login.php");
exit();
}


$generate = mysqli_query($conn,"
SELECT 
student_id,
exam_id,
SUM(marks_awarded) AS total_marks
FROM student_answers
GROUP BY student_id, exam_id
");

while($row = mysqli_fetch_assoc($generate)){

$student_id = $row['student_id'];
$exam_id = $row['exam_id'];
$total_marks = $row['total_marks'];

$check = mysqli_query($conn,"
SELECT * FROM results
WHERE student_id='$student_id'
AND exam_id='$exam_id'
");

$max_query = mysqli_query($conn,"
SELECT SUM(marks) AS max_marks 
FROM questions 
WHERE exam_id='$exam_id'
");

$max_row = mysqli_fetch_assoc($max_query);

$max_marks = $max_row['max_marks'];
if($max_marks > 0){
    $percentage = ($total_marks / $max_marks) * 100;
}else{
    $percentage = 0;
}
/* Grade Calculation */

if($percentage >= 90){
$grade = "S";
}
elseif($percentage >= 80){
$grade = "A";
}
elseif($percentage >= 70){
$grade = "B";
}
elseif($percentage >= 60){
$grade = "C";
}
elseif($percentage >= 50){
$grade = "D";
}
else{
$grade = "F";
}

if(mysqli_num_rows($check) == 0){

mysqli_query($conn,"INSERT INTO results
(student_id, exam_id, total_marks, max_marks, percentage, grade, is_published)
VALUES
('$student_id','$exam_id','$total_marks','$max_marks','$percentage','$grade',0)");

}
}
/* -----------------------------------
   Publish Result
----------------------------------- */

if(isset($_GET['publish'])){

$id = $_GET['publish'];

$res = mysqli_query($conn,"SELECT percentage FROM results WHERE id='$id'");
$data = mysqli_fetch_assoc($res);

$percentage = $data['percentage'];

/* Grade Calculation */

if($percentage >= 90){
$grade = "S";
}
elseif($percentage >= 80){
$grade = "A";
}
elseif($percentage >= 70){
$grade = "B";
}
elseif($percentage >= 60){
$grade = "C";
}
elseif($percentage >= 50){
$grade = "D";
}
else{
$grade = "F";
}

/* Update Result */

mysqli_query($conn,"
UPDATE results 
SET grade='$grade', is_published=1 
WHERE id='$id'
");

echo "<script>alert('Result Published Successfully'); window.location='publish-results.php';</script>";

}

/* -----------------------------------
   Fetch Results
----------------------------------- */

$query = mysqli_query($conn,"
SELECT results.*, students.name, exams.exam_name
FROM results
JOIN students ON results.student_id = students.id
JOIN exams ON results.exam_id = exams.id
ORDER BY results.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Publish Results</title>

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

.publish{
background:#000428;
color:white;
padding:6px 12px;
border-radius:5px;
text-decoration:none;
}

.published{
color:green;
font-weight:bold;
}

.back{
display:block;
text-align:center;
margin-top:20px;
text-decoration:none;
color:#000428;
}

</style>

</head>

<body>

<div class="container">

<h2>Publish Results</h2>

<table>

<tr>
<th>Student</th>
<th>Exam</th>
<th>Marks</th>
<th>Percentage</th>
<th>Grade</th>
<th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['exam_name']; ?></td>

<td><?php echo $row['total_marks']; ?> / <?php echo $row['max_marks']; ?></td>

<td><?php echo $row['percentage']; ?>%</td>

<td><?php echo $row['grade']; ?></td>

<td>

<?php if($row['is_published']==0){ ?>

<a class="publish" href="?publish=<?php echo $row['id']; ?>">Publish</a>

<?php } else { ?>

<span class="published">Published</span>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

<a class="back" href="teacher-dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>