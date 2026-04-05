<?php
session_start();

if(!isset($_SESSION['student_id'])){
    header("Location: student-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *{margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif;}
        body{display:flex; background:#f2f9ff;}

        /* Sidebar */
        .sidebar{
            width:60px; height:100vh; background:#4da6ff; color:white; position:fixed;
            transition:0.3s; overflow:hidden;
        }
        .sidebar.active{width:200px;}
        .sidebar ul{list-style:none; padding:20px 10px;}
        .sidebar ul li{margin:25px 0;}
        .sidebar ul li a{text-decoration:none; color:white; display:flex; align-items:center; gap:15px; padding:10px; border-radius:8px; transition:0.3s;}
        .sidebar ul li a:hover{background:rgba(255,255,255,0.2);}
        .sidebar ul li span{display:none;}
        .sidebar.active ul li span{display:inline;}
        .toggle{font-size:22px; padding:20px; cursor:pointer; color:white;}
        .sidebar i{font-size:18px;}

        /* Main Content */
        .main{margin-left:60px; width:100%; padding:40px; transition:0.3s;}
        .main.active{margin-left:200px;}

        /* Welcome Section */
        .welcome-box{background:white; padding:40px; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,0.1); text-align:center; margin-top:10px;}
        .welcome-box h1{font-size:34px; color:#007acc; margin-bottom:15px;}
        .welcome-box p{font-size:18px; color:#555;}

        /* Highlight Section */
        .highlight{margin-top:50px; text-align:center;}
        .highlight h2{font-size:28px; color:#4da6ff;}
        .highlight p{margin-top:10px; font-size:16px; color:#555;}

        /* Logout */
        .logout{text-align:center; margin-top:40px;}
        .logout a{background:#4da6ff; color:white; padding:8px 15px; border-radius:6px; text-decoration:none;}
        .student-profile{
display:flex;
align-items:center;
gap:10px;
padding:10px;
margin:10px;
color:white;
}

.student-profile i{
font-size:25px;
width:30px;
text-align:center;
}

.student-details{
display:none;
}

.sidebar.active .student-details{
display:block;
}

.student-details h4{
margin:0;
font-size:15px;
}

.student-details p{
font-size:12px;
opacity:0.8;
}
.topbar{
display:flex;
justify-content:space-between;
align-items:center;
background:white;
padding:15px 25px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
margin-bottom:30px;
}

.system-title{
font-size:18px;
font-weight:600;
color:#007acc;
}

.system-title i{
margin-right:8px;
}

.student-info{
font-size:16px;
color:#555;
display:flex;
align-items:center;
gap:8px;
}
    </style>
</head>

<body>

<div class="sidebar" id="sidebar">
    <div class="toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
<div class="student-profile">
    <i class="fas fa-user-circle"></i>
    <div class="student-details">
        <h4><?php echo $_SESSION['student_name']; ?></h4>
        <p>Student</p>
    </div>
</div>
    <ul>
        <li><a href="available-exams.php"><i class="fas fa-book"></i><span>Available Exams</span></a></li>
        <li><a href="student-results.php"><i class="fas fa-chart-bar"></i><span>Results</span></a></li>
        <li><a href="performance.php"><i class="fas fa-chart-line"></i><span>Performance</span></a></li>
        <li><a href="scorecard.php"><i class="fas fa-file-download"></i><span>Scorecard</span></a></li>
        <li>
<a href="student-profile.php">
<i class="fas fa-user"></i>
<span>My Profile</span>
</a>
</li>
        <li><a href="student-change-password.php"><i class="fas fa-key"></i><span>Change Password</span></a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
    </ul>
</div>

<div class="main" id="main">
<div class="topbar">

<div class="system-title">
<i class="fas fa-graduation-cap"></i> Online Examination System
</div>

<div class="student-info">
<i class="fas fa-user"></i> <?php echo $_SESSION['student_name']; ?>
</div>

</div>
    <div class="welcome-box">
        <h1>Welcome <?php echo $_SESSION['student_name']; ?> 👋</h1>
        <p>
            “Success is the sum of small efforts, repeated day in and day out.”
        </p>
    </div>

    <div class="highlight">
        <h2>Ready to Test Your Knowledge?</h2>
        <p>Attempt exams, track your progress, and improve every day. Your journey to excellence starts here 🚀</p>
    </div>

</div>

<script>
function toggleMenu(){
    document.getElementById("sidebar").classList.toggle("active");
    document.getElementById("main").classList.toggle("active");
}
</script>

</body>
</html>