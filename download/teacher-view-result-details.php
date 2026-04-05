<?php
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$student_id = $_GET['student_id'];
$exam_id = $_GET['exam_id'];

/* STUDENT + EXAM INFO */
$info_query = "
SELECT students.name AS student_name, exams.exam_name
FROM students
JOIN student_answers ON students.id = student_answers.student_id
JOIN exams ON exams.id = student_answers.exam_id
WHERE students.id='$student_id' AND exams.id='$exam_id'
LIMIT 1
";
$info = mysqli_fetch_assoc(mysqli_query($conn,$info_query));

/* QUESTION DETAILS */
$query = "
SELECT 
questions.id AS question_id,
questions.question_text,
questions.option_a,
questions.option_b,
questions.option_c,
questions.option_d,
questions.correct_option,
questions.question_type,
questions.marks,
student_answers.answer_text,
student_answers.selected_option,
student_answers.marks_awarded
FROM student_answers
JOIN questions ON student_answers.question_id = questions.id
WHERE student_answers.student_id='$student_id'
AND student_answers.exam_id='$exam_id'
GROUP BY student_answers.question_id
ORDER BY student_answers.id ASC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher View Result Details</title>
<style>
body{
    font-family:Arial, sans-serif;
    background:#eef4ff;
    margin:0;
}
.container{
    width:85%;
    margin:auto;
    margin-top:30px;
}
h2{
    color:#1a3c7c;
}
.info-box{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    margin-bottom:25px;
}
.questions{
    display:flex;
    flex-direction:column;
    gap:20px;
}
.question-card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    border-left:5px solid #1a73e8;
}
.question{
    font-weight:bold;
    margin-bottom:10px;
}
.correct{
    color:green;
    font-weight:bold;
}
.student{
    color:#333;
}
.student-note{
    background:#f9f9f9;
    border-left:4px solid #007bff;
    padding:15px;
    border-radius:8px;
    margin:10px 0;
    font-family:Arial, sans-serif;
    color:#333;
    white-space: pre-wrap;
    line-height:1.5;
    max-height: calc(1.5em * 10 + 15px); /* 10 lines max + padding */
    overflow-y:auto;
}
.marks{
    font-weight:bold;
    color:#1a73e8;
    margin-top:8px;
}
.back-btn{
    display:inline-block;
    margin-bottom:20px;
    background:#007bff;
    color:white;
    padding:8px 16px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
}
.back-btn:hover{
    background:#0056b3;
}
</style>
</head>
<body>

<div class="container">

<!-- Back button to teacher's results page -->
<a class="back-btn" href="teacher-view-results.php?exam_id=<?php echo $exam_id; ?>">⬅ Back</a>

<h2>📄 Exam Result Details</h2>

<div class="info-box">
    <p><b>Student:</b> <?php echo $info['student_name']; ?></p>
    <p><b>Exam:</b> <?php echo $info['exam_name']; ?></p>
</div>

<div class="questions">
<?php while($row=mysqli_fetch_assoc($result)){ ?>
    <div class="question-card">
        <div class="question">
            Q: <?php echo $row['question_text']; ?>
        </div>

        <?php if(strtolower($row['question_type']) == "mcq"): 
            // Correct option text mapping
            $correct = "";
            switch(strtoupper($row['correct_option'])){
                case "A": $correct = $row['option_a']; break;
                case "B": $correct = $row['option_b']; break;
                case "C": $correct = $row['option_c']; break;
                case "D": $correct = $row['option_d']; break;
            }
        ?>
            <div class="correct">
                Correct Answer: <?php echo $correct; ?>
            </div>
            <div class="student">
                Student Answer: <?php echo $row['selected_option']; ?>
            </div>

        <?php else: ?>
            <!-- Descriptive answer -->
            <div class="student-note">
                <?php echo nl2br(htmlspecialchars($row['answer_text'])); ?>
            </div>
        <?php endif; ?>

        <div class="marks">
            Marks Awarded: <?php echo $row['marks_awarded']; ?> / <?php echo $row['marks']; ?>
        </div>
    </div>
<?php } ?>
</div>

</div>
</body>
</html>