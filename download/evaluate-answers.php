<?php
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch descriptive exams and number of students per exam
$query = mysqli_query($conn,"
SELECT exams.id, exams.exam_name, COUNT(DISTINCT student_answers.student_id) AS student_count
FROM exams
JOIN questions ON exams.id = questions.exam_id
LEFT JOIN student_answers ON exams.id = student_answers.exam_id
WHERE exams.teacher_id='$teacher_id' AND questions.question_type='descriptive'
GROUP BY exams.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Evaluate Answers</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    background: #eef4ff;
    padding: 40px 30px;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    background: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}
.back-btn:hover {
    background: #0056b3;
}

/* Header strip */
.header-strip {
    background: #1a3c7c;
    color: white;
    padding: 15px 20px;
    font-size: 1.4em;
    font-weight: bold;
    margin-bottom: 30px;
    border-radius: 6px;
}

/* Container for exam cards */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
}

/* Exam card */
.card {
    background: white;
    border-left: 6px solid #1a3c7c;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

/* Exam name blue header */
.card h3 {
    background: #1a3c7c;
    color: white;
    margin: 0;
    padding: 12px 0;
    font-size: 1.1em;
}

/* Student count */
.card p {
    font-weight: bold;
    color: #333;
    margin: 15px 0;
}

/* View students button */
.card a {
    display: inline-block;
    text-decoration: none;
    background: #1a3c7c;
    color: white;
    padding: 8px 15px;
    border-radius: 6px;
    font-weight: bold;
    margin-bottom: 15px;
    transition: background 0.2s;
}

.card a:hover {
    background: #16325c;
}

</style>
</head>
<body>

<a href="teacher-dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<div class="header-strip">
📌 Select Exam to Evaluate
</div>

<div class="container">
    <?php while($row = mysqli_fetch_assoc($query)) { ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($row['exam_name']); ?></h3>
        <p>Students: <?php echo $row['student_count']; ?></p>
        <a href="evaluate-students.php?exam_id=<?php echo $row['id']; ?>">View Students</a>
    </div>
    <?php } ?>
</div>

</body>
</html>