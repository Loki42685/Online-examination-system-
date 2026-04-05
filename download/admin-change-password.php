<?php
include("config.php");
session_start();

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

if(isset($_POST['change_password'])){

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $result = mysqli_query($conn,
        "SELECT password FROM admin WHERE id='$admin_id'");

    $row = mysqli_fetch_assoc($result);

    if(password_verify($old_password, $row['password'])){

        if($new_password == $confirm_password){

            $hashed = password_hash($new_password, PASSWORD_DEFAULT);

            mysqli_query($conn,
                "UPDATE admin SET password='$hashed' WHERE id='$admin_id'");

            echo "<script>alert('Password Changed Successfully');
            window.location='admin-dashboard.php';
            </script>";

        } else {
            echo "<script>alert('New Passwords Do Not Match');</script>";
        }

    } else {
        echo "<script>alert('Old Password Incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script>
function togglePassword(id){
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#4da6ff,#007acc);
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

.password-container{
    position:relative;
    margin-bottom:15px;
}

.password-container input{
    width:100%;
    padding:10px;
    padding-right:15px;
    border-radius:8px;
    border:1px solid #ccc;
}

.password-container span{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
}
.back-btn{
    display:block;
    text-align:center;
    margin-top:15px;
    padding:10px;
    background:#1e3c72;
    color:white;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
}

.back-btn:hover{
    background:#16325c;
}
button{
    width:100%;
    padding:10px;
    background:#007acc;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
}

button:hover{
    background:#005f99;
}
</style>
</head>

<body>

<div class="container">
<h2>Change Password</h2>

<form method="POST">

<div class="password-container">
    <input type="password" id="old" name="old_password" placeholder="Old Password" required>
    <span onclick="togglePassword('old')">👁</span>
</div>

<div class="password-container">
    <input type="password" id="new" name="new_password" placeholder="New Password" required>
    <span onclick="togglePassword('new')">👁</span>
</div>

<div class="password-container">
    <input type="password" id="confirm" name="confirm_password" placeholder="Confirm Password" required>
    <span onclick="togglePassword('confirm')">👁</span>
</div>

<button type="submit" name="change_password">Update Password</button>

</form>
    <a class="back-btn" href="admin-dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>