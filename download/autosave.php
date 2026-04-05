<?php
session_start();
include("config.php");

$student_id = $_SESSION['student_id'];
$exam_id = $_POST['exam_id'];

foreach($_POST['answer'] as $question_id => $answer){

$selected_option = NULL;
$answer_text = NULL;

if(strlen($answer) == 1){
$selected_option = $answer;
}else{
$answer_text = mysqli_real_escape_string($conn,$answer);
}

mysqli_query($conn,"
REPLACE INTO temp_answers
(student_id,exam_id,question_id,selected_option,answer_text)
VALUES
('$student_id','$exam_id','$question_id','$selected_option','$answer_text')
");

}
?>