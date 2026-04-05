<?php
include("config.php");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	// Check if email already exists in students table
$check1 = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
if(mysqli_num_rows($check1) > 0){
    echo "<script>alert('Email already registered');</script>";
    exit();
}

// Check if email already pending verification
$check2 = mysqli_query($conn, "SELECT * FROM otp_verification WHERE email='$email'");
if(mysqli_num_rows($check2) > 0){
    echo "<script>alert('Email already sent OTP. Please verify.');</script>";
    exit();
}
    // Generate 6-digit OTP
    $otp = rand(100000,999999);

    // Insert into otp_verification table
    $sql = "INSERT INTO otp_verification (name,email,password,otp)
            VALUES ('$name','$email','$password','$otp')";

    if(mysqli_query($conn,$sql)){

        $mail = new PHPMailer(true);
	
        try{

            require 'mail-config.php';
			configureMail($mail);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification OTP';
            $mail->Body    = "Your OTP is: <b>$otp</b>";

            $mail->send();

            $_SESSION['verify_email'] = $email;

            header("Location: verify-otp.php");
            exit();

        }catch(Exception $e){
            echo "Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "<script>alert('Something went wrong');</script>";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Student Register</title>
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
    margin:8px 0;
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

.link{text-align:center;margin-top:10px;}

a{text-decoration:none;color:#1e3c72;}

</style>
</head>
<body>

<div class="container">
    <h2>Student Register</h2>
    <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <div style="position:relative;">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <span onclick="togglePassword('password')" 
          style="position:absolute; right:10px; top:10px; cursor:pointer;">
        👁
    </span>
</div>
    <button type="submit" name="register">Register</button>
</form>

    <div class="link">
        <a href="student-login.php">Already have account? Login</a>
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
