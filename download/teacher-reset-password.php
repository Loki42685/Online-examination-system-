<?php
include("config.php");
session_start();

if(!isset($_SESSION['teacher_reset_email'])){
    header("Location: teacher-login.php");
    exit();
}

$email = $_SESSION['teacher_reset_email'];

if(isset($_POST['reset_password'])){

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password === $confirm_password){

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE teachers SET password='$hashed' WHERE email='$email'"
        );

        mysqli_query($conn,
            "DELETE FROM password_reset WHERE email='$email'"
        );

        unset($_SESSION['teacher_reset_email']);

        echo "<script>alert('Password Reset Successful');
              window.location='teacher-login.php';
              </script>";

    }else{
        echo "<script>alert('Passwords do not match');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
h2{text-align:center;margin-bottom:20px;}
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
button:hover{background:#2a5298;}
.eye{
    position:absolute;
    right:10px;
    top:12px;
    cursor:pointer;
}
.field{
    position:relative;
}
</style>
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>
    <form method="POST">

        <div class="field">
            <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            <span class="eye" onclick="togglePassword('new_password')">👁</span>
        </div>

        <div class="field">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <span class="eye" onclick="togglePassword('confirm_password')">👁</span>
        </div>

        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</div>

<script>
function togglePassword(id){
    var input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>