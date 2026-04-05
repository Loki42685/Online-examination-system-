<?php
session_start();
include("config.php");

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

/* Get performance stats */

$result = mysqli_query($conn,"
SELECT 
COUNT(*) AS total_exams,
SUM(total_marks) AS total_marks,
AVG(percentage) AS avg_percentage,
MAX(percentage) AS highest_percentage
FROM results
WHERE student_id='$student_id'
AND is_published = 1
");

$data = mysqli_fetch_assoc($result);

$total_exams = $data['total_exams'] ?? 0;
$total_marks = $data['total_marks'] ?? 0;
$avg_percentage = round($data['avg_percentage'] ?? 0,2);
$highest_percentage = $data['highest_percentage'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>
<title>My Performance</title>

<style>

body{
font-family:'Segoe UI';
background:#eef4ff;
padding:40px;
}

.back-btn{
display:inline-block;
background:#007bff;
color:white;
padding:10px 18px;
border-radius:6px;
text-decoration:none;
margin-bottom:20px;
}

.header-strip{
background:#1a3c7c;
color:white;
padding:15px 20px;
font-size:20px;
border-radius:6px;
margin-bottom:30px;
}

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:25px;
}

.card{
background:white;
border-left:6px solid #1a3c7c;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
text-align:center;
padding:25px;
}

.card h3{
margin-bottom:10px;
color:#1a3c7c;
}

.card p{
font-size:28px;
font-weight:bold;
}

</style>

</head>

<body>

<a href="student-dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<div class="header-strip">
📈 My Performance
</div>

<div class="stats">

<div class="card">
<h3>Total Exams</h3>
<p><?php echo $total_exams; ?></p>
</div>

<div class="card">
<h3>Total Marks</h3>
<p><?php echo $total_marks; ?></p>
</div>

<div class="card">
<h3>Average Percentage</h3>
<p><?php echo $avg_percentage; ?>%</p>
</div>

<div class="card">
<h3>Highest Score</h3>
<p><?php echo $highest_percentage; ?>%</p>
</div>

</div>

</body>
</html>