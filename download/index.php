<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Online Examination System</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

body{
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    min-height:100vh;
    display:flex;
    flex-direction:column;
}

/* Header */
.header{
    text-align:center;
    padding:50px 20px 20px 20px;
    color:white;
}

.header h1{
    font-size:38px;
    letter-spacing:1px;
}

.header p{
    margin-top:10px;
    font-size:18px;
    opacity:0.9;
}

/* Login/Register Text */
.login-text{
    text-align:center;
    margin:20px 0 40px 0;
    font-size:20px;
    font-weight:600;
    color:white;
}

/* Container */
.container{
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:40px;
    padding:20px;
}

/* Card */
.card{
    width:260px;
    padding:35px 20px;
    border-radius:20px;
    backdrop-filter: blur(15px);
    background: rgba(255,255,255,0.15);
    box-shadow:0 8px 32px rgba(0,0,0,0.3);
    text-align:center;
    color:white;
    transition:0.4s ease;
}

.card:hover{
    transform: translateY(-10px) scale(1.05);
}

.card i{
    font-size:50px;
    margin-bottom:20px;
}

.card h3{
    margin-bottom:25px;
    font-size:24px;
}

/* Buttons */
.btn{
    display:inline-block;
    margin:6px;
    padding:8px 18px;
    border-radius:25px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.login-btn{
    background:white;
    color:#1e3c72;
}

.register-btn{
    background:transparent;
    border:2px solid white;
    color:white;
}

.login-btn:hover{
    background:#dbe9ff;
}

.register-btn:hover{
    background:white;
    color:#1e3c72;
}

/* Responsive */
@media(max-width:900px){
    .container{
        flex-direction:column;
        align-items:center;
    }
}

</style>
</head>

<body>

<div class="header">
    <h1>Online Examination System</h1>
    <p>A Software Engineering Project</p>
</div>

<div class="login-text">
    Login or Register Here
</div>

<div class="container">

    <!-- Student -->
    <div class="card">
        <i class="fas fa-user-graduate"></i>
        <h3>Student</h3>
        	<a href="student-login.php" class="btn login-btn">Login</a>
			<a href="student-register.php" class="btn register-btn">Register</a>



    </div>

    <!-- Teacher -->
    <div class="card">
        <i class="fas fa-chalkboard-teacher"></i>
        <h3>Teacher</h3>
        	<a href="teacher-login.php" class="btn login-btn">Login</a>
        	<a href="teacher-register.php" class="btn register-btn">Register</a>

    </div>

    <!-- Admin -->
    <div class="card">
        <i class="fas fa-user-shield"></i>
        <h3>Admin</h3>
        	<a href="admin-login.php" class="btn login-btn">Login</a>

    </div>

</div>

</body>
</html>
