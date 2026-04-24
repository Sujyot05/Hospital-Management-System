<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}

include "db.php";

/* ADD */
if(isset($_POST['add'])){
    $name=$_POST['name'];
    $spec=$_POST['spec'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $fee=$_POST['fee'];

    mysqli_query($conn,"INSERT INTO Doctors
    (name,specialization,phone,email,consultation_fee)
    VALUES('$name','$spec','$phone','$email','$fee')");
}

/* DELETE */
if(isset($_GET['del'])){
    $id=$_GET['del'];
    mysqli_query($conn,"DELETE FROM Doctors WHERE doctor_id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Doctors - HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

body{
background:linear-gradient(135deg,#e9f4f6,#f8fbfc);
}

/* HEADER */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 60px;
background:rgba(255,255,255,0.7);
backdrop-filter:blur(12px);
box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.logo{
display:flex;
gap:10px;
align-items:center;
}

.logo img{width:40px;}

nav a{
text-decoration:none;
color:#333;
font-weight:500;
}

/* GLASS CARD */
.container{
width:90%;
max-width:1100px;
margin:40px auto;
padding:30px;
border-radius:15px;
background:rgba(255,255,255,0.6);
backdrop-filter:blur(12px);
box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

h2{
color:#0b8fa1;
margin-bottom:15px;
}

/* INPUTS */
input{
width:100%;
padding:14px;
margin:8px 0;
border-radius:8px;
border:1px solid #e0e0e0;
background:white;
}

/* BUTTON */
button{
background:#0b8fa1;
color:white;
border:none;
padding:14px;
border-radius:8px;
cursor:pointer;
width:100%;
font-weight:500;
transition:0.3s;
}

button:hover{
background:#097481;
}

/* TABLE */
table{
width:100%;
margin-top:20px;
border-collapse:collapse;
background:white;
border-radius:10px;
overflow:hidden;
}

th{
background:#0b8fa1;
color:white;
padding:14px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
text-align:center;
}

tr:hover{
background:#f9f9f9;
}

a.del{
color:red;
text-decoration:none;
font-weight:500;
}

/* MOBILE */
@media(max-width:768px){
nav{padding:15px 20px;}
}
</style>
</head>

<body>

<!-- HEADER -->
<nav>
<div class="logo">
<img src="v987-18a.png">
<h3>HMS Admin</h3>
</div>

<a href="admin.php">Dashboard</a>
</nav>

<!-- ADD DOCTOR -->
<div class="container">

<h2>Add Doctor</h2>

<form method="POST">
<input name="name" placeholder="Doctor Name" required>
<input name="spec" placeholder="Specialization" required>
<input name="phone" placeholder="Phone">
<input name="email" placeholder="Email">
<input name="fee" placeholder="Consultation Fee" required>
<button name="add">Add Doctor</button>
</form>

</div>

<!-- DOCTOR LIST -->
<div class="container">

<h2>Doctor List</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Specialization</th>
<th>Phone</th>
<th>Email</th>
<th>Fee</th>
<th>Action</th>
</tr>

<?php
$res=mysqli_query($conn,"SELECT * FROM Doctors");

while($row=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$row['doctor_id']}</td>
<td>{$row['name']}</td>
<td>{$row['specialization']}</td>
<td>{$row['phone']}</td>
<td>{$row['email']}</td>
<td>{$row['consultation_fee']}</td>
<td><a class='del' href='?del={$row['doctor_id']}'>Delete</a></td>
</tr>";
}
?>

</table>

</div>

</body>
</html>

