<?php
session_start();
if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}

include("config.php");

$student_id = $_SESSION['student_id'];

/* Fetch exams with teacher name */
$exam_query = "
SELECT exams.*, teachers.name AS teacher_name
FROM exams
JOIN teachers ON exams.teacher_id = teachers.id
";

$exam_result = mysqli_query($conn, $exam_query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Available Exams</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
font-family:'Segoe UI',sans-serif;
background:#f2f9ff;
margin:0;
padding:0;
}

.header-strip{
background:#4da6ff;
color:white;
text-align:center;
padding:25px 0;
font-size:28px;
font-weight:bold;
box-shadow:0 4px 8px rgba(0,0,0,0.1);
}

.exam-container{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:25px;
padding:40px;
}

.exam-card{
background:white;
padding:20px;
border-radius:12px;
box-shadow:0 10px 20px rgba(0,0,0,0.1);
transition:0.3s;
text-align:center;
min-height:240px;
display:flex;
flex-direction:column;
justify-content:space-between;
}

.exam-card:hover{
transform:translateY(-5px);
box-shadow:0 15px 25px rgba(0,0,0,0.2);
}

.exam-card h3{
color:#007acc;
margin-bottom:10px;
}

.exam-card p{
color:#555;
font-size:14px;
margin:5px 0;
}

.take-btn{
margin-top:10px;
background:#007bff;
color:white;
padding:10px;
border-radius:6px;
text-decoration:none;
display:inline-block;
font-weight:500;
}

.take-btn:hover{
background:#0056b3;
}

.completed{
margin-top:10px;
background:#28a745;
color:white;
padding:8px;
border-radius:6px;
font-size:14px;
display:inline-block;
}

.pending{
margin-top:10px;
background:#ffc107;
color:black;
padding:8px;
border-radius:6px;
font-size:14px;
}
.dashboard-btn{
display:block;
width:220px;
margin:30px auto 40px auto;
background:#1e3c72;
color:white;
text-align:center;
padding:12px;
border-radius:8px;
text-decoration:none;
font-weight:500;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
}

.dashboard-btn:hover{
background:#16325c;
}
@media(max-width:900px){
.exam-container{
grid-template-columns:repeat(2,1fr);
}
}

@media(max-width:600px){
.exam-container{
grid-template-columns:1fr;
}
}

</style>
</head>

<body>

<div class="header-strip">Available Exams</div>

<div class="exam-container">

<?php 
while($exam = mysqli_fetch_assoc($exam_result)) {

$exam_id = $exam['id'];

/* Get question count + exam type */
$qinfo = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total_questions, question_type
FROM questions
WHERE exam_id='$exam_id'
LIMIT 1
"));

$total_questions = $qinfo['total_questions'];
$exam_type = ucfirst($qinfo['question_type']);

/* Check if student already attempted */
$status_query = mysqli_query($conn,"
SELECT SUM(marks_awarded) AS total_marks,
COUNT(*) AS total_questions
FROM student_answers
WHERE student_id='$student_id'
AND exam_id='$exam_id'
");

$status = mysqli_fetch_assoc($status_query);

$attempted = $status['total_questions'] > 0;
$marks = $status['total_marks'];
?>

<div class="exam-card">

<h3><?php echo $exam['exam_name']; ?></h3>

<p><?php echo $exam['description']; ?></p>

<p><strong>Instructor:</strong> <?php echo $exam['teacher_name']; ?></p>

<p><strong>Duration:</strong> <?php echo $exam['duration']; ?> mins</p>

<p><strong>Type:</strong> <?php echo $exam_type; ?></p>

<p><strong>Questions:</strong> <?php echo $total_questions; ?></p>

<?php if(!$attempted){ ?>

<a class="take-btn" href="take-exam.php?exam_id=<?php echo $exam_id; ?>">
<i class="fa fa-pen"></i> Take Exam
</a>

<?php } elseif($marks == NULL){ ?>

<div class="pending">
<i class="fa fa-clock"></i> Pending Evaluation
</div>

<?php } else { ?>

<div class="completed">
<i class="fa fa-check"></i> Completed (Marks: <?php echo $marks; ?>)
</div>

<?php } ?>

</div>

<?php } ?>

</div>
<a class="dashboard-btn" href="student-dashboard.php">
<i class="fa fa-arrow-left"></i> Back to Dashboard
</a>

</body>
</html>