<?php
session_start();

if(!isset($_SESSION['student_id'])){
header("Location: student-login.php");
exit();
}

include("config.php");

$student_id = $_SESSION['student_id'];
$exam_id = $_GET['exam_id'];

/* Fetch exam */
$exam_query = mysqli_query($conn,"SELECT * FROM exams WHERE id='$exam_id'");
$exam = mysqli_fetch_assoc($exam_query);
$duration = $exam['duration'];

/* Fetch questions */
$question_query = mysqli_query($conn,"SELECT * FROM questions WHERE exam_id='$exam_id'");
$total_questions = mysqli_num_rows($question_query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Take Exam</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

/* Timer */

.timer{
position:fixed;
top:20px;
right:30px;
background:#ff4d4d;
color:white;
padding:12px 20px;
border-radius:8px;
font-weight:bold;
box-shadow:0 3px 10px rgba(0,0,0,0.2);
}

/* Exam box */

.exam-box{
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* Question Card */

.question-card{
background:#fff;
border-left:6px solid #007bff;
padding:20px;
margin-bottom:20px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

.question-header{
display:flex;
justify-content:space-between;
margin-bottom:10px;
}

.question-type{
background:#007bff;
color:white;
padding:3px 10px;
border-radius:6px;
font-size:12px;
}

.question-marks{
background:#eaf3ff;
color:#007bff;
padding:3px 10px;
border-radius:6px;
font-size:12px;
font-weight:bold;
}

/* options */

label{
display:block;
margin:8px 0;
cursor:pointer;
}

/* textarea */

textarea{
width:100%;
padding:10px;
border-radius:6px;
border:1px solid #ccc;
}

/* progress */

.progress-container{
margin-bottom:25px;
}

.progress-text{
font-weight:bold;
margin-bottom:5px;
}

.progress-bar{
width:100%;
height:12px;
background:#ddd;
border-radius:10px;
overflow:hidden;
}

.progress-fill{
height:100%;
width:0%;
background:#4da6ff;
transition:0.3s;
}

/* submit */

.submit-btn{
background:#007bff;
color:white;
padding:12px 25px;
border:none;
border-radius:8px;
cursor:pointer;
font-size:16px;
}

.submit-btn:hover{
background:#0056b3;
}

</style>
</head>

<body>

<div class="timer">
⏳ Time Left: <span id="timer"></span>
</div>

<div class="exam-box">

<h2><?php echo $exam['exam_name']; ?></h2>

<!-- Progress Bar -->

<div class="progress-container">

<div class="progress-text">
Progress: <span id="answered">0</span> / <?php echo $total_questions; ?> Questions
</div>

<div class="progress-bar">
<div class="progress-fill" id="progressFill"></div>
</div>

</div>

<form id="examForm" method="POST" action="submit-exam.php">

<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">

<?php 
$qno=1;
mysqli_data_seek($question_query,0);

while($q = mysqli_fetch_assoc($question_query)){

$question_id = $q['id'];
?>

<div class="question-card">

<div class="question-header">

<div class="question-type">
<?php echo strtoupper($q['question_type']); ?>
</div>

<div class="question-marks">
Marks: <?php echo $q['marks']; ?>
</div>

</div>

<p><b>Q<?php echo $qno++; ?>.</b> <?php echo $q['question_text']; ?></p>

<?php if(strtolower($q['question_type']) == "mcq"){ ?>

<label>
<input type="radio" name="answer[<?php echo $question_id; ?>]" value="A">
<?php echo $q['option_a']; ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $question_id; ?>]" value="B">
<?php echo $q['option_b']; ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $question_id; ?>]" value="C">
<?php echo $q['option_c']; ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $question_id; ?>]" value="D">
<?php echo $q['option_d']; ?>
</label>

<?php } else { ?>

<textarea name="answer[<?php echo $question_id; ?>]" rows="4" placeholder="Write your answer here..."></textarea>

<?php } ?>

</div>

<?php } ?>

<button class="submit-btn" type="submit">Submit Exam</button>

</form>

</div>

<script>

/* TIMER */

var duration = <?php echo $duration; ?> * 60;
var timer = duration;

var interval = setInterval(function(){

var minutes = Math.floor(timer / 60);
var seconds = timer % 60;

minutes = minutes < 10 ? "0"+minutes : minutes;
seconds = seconds < 10 ? "0"+seconds : seconds;

document.getElementById("timer").innerHTML = minutes + ":" + seconds;

timer--;

if(timer < 0){
clearInterval(interval);

alert("Time is up! Exam will be submitted.");

document.getElementById("examForm").submit();
}

},1000);


/* PROGRESS BAR */

var total = <?php echo $total_questions; ?>;

function updateProgress(){

var answered = 0;

document.querySelectorAll('input[type="radio"]:checked').forEach(function(){
answered++;
});

document.querySelectorAll('textarea').forEach(function(text){
if(text.value.trim() !== ""){
answered++;
}
});

document.getElementById("answered").innerText = answered;

var percent = (answered / total) * 100;

document.getElementById("progressFill").style.width = percent + "%";

}

document.querySelectorAll("input[type=radio]").forEach(function(radio){
radio.addEventListener("change", updateProgress);
});

document.querySelectorAll("textarea").forEach(function(text){
text.addEventListener("input", updateProgress);
});


/* AUTO SAVE */

setInterval(function(){

var formData = new FormData(document.getElementById("examForm"));

fetch("autosave.php",{
method:"POST",
body:formData
});

console.log("Autosaved");

},10000);


/* Disable refresh */

document.addEventListener("keydown", function(e) {
if (e.keyCode == 116) {
e.preventDefault();
}
});

/* Disable right click */

document.addEventListener("contextmenu", function(e){
e.preventDefault();
});

/* Tab switch warning */

document.addEventListener("visibilitychange", function(){
if(document.hidden){
alert("Warning: Do not switch tabs during exam!");
}
});

</script>

</body>
</html>