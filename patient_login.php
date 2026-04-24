<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $email=$_POST['email'];
    $pass=$_POST['password'];

    $q=mysqli_query($conn,"SELECT * FROM Patients WHERE email='$email'");
    $data=mysqli_fetch_assoc($q);

    if($data && password_verify($pass,$data['password'])){
        $_SESSION['patient']=$data['patient_id'];
        header("Location: patient_dashboard.php");
        exit();
    }else{
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Login | HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
min-height:100vh;
background:linear-gradient(135deg,#e0f2fe,#f8fafc);
display:flex;
flex-direction:column;
}

/* NAVBAR */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 6%;
background:white;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.logo{
display:flex;
align-items:center;
gap:10px;
font-size:1.2rem;
font-weight:600;
color:#0b8fa1;
}

.logo img{width:40px;}

nav a{
text-decoration:none;
color:#334155;
font-weight:500;
}

/* LOGIN CARD */
.wrapper{
flex:1;
display:flex;
justify-content:center;
align-items:center;
padding:30px;
}

.card{
background:white;
width:360px;
padding:40px;
border-radius:18px;
box-shadow:0 20px 40px rgba(0,0,0,0.1);
text-align:center;
}

.card h2{
color:#0b8fa1;
margin-bottom:6px;
}

.card p{
font-size:0.9rem;
color:#64748b;
margin-bottom:25px;
}

input{
width:100%;
padding:14px;
margin-bottom:15px;
border-radius:10px;
border:1px solid #cbd5e1;
font-size:0.95rem;
}

input:focus{
outline:none;
border-color:#0b8fa1;
}

button{
width:100%;
padding:14px;
border-radius:10px;
border:none;
background:#0b8fa1;
color:white;
font-size:1rem;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#0369a1;
transform:translateY(-2px);
}

.card a{
color:#0b8fa1;
text-decoration:none;
font-weight:500;
}

/* FOOTER */
footer{
text-align:center;
padding:20px;
color:#64748b;
font-size:0.85rem;
}
</style>
</head>

<body>

<!-- NAV -->
<nav>
<div class="logo">
<img src="v987-18a.png"> HMS
</div>
<a href="patient_register.php">Register</a>
</nav>

<!-- LOGIN -->
<div class="wrapper">
<div class="card">
<h2>Patient Login</h2>
<p>Access your appointments & medical records</p>

<form method="POST">
<input type="email" name="email" placeholder="Email Address" required>
<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>
</form>

<p style="margin-top:15px;">
New here? <a href="patient_register.php">Create an account</a>
</p>
</div>
</div>

<footer>
© 2026 Hospital Management System
</footer>

</body>
</html>
