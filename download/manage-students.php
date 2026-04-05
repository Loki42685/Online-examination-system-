<?php
include("config.php");
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
}

/* ADD STUDENT */
if(isset($_POST['add_student'])){

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $check = mysqli_query($conn,"SELECT * FROM students WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){

        echo "<script>alert('Email already exists');</script>";

    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn,
        "INSERT INTO students(name,email,password)
        VALUES('$name','$email','$hashed')");

        echo "<script>alert('Student Added Successfully');</script>";
    }
}

/* DELETE STUDENT */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,"DELETE FROM students WHERE id='$id'");

    echo "<script>
    alert('Student Deleted Successfully');
    window.location='manage-students.php';
    </script>";
}

/* FETCH STUDENTS */
$students = mysqli_query($conn,"SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Students</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
margin:0;
font-family:'Segoe UI';
background:#f4f6f9;
}

.container{
width:90%;
margin:auto;
margin-top:40px;
}

h2{
margin-bottom:20px;
}

form{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
margin-bottom:30px;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:6px;
border:1px solid #ccc;
}

button{
padding:10px 20px;
background:#28a745;
border:none;
color:white;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#1e7e34;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
}

th,td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:center;
}

th{
background:#28a745;
color:white;
}

.delete{
color:red;
text-decoration:none;
font-weight:bold;
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
<h2>Manage Students</h2>

<!-- ADD STUDENT FORM -->

<form method="POST">

<input type="text" name="name" placeholder="Student Name" required>

<input type="email" name="email" placeholder="Student Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="add_student">Add Student</button>

</form>


<!-- STUDENTS TABLE -->

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($students)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td>

<a class="delete"
href="manage-students.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this student?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>