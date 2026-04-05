<?php
include("config.php");
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
    
}

if(isset($_GET['delete_exam_id'])) {
    $exam_id = $_GET['delete_exam_id'];

    // 1️⃣ Delete student answers for this exam
    mysqli_query($conn, "DELETE FROM student_answers WHERE exam_id='$exam_id'");

    // 2️⃣ Delete questions for this exam
    mysqli_query($conn, "DELETE FROM questions WHERE exam_id='$exam_id'");

    // 3️⃣ Delete the exam itself
    mysqli_query($conn, "DELETE FROM exams WHERE id='$exam_id'");

    // Optional: redirect back with success message
    header("Location: manage-exams.php?msg=Exam+deleted+successfully");
    exit();
}


/* ADD EXAM */
if(isset($_POST['add_exam'])){

    $exam_name = mysqli_real_escape_string($conn,$_POST['exam_name']);
    $subject = mysqli_real_escape_string($conn,$_POST['subject']);
    $duration = $_POST['duration'];
    $exam_date = $_POST['exam_date'];
    $teacher_id = $_POST['teacher_id'];
    $exam_type = $_POST['exam_type'];

    mysqli_query($conn,
    "INSERT INTO exams(teacher_id,exam_name,subject,duration,exam_date,status,exam_type)
    VALUES('$teacher_id','$exam_name','$subject','$duration','$exam_date','active','$exam_type')");

    echo "<script>alert('Exam Created Successfully');</script>";
}

/* DELETE EXAM WITH QUESTIONS AND STUDENT ANSWERS */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    // Delete student answers for this exam
    mysqli_query($conn, "DELETE FROM student_answers WHERE exam_id='$id'");

    // Delete questions for this exam
    mysqli_query($conn, "DELETE FROM questions WHERE exam_id='$id'");

    // Delete the exam itself
    mysqli_query($conn, "DELETE FROM exams WHERE id='$id'");

    echo "<script>
    alert('Exam Deleted Successfully (Questions & Answers also removed)');
    window.location='manage-exams.php';
    </script>";
}

/* FETCH DATA */
$teachers = mysqli_query($conn,"SELECT * FROM teachers");
$exams = mysqli_query($conn,"SELECT exams.*, teachers.name AS teacher_name
FROM exams
LEFT JOIN teachers ON exams.teacher_id = teachers.id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Exams</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{margin:0;font-family:'Segoe UI';background:#f4f6f9;}
.container{width:90%;margin:auto;margin-top:40px;}
h2{margin-bottom:20px;}
form{background:white;padding:20px;border-radius:10px;box-shadow:0 5px 10px rgba(0,0,0,0.1);margin-bottom:30px;}
input,select{width:100%;padding:10px;margin:10px 0;border-radius:6px;border:1px solid #ccc;}
button{padding:10px 20px;background:#007acc;border:none;color:white;border-radius:6px;cursor:pointer;}
button:hover{background:#005fa3;}
table{width:100%;border-collapse:collapse;background:white;box-shadow:0 5px 10px rgba(0,0,0,0.1);}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
th{background:#007acc;color:white;}
.delete{color:red;text-decoration:none;font-weight:bold;}
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
<h2>Manage Exams</h2>

<!-- CREATE EXAM FORM -->
<form method="POST">
<input type="text" name="exam_name" placeholder="Exam Name" required>
<input type="text" name="subject" placeholder="Subject" required>
<input type="number" name="duration" placeholder="Duration (minutes)" required>
<input type="date" name="exam_date" required>
<select name="teacher_id" required>
    
<option value="">Assign Teacher</option>
<?php while($t = mysqli_fetch_assoc($teachers)){ ?>
<option value="<?php echo $t['id']; ?>">
<?php echo $t['name']; ?>
</option>
<?php } ?>
</select>
<select name="exam_type" required>
<option value="">Select Exam Type</option>
<option value="mcq">MCQ</option>
<option value="descriptive">Descriptive</option>
</select>
<button type="submit" name="add_exam">Create Exam</button>
</form>

<!-- EXAMS TABLE -->
<table>
<tr>
<th>ID</th>
<th>Exam Name</th>
<th>Subject</th>
<th>Duration</th>
<th>Date</th>
<th>Teacher</th>
<th>Type</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($exams)){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['exam_name']; ?></td>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['duration']; ?> mins</td>
<td><?php echo $row['exam_date']; ?></td>
<td><?php echo $row['teacher_name']; ?></td>
<td><?php echo strtoupper($row['exam_type']); ?></td>
<td><?php echo $row['status']; ?></td>
<td>
<a class="delete"
href="manage-exams.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this exam?')">
Delete
</a>
</td>
</tr>
<?php } ?>
</table>

</div>
</body>
</html>