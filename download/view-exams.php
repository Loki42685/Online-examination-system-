<?php
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$query = "SELECT * FROM exams WHERE teacher_id='$teacher_id'";
$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Exams</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f2f9ff;
    padding:40px;
}

.container{
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

h2{
    color:#1e3c72;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#1e3c72;
    color:white;
    padding:12px;
    text-align:left;
}

table td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

.add-btn{
    background:#1e3c72;
    color:white;
    padding:8px 14px;
    border-radius:5px;
    text-decoration:none;
}

.back{
    display:inline-block;
    margin-top:20px;
    padding:10px 15px;
    background:#1e3c72;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

</style>

</head>

<body>

<div class="container">

<h2>Available Exams</h2>

<table>

<tr>
<th>ID</th>
<th>Exam Name</th>
<th>Subject</th>
<th>Duration</th>
<th>Exam Date</th>
<th>Action</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){
    while($row=mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['exam_name']; ?></td>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['duration']; ?> mins</td>
<td><?php echo $row['exam_date']; ?></td>

<td>
<a class="add-btn" href="add-question.php?exam_id=<?php echo $row['id']; ?>">
Add Questions
</a>
</td>

</tr>

<?php
    }
}else{
?>

<tr>
<td colspan="6" style="text-align:center; padding:20px;">
No exams assigned by admin yet
</td>
</tr>

<?php } ?>

</table>

<a class="back" href="teacher-dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>