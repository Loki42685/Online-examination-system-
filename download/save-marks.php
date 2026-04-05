<?php
include("config.php");

$marks = $_POST['marks_awarded'];
$id = $_POST['answer_id'];
$exam_id = $_POST['exam_id'];
$student_id = $_POST['student_id'];

mysqli_query($conn,"
UPDATE student_answers
SET marks_awarded='$marks'
WHERE id='$id'
");

header("Location: evaluate-paper.php?exam_id=$exam_id&student_id=$student_id");
?>