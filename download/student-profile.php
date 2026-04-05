<?php
session_start();
include("config.php");

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$query = mysqli_query($conn,"SELECT * FROM students WHERE id='$student_id'");
$student = mysqli_fetch_assoc($query);

/* Update name only */
if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    mysqli_query($conn,"UPDATE students SET name='$name' WHERE id='$student_id'");
    echo "<script>alert('Profile Updated'); window.location='student-profile.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Profile</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#eef5ff;
    padding:40px;
}

.profile-card{
    max-width:500px;
    margin:auto;
    background:white;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.profile-header{
    text-align:center;
    margin-bottom:25px;
}

.profile-icon{
    font-size:70px;
    color:#000428;
    margin-bottom:10px;
}

.profile-header h2{
    color:#000428;
}

.profile-info{
    margin-bottom:20px;
}

.profile-info label{
    font-weight:bold;
    display:block;
    margin-top:12px;
}

input{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-top:5px;
}

.info-box{
    background:#f5f7ff;
    padding:10px;
    border-radius:6px;
    margin-top:5px;
    color:#333;
}

button{
    margin-top:20px;
    width:100%;
    padding:10px;
    border:none;
    border-radius:6px;
    background:#000428;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#021b79;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    text-decoration:none;
    color:#000428;
}
</style>

</head>
<body>

<div class="profile-card">

<div class="profile-header">
    <div class="profile-icon">
        <i class="fas fa-user-circle"></i>
    </div>
    <h2>Student Profile</h2>
</div>

<form method="POST">

<div class="profile-info">

    <label>Name</label>
    <input type="text" name="name" value="<?php echo $student['name']; ?>" required>

    <label>Email</label>
    <div class="info-box"><?php echo $student['email']; ?></div>


    <label>Role</label>
    <div class="info-box">Student</div>

    <label>Account Created</label>
    <div class="info-box">
        <?php echo isset($student['created_at']) ? $student['created_at'] : "Not Available"; ?>
    </div>

</div>

<button type="submit" name="update">
    <i class="fas fa-save"></i> Update Name
</button>

</form>

<a class="back" href="student-dashboard.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>