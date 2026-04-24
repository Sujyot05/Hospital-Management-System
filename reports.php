<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}

include "db.php";

/* SUMMARY DATA */
$sum=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
COUNT(*) AS total_appts,
SUM(total_amount) AS total_revenue
FROM Billing
WHERE payment_status='Paid'
"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Full Analysis - HMS</title>

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
background:rgba(255,255,255,0.8);
backdrop-filter:blur(10px);
}

.container{
width:95%;
max-width:1200px;
margin:40px auto;
padding:30px;
border-radius:15px;
background:rgba(255,255,255,0.7);
backdrop-filter:blur(12px);
box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

h2{
color:#0b8fa1;
margin-bottom:20px;
text-align:center;
}

table{
width:100%;
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

/* SUMMARY BOX */
.summary{
margin-top:30px;
padding:20px;
background:#f5fbfc;
border-radius:12px;
text-align:center;
font-size:18px;
}

.summary span{
font-weight:bold;
color:#0b8fa1;
}
</style>
</head>

<body>

<nav>
<h3>HMS Reports</h3>
<a href="admin.php">Dashboard</a>
</nav>

<div class="container">

<h2>Hospital Full Analysis</h2>

<table>
<tr>
<th>Appt ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Reason</th>
<th>Date</th>
<th>Amount</th>
<th>Status</th>
<th>Method</th>
</tr>

<?php
$q=mysqli_query($conn,"
SELECT 
a.appointment_id,
p.first_name AS patient,
d.name AS doctor,
a.reason,
a.appointment_date,
b.total_amount,
b.payment_status,
b.payment_method

FROM Appointments a
JOIN Patients p ON a.patient_id=p.patient_id
JOIN Doctors d ON a.doctor_id=d.doctor_id
LEFT JOIN Billing b ON a.appointment_id=b.appointment_id
ORDER BY a.appointment_date DESC
");

while($r=mysqli_fetch_assoc($q)){
echo "<tr>
<td>{$r['appointment_id']}</td>
<td>{$r['patient']}</td>
<td>Dr. {$r['doctor']}</td>
<td>{$r['reason']}</td>
<td>{$r['appointment_date']}</td>
<td>₹{$r['total_amount']}</td>
<td>{$r['payment_status']}</td>
<td>{$r['payment_method']}</td>
</tr>";
}
?>

</table>

<!-- SUMMARY -->
<div class="summary">
Total Appointments Completed: 
<span><?php echo $sum['total_appts']; ?></span>
<br><br>
Total Revenue Collected: 
<span>₹<?php echo $sum['total_revenue'] ?? 0; ?></span>
</div>

</div>

</body>
</html>
