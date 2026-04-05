<?php
session_start();
include("config.php");

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}
include("includes/ml_functions.php");
$student_id = $_SESSION['student_id'];

/* Get published results */

$query = mysqli_query($conn,"
SELECT DISTINCT
exams.id AS exam_id,
exams.exam_name,
results.total_marks AS obtained_marks,
results.percentage,
results.grade,
results.is_published
FROM exams

LEFT JOIN results 
ON exams.id = results.exam_id 
AND results.student_id = '$student_id'

LEFT JOIN student_answers 
ON exams.id = student_answers.exam_id 
AND student_answers.student_id = '$student_id'

WHERE student_answers.id IS NOT NULL
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Results</title>

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

.container{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:25px;
}

.card{
background:white;
border-left:6px solid #1a3c7c;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.08);
text-align:center;
overflow:hidden;
}

.card h3{
background:#1a3c7c;
color:white;
margin:0;
padding:12px;
}

.card p{
margin:12px 0;
font-weight:bold;
}

.grade{
color:#1a3c7c;
font-size:18px;
}

.card a{
display:inline-block;
margin-bottom:15px;
background:#1a3c7c;
color:white;
padding:8px 15px;
border-radius:6px;
text-decoration:none;
}

.no-results{
text-align:center;
font-size:18px;
color:#555;
}

</style>

</head>
<body>

<a href="student-dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<div class="header-strip">
📊 My Exam Results
</div>

<div class="container">

<?php 
if(mysqli_num_rows($query) > 0){

while($row=mysqli_fetch_assoc($query)){ 
   $is_published = $row['is_published'];
$avg_query = mysqli_query($conn, "
    SELECT AVG(percentage) as avg_score 
    FROM results 
    WHERE student_id='$student_id'
");

$avg_row = mysqli_fetch_assoc($avg_query);

// If no previous data, fallback to 70
$previous_marks = ($avg_row['avg_score'] !== NULL) ? $avg_row['avg_score'] : 70;
if(isset($row['is_published']) && $row['is_published'] == 1){
    $obtained = $row['percentage'];
} else {
    $obtained = $previous_marks;
}
	// Get average of previous results

// Temporary previous marks (we improve later)


// AI Prediction
$predicted = predictScore($previous_marks, $obtained);

// Performance Level
if($predicted >= 75){
    $level = "Excellent 🟢";
}else if($predicted >= 50){
    $level = "Good 🟡";
}else{
    $level = "Needs Improvement 🔴";
}
?>

<div class="card">

<h3><?php echo $row['exam_name']; ?></h3>

<?php if(isset($row['is_published']) && $row['is_published'] == 1){ ?>

<p>Marks: <?php echo $row['obtained_marks']; ?></p>
<p>Percentage: <?php echo $row['percentage']; ?>%</p>
<p class="grade">Grade: <?php echo $row['grade']; ?></p>

<?php } else { ?>

<p style="color:#888;">⏳ Result under evaluation</p>

<?php } ?>

<div style="background:#f0f6ff; padding:10px; border-radius:8px; margin-top:10px;">
    <p><b>🤖 AI Prediction</b></p>
    <p>Score: <?php echo $predicted; ?>%</p>
    <p>Performance: <?php echo $level; ?></p>
</div>

<?php if(isset($row['is_published']) && $row['is_published'] == 1){ ?>
<a href="student-result-details.php?exam_id=<?php echo $row['exam_id']; ?>">
View Details
</a>
<?php } ?>
</div>

<?php 
}
}else{
echo "<div class='no-results'>No results published yet.</div>";
}
?>

</div>

</body>
</html>