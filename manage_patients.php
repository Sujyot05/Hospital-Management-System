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
$fn=$_POST['first_name'];
$ln=$_POST['last_name'];
$gender=$_POST['gender'];
$dob=$_POST['dob'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$address=$_POST['address'];
$blood=$_POST['blood'];
$govt=$_POST['govt_id'];
$doc=$_POST['doctor'];

mysqli_query($conn,"INSERT INTO Patients
(first_name,last_name,gender,dob,phone,email,address,
blood_group,govt_id,assigned_doctor)
VALUES('$fn','$ln','$gender','$dob','$phone','$email',
'$address','$blood','$govt','$doc')");
}

/* DELETE */
if(isset($_GET['del'])){
$id=$_GET['del'];
mysqli_query($conn,"DELETE FROM Patients WHERE patient_id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Patients</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
background:linear-gradient(135deg,#eef7f9,#ffffff);
}

/* HEADER */
nav{
display:flex;
justify-content:space-between;
padding:15px 60px;
background:rgba(255,255,255,0.7);
backdrop-filter:blur(10px);
}

/* CARD */
.container{
width:90%;
max-width:1100px;
margin:40px auto;
padding:30px;
border-radius:15px;
background:rgba(255,255,255,0.65);
backdrop-filter:blur(12px);
box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h2{color:#0b8fa1;margin-bottom:15px;}

input,select{
width:100%;
padding:14px;
margin:8px 0;
border-radius:8px;
border:1px solid #e3e3e3;
}

button{
background:#0b8fa1;
color:white;
border:none;
padding:14px;
border-radius:8px;
cursor:pointer;
width:100%;
}

/* TABLE */
table{
width:100%;
margin-top:20px;
border-collapse:collapse;
background:white;
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

a.del{color:red;text-decoration:none;}
</style>
</head>

<body>

<nav>
<h3>HMS Admin</h3>
<a href="admin.php">Dashboard</a>
</nav>

<!-- ADD PATIENT -->
<div class="container">
<h2>Add Patient</h2>

<form method="POST">

<input name="first_name" placeholder="First Name" required>
<input name="last_name" placeholder="Last Name">

<select name="gender">
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>

<input type="date" name="dob">
<input name="phone" placeholder="Phone">
<input name="email" placeholder="Email">
<input name="address" placeholder="Address">

<input name="govt_id" placeholder="Govt ID (Aadhar/PAN)">

<!-- BLOOD GROUP DROPDOWN -->
<select name="blood">
<option>A+</option>
<option>A-</option>
<option>B+</option>
<option>B-</option>
<option>O+</option>
<option>O-</option>
<option>AB+</option>
<option>AB-</option>
</select>

<!-- DOCTOR DROPDOWN -->
<select name="doctor">
<option value="">Assign Doctor</option>
<?php
$d=mysqli_query($conn,"SELECT doctor_id,name FROM Doctors");
while($doc=mysqli_fetch_assoc($d)){
echo "<option value='{$doc['doctor_id']}'>
Dr. {$doc['name']}
</option>";
}
?>
</select>

<button name="add">Add Patient</button>

</form>
</div>

<!-- LIST -->
<div class="container">
<h2>Patient List</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Blood</th>
<th>Doctor</th>
<th>Govt ID</th>
<th>Action</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT p.*,d.name as docname
FROM Patients p
LEFT JOIN Doctors d
ON p.assigned_doctor=d.doctor_id
");

while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$r['patient_id']}</td>
<td>{$r['first_name']} {$r['last_name']}</td>
<td>{$r['blood_group']}</td>
<td>{$r['docname']}</td>
<td>{$r['govt_id']}</td>
<td><a class='del' href='?del={$r['patient_id']}'>Delete</a></td>
</tr>";
}
?>

</table>
</div>

</body>
</html>
