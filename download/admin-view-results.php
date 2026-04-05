<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
}

$search = "";
$exam_filter = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];
}

if(isset($_GET['exam'])){
    $exam_filter = $_GET['exam'];
}

// Fetch exams for filter
$exams = mysqli_query($conn,"SELECT * FROM exams");

// Base query for student results
$query = "
SELECT 
students.id AS student_id,
students.name AS student_name,
exams.id AS exam_id,
exams.exam_name,
SUM(student_answers.marks_awarded) AS obtained_marks,
SUM(questions.marks) AS total_marks
FROM student_answers
JOIN students ON student_answers.student_id = students.id
JOIN exams ON student_answers.exam_id = exams.id
JOIN questions ON student_answers.question_id = questions.id
WHERE 1
";

if($exam_filter != ""){
    $query .= " AND exams.id='$exam_filter'";
}

if($search != ""){
    $query .= " AND students.name LIKE '%$search%'";
}

$query .= " GROUP BY student_answers.student_id, student_answers.exam_id";

$result = mysqli_query($conn,$query);

// For progress bar: total students for selected exam or all exams
$progress_query = "SELECT COUNT(DISTINCT student_id) AS total_students FROM student_answers WHERE 1";
if($exam_filter != ""){
    $progress_query .= " AND exam_id='$exam_filter'";
}
$progress_result = mysqli_fetch_assoc(mysqli_query($conn, $progress_query));
$total_students = $progress_result['total_students'];

// Calculate average percentage
$total_percentages = 0;
$student_count = 0;
$all_results = [];
while($row = mysqli_fetch_assoc($result)){
    $percentage = ($row['total_marks'] > 0) ? ($row['obtained_marks']/$row['total_marks'])*100 : 0;
    $row['percentage'] = $percentage;
    $all_results[] = $row;
    $total_percentages += $percentage;
    $student_count++;
}
$avg_percentage = ($student_count>0) ? number_format($total_percentages/$student_count,2) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin – Exam Results</title>
<style>
body{
    font-family:Arial;
    background:#eef4ff;
    margin:0;
}
.container{
    width:90%;
    margin:auto;
    margin-top:30px;
}
h2{
    color:#1a3c7c;
    margin-bottom:20px;
}
.filters{
    display:flex;
    gap:15px;
    margin-bottom:25px;
}
input,select{
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    background:#007bff;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:6px;
    cursor:pointer;
}
.progress-container{
    margin-bottom:15px;
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}
.progress-bar{
    width:100%;
    height:15px;
    background:#ddd;
    border-radius:10px;
    overflow:hidden;
}
.progress-fill{
    height:100%;
    width:0%;
    background:#007bff;
    transition:0.3s;
}
.average-card{
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
    margin-bottom:20px;
    font-weight:bold;
    color:#1a3c7c;
}
.cards{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
}
.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
.card h3{
    margin-top:0;
    color:#1a3c7c;
}
.label{
    font-weight:bold;
}
.view-btn{
    display:inline-block;
    margin-top:10px;
    background:#3498db;
    color:white;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
}
.view-btn:hover{
    background:#2980b9;
}
</style>
</head>
<body>

<div class="container">
<!-- Back to Dashboard button -->
<div style="margin-bottom:20px;">
    <a href="admin-dashboard.php" style="
        background:#007bff;
        color:white;
        padding:8px 16px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
    ">⬅ Back to Dashboard</a>
</div>
<h2>📊 Admin Panel – Exam Results</h2>

<form method="GET" class="filters">
<input type="text" name="search" placeholder="Search student..." value="<?php echo $search; ?>">

<select name="exam">
<option value="">All Exams</option>
<?php foreach($exams as $exam){ ?>
<option value="<?php echo $exam['id']; ?>" <?php if($exam_filter==$exam['id']) echo "selected"; ?>>
<?php echo $exam['exam_name']; ?>
</option>
<?php } ?>
</select>

<button type="submit">Filter</button>
</form>

<!-- Progress Bar -->
<div class="progress-container">
<b>Total Students:</b> <?php echo $total_students; ?>
<div class="progress-bar">
    <div class="progress-fill" id="progressFill" style="width:100%;"></div>
</div>
</div>

<!-- Average Percentage Card -->
<div class="average-card">
Average Percentage: <?php echo $avg_percentage; ?>%
</div>

<!-- Student Result Cards -->
<div class="cards">
<?php foreach($all_results as $row){ 
    $percentage_text = number_format($row['percentage'],2)."%";
?>
<div class="card">
<h3><?php echo $row['student_name']; ?></h3>
<p><span class="label">Exam:</span> <?php echo $row['exam_name']; ?></p>
<p><span class="label">Marks:</span> <?php echo $row['obtained_marks']." / ".$row['total_marks']; ?></p>
<p><span class="label">Percentage:</span> <?php echo $percentage_text; ?></p>
<a class="view-btn" href="view-result-details.php?student_id=<?php echo $row['student_id']; ?>&exam_id=<?php echo $row['exam_id']; ?>">
View Details
</a>
</div>
<?php } ?>
</div>

</div>
</body>
</html>