<?php
include("config.php");

$exam_id = $_POST['exam_id'];
$student_id = $_POST['student_id'];

foreach($_POST['marks'] as $answer_id => $marks){

mysqli_query($conn,"
UPDATE student_answers
SET marks_awarded='$marks'
WHERE id='$answer_id'
");

}

header("Location: evaluate-students.php?exam_id=$exam_id&student_id=$student_id");
?>