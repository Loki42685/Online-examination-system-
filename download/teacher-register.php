<?php
include("config.php");

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO teachers (name,email,password)
            VALUES ('$name','$email','$password')";

    if(mysqli_query($conn,$sql)){

        $teacher_id = mysqli_insert_id($conn);

        echo "<script>
        alert('Registration Successful! Your Teacher ID is: $teacher_id');
        window.location='teacher-login.php';
        </script>";
        exit();

    } else {
        echo "<script>alert('Error: Email may already exist');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Register</title>
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
    <h2>Teacher Register</h2>
    <form method="POST" action="">
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
        <a href="teacher-login.php">Already have account? Login</a>
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
