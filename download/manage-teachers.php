<?php
include("config.php");
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
}

/* ADD TEACHER */
if(isset($_POST['add_teacher'])){

    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $check = mysqli_query($conn,
        "SELECT * FROM teachers WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){

        echo "<script>alert('Email already exists');</script>";

    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn,
        "INSERT INTO teachers(name,email,password)
        VALUES('$name','$email','$hashed')");

        echo "<script>alert('Teacher Added Successfully');</script>";
    }
}


/* DELETE TEACHER */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn,
    "DELETE FROM teachers WHERE id='$id'");

    echo "<script>
    alert('Teacher Deleted');
    window.location='manage-teachers.php';
    </script>";
}
?>
<?php
$teachers = mysqli_query($conn,"SELECT * FROM teachers");
?>
<!DOCTYPE html>
<html>
<head>

<title>Manage Teachers</title>

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
background:#007acc;
border:none;
color:white;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#005fa3;
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
background:#007acc;
color:white;
}

.delete{
color:red;
text-decoration:none;
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
<h2>Manage Teachers</h2>

<!-- ADD TEACHER FORM -->

<form method="POST">

<input type="text" name="name" placeholder="Teacher Name" required>

<input type="email" name="email" placeholder="Teacher Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="add_teacher">Add Teacher</button>

</form>


<!-- TEACHERS TABLE -->

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($teachers)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['email']; ?></td>

<td>

<a class="delete"
href="manage-teachers.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this teacher?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>
