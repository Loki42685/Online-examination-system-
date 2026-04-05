<?php
session_start();
include("config.php");

$exam_id = $_GET['exam_id'];

// Get exam name for header strip
$exam_query = mysqli_query($conn,"SELECT exam_name FROM exams WHERE id='$exam_id'");
$exam_data = mysqli_fetch_assoc($exam_query);
$exam_name = $exam_data['exam_name'];

// Fetch students for this exam
$query = mysqli_query($conn,"
SELECT DISTINCT students.id, students.name
FROM student_answers
JOIN students ON student_answers.student_id = students.id
WHERE student_answers.exam_id='$exam_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Select Student</title>
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

/* Container for student cards */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
}

/* Student card */
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

/* Student name blue header */
.card h3 {
    background: #1a3c7c;
    color: white;
    margin: 0;
    padding: 12px 0;
    font-size: 1.1em;
}

/* Evaluate paper button */
.card a {
    display: inline-block;
    text-decoration: none;
    background: #1a3c7c;
    color: white;
    padding: 8px 15px;
    border-radius: 6px;
    font-weight: bold;
    margin: 15px 0;
    transition: background 0.2s;
}

.card a:hover {
    background: #16325c;
}

</style>
</head>
<body>

<a href="evaluate-answers.php" class="back-btn">⬅ Back</a>

<div class="header-strip">
📌 Exam: <?php echo htmlspecialchars($exam_name); ?>
</div>

<div class="container">
    <?php while($row = mysqli_fetch_assoc($query)) { ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <a href="evaluate-paper.php?exam_id=<?php echo $exam_id; ?>&student_id=<?php echo $row['id']; ?>">
            Evaluate Paper
        </a>
    </div>
    <?php } ?>
</div>

</body>
</html>