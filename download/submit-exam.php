<?php
session_start();
include("config.php");

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}
include("includes/ml_functions.php");
$student_id = $_SESSION['student_id'];
$exam_id = $_POST['exam_id'];
$answers = $_POST['answer']; // array: question_id => answer (A/B/C/D or text)

/* Loop through all submitted answers */
foreach($answers as $question_id => $answer){

    // Fetch question details
    $q = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM questions WHERE id='$question_id'"));
    $marks_awarded = 0;
    $answer_text = "";
    $selected_option = "";

    if(strtolower($q['question_type']) == "mcq"){
        $selected_option = strtoupper($answer); // store as A/B/C/D
        // Compare with correct option
        if(strtoupper($selected_option) == strtoupper($q['correct_option'])){
            $marks_awarded = $q['marks']; // full marks for correct
        } else {
            $marks_awarded = 0; // wrong answer
        }
    } else {
    $answer_text = $answer; // descriptive

    // AI Evaluation starts here
    $model_answer = $q['correct_answer']; // make sure this column exists
    $max_marks = $q['marks'];

    $marks_awarded = evaluateAnswer($answer_text, $model_answer, $max_marks);
	}

    // Insert into student_answers
    $stmt = $conn->prepare("INSERT INTO student_answers (student_id, exam_id, question_id, answer_text, selected_option, marks_awarded) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissi", $student_id, $exam_id, $question_id, $answer_text, $selected_option, $marks_awarded);
    $stmt->execute();
    $stmt->close();
}

/* After submission, redirect to exam dashboard or results page */
header("Location: available-exams.php");
exit();
?>