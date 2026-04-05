<?php
include("config.php");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['send_otp'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check = mysqli_query($conn, "SELECT * FROM teachers WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){

        $otp = rand(100000,999999);

        $created = date("Y-m-d H:i:s");
        $expiry  = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        mysqli_query($conn, "DELETE FROM password_reset WHERE email='$email'");

        mysqli_query($conn, "INSERT INTO password_reset (email, otp, created_at, expiry)
                             VALUES ('$email','$otp','$created','$expiry')");

        $_SESSION['teacher_reset_email'] = $email;

        $mail = new PHPMailer(true);

        try{
            require 'mail-config.php';
            configureMail($mail);

            $mail->addAddress($email);
            $mail->Subject = "Password Reset OTP";
            $mail->Body    = "Your OTP is: $otp\nValid for 5 minutes.";

            $mail->send();

            header("Location: teacher-verify-otp.php");
            exit();

        }catch(Exception $e){
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    }else{
        echo "<script>alert('Email not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Forgot Password</title>
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
</style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" name="send_otp">Send OTP</button>
    </form>
</div>

</body>
</html>