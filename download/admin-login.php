<?php
include("config.php");
session_start();

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];

            header("Location: admin-dashboard.php");
            exit();

        } else {
            echo "<script>alert('Wrong Password');</script>";
        }

    } else {
        echo "<script>alert('Admin Not Found');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#000428,#004e92);
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
    background:#000428;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
button:hover{
    background:#004e92;
}
</style>
</head>

<body>

<div class="container">
    <h2>Admin Login</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <div style="position:relative;">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <span onclick="togglePassword('password')" 
          style="position:absolute; right:10px; top:10px; cursor:pointer;">
        👁
    </span>
</div>
        <button type="submit" name="login">Login</button>
        <div style="text-align:center;margin-top:10px;">
    <a href="admin-forgot-password.php">Forgot Password?</a>
</div>
    </form>

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

