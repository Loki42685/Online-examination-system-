<?php
session_start();
include("config.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
}

$query = "
SELECT 
results.*, 
students.name AS student_name,
exams.exam_name
FROM results
JOIN students ON results.student_id = students.id
JOIN exams ON results.exam_id = exams.id
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family:'Segoe UI';
background:#eef5ff;
padding:40px;
}

.container{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

h2{
margin-bottom:20px;
color:#000428;
}

table{
width:100%;
border-collapse:collapse;
}

table th{
background:#000428;
color:white;
padding:12px;
text-align:left;
}

table td{
padding:12px;
border-bottom:1px solid #ddd;
}

.back{
margin-top:20px;
display:inline-block;
padding:10px 15px;
background:#000428;
color:white;
text-decoration:none;
border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Student Results</h2>

<table>

<tr>
<th>ID</th>
<th>Student</th>
<th>Exam</th>
<th>Total Marks</th>
<th>Percentage</th>
<th>Grade</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['student_name']; ?></td>
<td><?php echo $row['exam_name']; ?></td>
<td><?php echo $row['total_marks']; ?></td>
<td><?php echo $row['percentage']; ?>%</td>
<td><?php echo $row['grade']; ?></td>
<td><?php echo $row['is_published'] ? "Published" : "Pending"; ?></td>
</tr>

<?php } ?>

</table>

<a class="back" href="admin-dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>