<?php

include "db.php";

$result=null;

if(isset($_GET['search'])){

$search=$_GET['search'];

$result=mysqli_query($conn,"
SELECT 
b.bill_id,
b.total_amount,
b.payment_status,
b.payment_method,
p.first_name,
a.appointment_id
FROM Billing b
JOIN Appointments a ON b.appointment_id=a.appointment_id
JOIN Patients p ON a.patient_id=p.patient_id
WHERE 
a.appointment_id LIKE '%$search%'
OR p.first_name LIKE '%$search%'
");

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Check Bill Status</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#0f172a,#1e3a8a);
min-height:100vh;
color:white;
}

/* GLASS */

.glass{

background:rgba(255,255,255,0.08);
backdrop-filter:blur(12px);
border-radius:15px;
border:1px solid rgba(255,255,255,0.2);

}

.table{
color:white;
}

.table thead{
background:rgba(255,255,255,0.15);
}

</style>

</head>

<body>

<div class="container py-5">

<h3 class="text-center mb-4">
Check Bill Status
</h3>

<!-- SEARCH -->

<div class="glass p-4 mb-4">

<form method="GET">

<div class="input-group">

<input 
type="text"
name="search"
class="form-control"
placeholder="Enter Appointment ID or Patient Name"
required>

<button class="btn btn-primary">
Search
</button>

</div>

</form>

</div>


<!-- RESULT -->

<?php if($result){ ?>

<div class="glass p-4">

<table class="table table-bordered text-center">

<thead>

<tr>

<th>Bill ID</th>
<th>Appointment</th>
<th>Patient</th>
<th>Amount</th>
<th>Method</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result)){

echo "<tr>

<td>{$row['bill_id']}</td>
<td>{$row['appointment_id']}</td>
<td>{$row['first_name']}</td>
<td>₹{$row['total_amount']}</td>
<td>{$row['payment_method']}</td>

<td>";

if($row['payment_status']=="Paid"){

echo "<span class='badge bg-success'>Paid</span>";

}else{

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}

echo "</td>

</tr>";

}

?>

</tbody>

</table>

</div>

<?php } ?>

</div>

</body>
</html>