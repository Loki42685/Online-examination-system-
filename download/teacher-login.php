<?php
include("config.php");
session_start();

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM teachers WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            $_SESSION['teacher_id'] = $row['id'];
            $_SESSION['teacher_name'] = $row['name'];

            header("Location: teacher-dashboard.php");
            exit();

        } else {
            echo "<script>alert('Wrong Password');</script>";
        }

    } else {
        echo "<script>alert('Teacher Not Found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.container{
    width:350px;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:10px;
    background:#1e3c72;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#2a5298;
}

.link{
    text-align:center;
    margin-top:10px;
}

a{
    text-decoration:none;
    color:#1e3c72;
}

</style>
</head>
<body>

<div class="container">
    <h2>Teacher Login</h2>
    <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required>
    <div style="position:relative;">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <span onclick="togglePassword('password')" 
          style="position:absolute; right:10px; top:10px; cursor:pointer;">
        👁
    </span>
</div>
    <button type="submit" name="login">Login</button>
        <div style="text-align:right; margin-top:5px;">
    <a href="teacher-forgot-password.php">Forgot Password?</a>
</div>
	</form>

    <div class="link">
        <a href="teacher-register.php">New Teacher? Register</a>
    </div>
</div>
<script>
function togglePassword(id){
    var input = document.getElementById(id);
    if(input.type === "password"){
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
</body>
</html>
