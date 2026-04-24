<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}

include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient History - HMS</title>

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
background:rgba(255,255,255,0.8);
backdrop-filter:blur(10px);
}

.container{
width:90%;
max-width:1100px;
margin:40px auto;
padding:30px;
border-radius:15px;
background:rgba(255,255,255,0.7);
box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h2{
color:#0b8fa1;
margin-bottom:15px;
text-align:center;
}

input{
width:100%;
padding:14px;
margin:10px 0;
border-radius:8px;
border:1px solid #ddd;
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
padding:12px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
text-align:center;
}
</style>
</head>

<body>

<nav>
<h3>Patient History</h3>
<a href="admin.php">Dashboard</a>
</nav>

<div class="container">

<h2>Search Patient</h2>

<form method="GET">
<input name="search" placeholder="Enter Name or Govt ID" required>
<button>Search</button>
</form>

<?php
if(isset($_GET['search'])){
$s=$_GET['search'];

$q=mysqli_query($conn,"
SELECT 
p.patient_id,
p.first_name,
p.govt_id,
d.name AS doctor,
a.appointment_date,
a.reason,
b.total_amount

FROM Patients p
LEFT JOIN Appointments a ON p.patient_id=a.patient_id
LEFT JOIN Doctors d ON a.doctor_id=d.doctor_id
LEFT JOIN Billing b ON a.appointment_id=b.appointment_id

WHERE p.first_name LIKE '%$s%'
OR p.govt_id LIKE '%$s%'
");

echo "<table>
<tr>
<th>Patient</th>
<th>Govt ID</th>
<th>Doctor</th>
<th>Date</th>
<th>Reason</th>
<th>Bill</th>
</tr>";

while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$r['first_name']}</td>
<td>{$r['govt_id']}</td>
<td>Dr. {$r['doctor']}</td>
<td>{$r['appointment_date']}</td>
<td>{$r['reason']}</td>
<td>₹{$r['total_amount']}</td>
</tr>";
}

echo "</table>";
}
?>

</div>

</body>
</html>
