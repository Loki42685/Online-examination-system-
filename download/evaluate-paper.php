<?php
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$exam_id = $_GET['exam_id'];
$student_id = $_GET['student_id'];

// Fetch student answers along with question max marks
$query = mysqli_query($conn,"
SELECT sa.id, questions.question_text, sa.answer_text, sa.marks_awarded, questions.marks AS max_marks
FROM student_answers sa
JOIN questions ON sa.question_id = questions.id
WHERE sa.exam_id='$exam_id'
AND sa.student_id='$student_id'
");

$total_q = mysqli_num_rows($query);

$graded_query = mysqli_query($conn,"
SELECT COUNT(*) AS graded
FROM student_answers
WHERE exam_id='$exam_id'
AND student_id='$student_id'
AND marks_awarded IS NOT NULL
");

$graded_row = mysqli_fetch_assoc($graded_query);
$graded = $graded_row['graded'];

$progress = 0;
if($total_q > 0){
    $progress = ($graded / $total_q) * 100;
}

$already_graded = ($graded == $total_q);
?>

<!DOCTYPE html>
<html>
<head>
<title>Evaluate Paper</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f2f9ff;
    padding:40px;
}

h2{
    text-align:center;
    color:#1e3c72;
    margin-bottom:20px;
}

.progress-box{
    max-width:600px;
    margin:auto;
    margin-bottom:30px;
}

.progress-text{
    margin-bottom:8px;
    font-weight:bold;
}

.progress-bar{
    width:100%;
    background:#ddd;
    border-radius:20px;
    overflow:hidden;
}

.progress-fill{
    height:20px;
    background:#1e3c72;
    width:<?php echo $progress; ?>%;
}

.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

.question{
    font-weight:bold;
    color:#1e3c72;
    margin-bottom:10px;
}

.answer{
    background:#f5f5f5;
    padding:15px;
    border-radius:8px;
    margin-bottom:10px;
    max-height:200px;
    overflow:auto;
}

input[type=number]{
    padding:8px;
    width:80px;
    border:1px solid #ccc;
    border-radius:5px;
}

.save-btn{
    display:block;
    margin:auto;
    margin-top:30px;
    background:#1e3c72;
    color:white;
    padding:10px 25px;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

.save-btn:hover{
    background:#16325c;
}
</style>
</head>
<body>

<h2>Evaluate Student Paper</h2>

<div class="progress-box">
    <div class="progress-text">
        Evaluated: <?php echo $graded; ?> / <?php echo $total_q; ?> Questions
    </div>
    <div class="progress-bar">
        <div class="progress-fill"></div>
    </div>
</div>

<form method="POST" action="save-all-marks.php">

<?php while($row=mysqli_fetch_assoc($query)){ ?>
<div class="card">
    <div class="question">Question:</div>
    <?php echo $row['question_text']; ?>
    <br><br>
    <div class="question">Student Answer:</div>
    <div class="answer"><?php echo $row['answer_text']; ?></div>

    <div class="question">Max Marks: <?php echo $row['max_marks']; ?></div>
    <label>Marks Awarded:</label>
    <input type="number"
           name="marks[<?php echo $row['id']; ?>]"
           value="<?php echo $row['marks_awarded']; ?>"
           min="0"
           max="<?php echo $row['max_marks']; ?>"
           step="0.1"
           required>
</div>
<?php } ?>

<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
<input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

<?php if($already_graded){ ?>
<button class="save-btn">✏️ Edit Marks</button>
<?php } else { ?>
<button class="save-btn">Save Marks</button>
<?php } ?>

</form>

</body>
</html>