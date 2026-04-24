<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include "db.php";

/* GENERATE BILL */

if(isset($_POST['generate'])){

$app=$_POST['appointment'];
$method=$_POST['method'];

$apptQ=mysqli_query($conn,"SELECT status FROM Appointments WHERE appointment_id='$app'");
$appt=mysqli_fetch_assoc($apptQ);

if($appt['status']!="Accepted"){
$popupMsg="Appointment not approved";
}

else{

$check=mysqli_query($conn,"SELECT * FROM Billing WHERE appointment_id='$app'");

if(mysqli_num_rows($check)==0){

$feeQ=mysqli_query($conn,"
SELECT d.consultation_fee
FROM Appointments a
JOIN Doctors d ON a.doctor_id=d.doctor_id
WHERE a.appointment_id='$app'
");

$fee=mysqli_fetch_assoc($feeQ);

mysqli_query($conn,"
INSERT INTO Billing
(appointment_id,total_amount,payment_method,payment_status,bill_date)
VALUES
('$app','{$fee['consultation_fee']}','$method','Pending',CURDATE())
");

$popupMsg="Bill Generated";

}

else{
$popupMsg="Bill already exists";
}

}

}

/* PAY BILL */

if(isset($_POST['pay_bill'])){

$id=$_POST['bill_id'];

mysqli_query($conn,"
UPDATE Billing
SET payment_status='Paid'
WHERE bill_id='$id'
");

echo "OK";
exit();

}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>HMS Billing</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#0f172a,#1e3a8a);
min-height:100vh;
color:white;
}

/* GLASS CARD */

.glass{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(12px);
border-radius:15px;
border:1px solid rgba(255,255,255,0.15);
box-shadow:0 8px 32px rgba(0,0,0,0.3);
}

/* TABLE */

.table{
color:white;
}

.table thead{
background:rgba(255,255,255,0.15);
}

/* NFC */

#nfc-screen{

position:fixed;
top:0;
left:0;
width:100%;
height:100%;
display:none;
align-items:center;
justify-content:center;
flex-direction:column;

background:linear-gradient(135deg,#020617,#0ea5e9);

z-index:9999;

}

.nfc-circle{

width:120px;
height:120px;

border-radius:50%;
border:6px solid white;

animation:pulse 1.5s infinite;

}

@keyframes pulse{

0%{transform:scale(1);}
50%{transform:scale(1.2);}
100%{transform:scale(1);}

}

</style>
</head>

<body>

<!-- NFC -->

<div id="nfc-screen">

<h4>Scan NFC Card</h4>

<div class="nfc-circle"></div>

<p id="nfc-status">Hold card near device...</p>

</div>

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-transparent p-3">

<div class="container">

<h4>Hospital Billing Panel</h4>

<button onclick="startNFC()" class="btn btn-light">
📡 Scan NFC
</button>

</div>

</nav>


<div class="container py-4">

<!-- GENERATE BILL -->

<div class="glass p-4 mb-4">

<h5 class="mb-3">Generate Bill</h5>

<form method="POST">

<select name="appointment" class="form-select mb-3" required>

<option value="">Select Appointment</option>

<?php

$q=mysqli_query($conn,"
SELECT a.appointment_id,p.first_name,d.name
FROM Appointments a
JOIN Patients p ON a.patient_id=p.patient_id
JOIN Doctors d ON a.doctor_id=d.doctor_id
WHERE a.status='Accepted'
");

while($r=mysqli_fetch_assoc($q)){

echo "<option value='{$r['appointment_id']}'>
Appt #{$r['appointment_id']} - {$r['first_name']} (Dr {$r['name']})
</option>";

}

?>

</select>

<select name="method" class="form-select mb-3">

<option>Cash</option>
<option>Card</option>
<option>UPI</option>

</select>

<button name="generate" class="btn btn-primary w-100">
Generate Bill
</button>

</form>

</div>


<!-- BILL TABLE -->

<div class="glass p-4">

<h5 class="mb-3">Pending Bills</h5>

<table class="table table-bordered text-center">

<thead>

<tr>
<th>Bill ID</th>
<th>Patient</th>
<th>Amount</th>
<th>Method</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php

$b=mysqli_query($conn,"
SELECT b.bill_id,b.total_amount,b.payment_method,p.first_name
FROM Billing b
JOIN Appointments a ON b.appointment_id=a.appointment_id
JOIN Patients p ON a.patient_id=p.patient_id
WHERE b.payment_status='Pending'
");

while($row=mysqli_fetch_assoc($b)){

echo "

<tr>

<td>{$row['bill_id']}</td>
<td>{$row['first_name']}</td>
<td>₹{$row['total_amount']}</td>
<td>{$row['payment_method']}</td>

<td>

<button class='btn btn-success btn-sm'
onclick=\"payBill({$row['bill_id']},'{$row['payment_method']}')\">
Pay Bill
</button>

</td>

</tr>

";

}

?>

</tbody>

</table>

</div>


<!-- PAYMENT BOX -->

<div id="paymentBox" class="glass p-4 mt-4" style="display:none">

<h5 id="payTitle"></h5>

<div id="payContent"></div>

</div>

</div>


<script>

/* PAYMENT UI */

function payBill(id,method){

let box=document.getElementById("paymentBox");
let title=document.getElementById("payTitle");
let content=document.getElementById("payContent");

box.style.display="block";

if(method=="Cash"){

title.innerText="Cash Payment";

content.innerHTML=`

<p>Go to <b>Counter 2</b></p>

<button class="btn btn-success"
onclick="processPayment(${id})">
Confirm Payment
</button>

`;

}

if(method=="Card"){

title.innerText="Card Payment";

content.innerHTML=`

<input id="cardNumber" class="form-control mb-2"
placeholder="Card Number"
onkeyup="detectCard()">

<div id="cardType" class="mb-2 text-warning"></div>

<input type="password" class="form-control mb-2"
placeholder="ATM PIN">

<button class="btn btn-success"
onclick="processPayment(${id})">
Pay Now
</button>

`;

}

if(method=="UPI"){

title.innerText="UPI Payment";

content.innerHTML=`

<input class="form-control mb-2"
placeholder="Enter UPI ID">

<button class="btn btn-success"
onclick="processPayment(${id})">
Pay via UPI
</button>

`;

}

}


/* CARD TYPE DETECTION */

function detectCard(){

let number=document.getElementById("cardNumber").value;
let type="";

if(number.startsWith("4")){
type="Visa";
}

else if(
number.startsWith("51")||
number.startsWith("52")||
number.startsWith("53")||
number.startsWith("54")||
number.startsWith("55")
){
type="MasterCard";
}

else if(number.startsWith("60")){
type="RuPay";
}

document.getElementById("cardType").innerHTML=
type ? "Card Type: "+type : "";

}


/* PROCESS PAYMENT */

function processPayment(id){

let content=document.getElementById("payContent");

content.innerHTML="Processing Payment...";

setTimeout(()=>{

fetch("",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"pay_bill=1&bill_id="+id

})

.then(res=>res.text())
.then(data=>{

content.innerHTML="<h5 class='text-success'>Payment Successful ✔</h5>";

setTimeout(()=>{
location.reload();
},1500);

});

},3000);

}


/* NFC DEMO */

function startNFC(){

const screen=document.getElementById("nfc-screen");
const status=document.getElementById("nfc-status");

screen.style.display="flex";

status.innerText="Scanning NFC...";

setTimeout(()=>{

status.innerText="Scan Failed ❌";

},3000);

setTimeout(()=>{

screen.style.display="none";

},4500);

}

</script>

<?php
if(isset($popupMsg)){
echo "<script>alert('$popupMsg');</script>";
}
?>

</body>
</html>