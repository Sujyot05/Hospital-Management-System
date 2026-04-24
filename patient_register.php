<?php
include "db.php";

if(isset($_POST['register'])){
$name=$_POST['name'];
$email=$_POST['email'];
$pass=password_hash($_POST['password'],PASSWORD_DEFAULT);

mysqli_query($conn,"
INSERT INTO Patients(first_name,email,password)
VALUES('$name','$email','$pass')
");

echo "<script>alert('Registered! Now login');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Register</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
body{
font-family:Poppins;
background:#eef7f9;
margin:0;
}

/* HEADER */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 60px;
background:white;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.logo{
display:flex;
align-items:center;
gap:10px;
}

.logo img{width:40px;}

form{
width:320px;
margin:60px auto;
padding:30px;
background:white;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
text-align:center;
}

input{
width:90%;
padding:12px;
margin:10px 0;
border:1px solid #ddd;
border-radius:8px;
}

button{
background:#0b8fa1;
color:white;
border:none;
padding:12px;
width:100%;
border-radius:8px;
cursor:pointer;
}
</style>
</head>

<body>

<nav>
<div class="logo">
<img src="v987-18a.png">
<h3>HMS</h3>
</div>
<a href="patient_login.php">Login</a>
</nav>

<form method="POST">
<h2>Patient Register</h2>

<input name="name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>

<button name="register">Register</button>

<p>Already registered? <a href="patient_login.php">Login</a></p>
</form>

</body>
</html>
