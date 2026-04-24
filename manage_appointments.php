<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
header("Location: admin.html");
exit();
}

include "db.php";

/* ADMIN BOOK */
if(isset($_POST['book'])){
$p=$_POST['patient'];
$d=$_POST['doctor'];
$date=$_POST['date'];
$time=$_POST['time'];
$reason=$_POST['reason'];

mysqli_query($conn,"
INSERT INTO Appointments
(patient_id,doctor_id,appointment_date,appointment_time,reason,status)
VALUES('$p','$d','$date','$time','$reason','Accepted')
");
}

/* ACCEPT */
if(isset($_GET['accept'])){
$id=$_GET['accept'];
mysqli_query($conn,"
UPDATE Appointments 
SET status='Accepted'
WHERE appointment_id=$id");
}

/* REJECT */
if(isset($_GET['reject'])){
$id=$_GET['reject'];
mysqli_query($conn,"
UPDATE Appointments 
SET status='Cancelled'
WHERE appointment_id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Appointments - HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
background:linear-gradient(135deg,#eef7f9,#ffffff);
}

nav{
display:flex;
justify-content:space-between;
padding:15px 60px;
background:rgba(255,255,255,0.7);
}

.container{
width:90%;
max-width:1100px;
margin:40px auto;
padding:30px;
border-radius:15px;
background:rgba(255,255,255,0.65);
box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h2{color:#0b8fa1;margin-bottom:15px;}

input,select,textarea{
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

.accept{
background:green;
color:white;
padding:6px 12px;
border-radius:6px;
text-decoration:none;
}

.reject{
background:red;
color:white;
padding:6px 12px;
border-radius:6px;
text-decoration:none;
}
</style>
</head>

<body>

<nav>
<h3>HMS Admin</h3>
<a href="admin.php">Dashboard</a>
</nav>

<!-- BOOK -->
<div class="container">
<h2>Book Appointment (Admin)</h2>

<form method="POST">

<select name="patient" required>
<option value="">Select Patient</option>
<?php
$p=mysqli_query($conn,"SELECT patient_id,first_name FROM Patients");
while($row=mysqli_fetch_assoc($p)){
echo "<option value='{$row['patient_id']}'>{$row['first_name']}</option>";
}
?>
</select>

<select name="doctor" required>
<option value="">Select Doctor</option>
<?php
$d=mysqli_query($conn,"SELECT doctor_id,name FROM Doctors");
while($row=mysqli_fetch_assoc($d)){
echo "<option value='{$row['doctor_id']}'>Dr. {$row['name']}</option>";
}
?>
</select>

<input type="date" name="date" required>
<input type="time" name="time" required>
<textarea name="reason" placeholder="Reason"></textarea>

<button name="book">Book Appointment</button>
</form>
</div>

<!-- LIST -->
<div class="container">
<h2>All Appointments</h2>

<table>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT a.*,p.first_name as pname,d.name as dname
FROM Appointments a
JOIN Patients p ON a.patient_id=p.patient_id
JOIN Doctors d ON a.doctor_id=d.doctor_id
ORDER BY appointment_date DESC
");

while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$r['appointment_id']}</td>
<td>{$r['pname']}</td>
<td>Dr. {$r['dname']}</td>
<td>{$r['appointment_date']}</td>
<td>{$r['appointment_time']}</td>
<td>{$r['status']}</td>
<td>";

if($r['status']=="Pending"){
echo "
<a class='accept' href='?accept={$r['appointment_id']}'>Accept</a>
<a class='reject' href='?reject={$r['appointment_id']}'>Reject</a>
";
}else{
echo "-";
}

echo "</td></tr>";
}
?>

</table>
</div>

</body>
</html>
