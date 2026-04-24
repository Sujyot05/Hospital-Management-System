<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}

include "db.php";

if(isset($_GET['paid'])){
    $id = $_GET['paid'];

    $update = mysqli_query($conn,"
        UPDATE Billing 
        SET payment_status='Paid' 
        WHERE bill_id='$id'
    ");

    if($update){
        $popupTitle = "Success";
      
    } else {
        $popupTitle = "Error";
        $popupMsg   = "Failed to update payment";
    }
}

/* GENERATE BILL */
if(isset($_POST['generate'])){
    $app    = $_POST['appointment'];
    $method = $_POST['method'];
    $extra  = isset($_POST['extra']) ? floatval($_POST['extra']) : 0;
    $tests  = isset($_POST['tests_total']) ? floatval($_POST['tests_total']) : 0;

    $apptQ = mysqli_query($conn,"SELECT status FROM Appointments WHERE appointment_id='$app'");
    $appt = mysqli_fetch_assoc($apptQ);

    if($appt['status'] != 'Accepted'){
        $popupTitle = "Not Allowed";
        $popupMsg   = "Appointment not valid for billing";
    }else{

        $check = mysqli_query($conn,"SELECT * FROM Billing WHERE appointment_id='$app'");

        if(mysqli_num_rows($check) > 0){
            $bill = mysqli_fetch_assoc($check);

            if($bill['payment_status'] == 'Paid'){
                $popupTitle = "Payment Info";
                $popupMsg   = "Bill already paid on {$bill['bill_date']}";
            }else{
                $popupTitle = "Info";
                $popupMsg   = "Bill already generated and pending";
            }
        }else{

            $feeQ = mysqli_query($conn,"
                SELECT d.consultation_fee
                FROM Appointments a
                LEFT JOIN Doctors d ON a.doctor_id = d.doctor_id
                WHERE a.appointment_id = '$app'
            ");

            $base = 0;
            if($feeQ && mysqli_num_rows($feeQ) > 0){
                $feeData = mysqli_fetch_assoc($feeQ);
                $base = floatval($feeData['consultation_fee']);
            }

            $total = $base + $extra + $tests;

            mysqli_query($conn,"
                INSERT INTO Billing
                (appointment_id,total_amount,payment_method,bill_date,payment_status)
                VALUES('$app','$total','$method',CURDATE(),'Pending')
            ");

            $popupTitle = "Success";
            $popupMsg   = "Consultation: ₹$base\nExtra: ₹$extra\nTests: ₹$tests\nTotal: ₹$total";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Billing | HMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}
body{background:linear-gradient(135deg,#e0f2fe,#f8fafc);}
.hero{background:linear-gradient(135deg,#0b8fa1,#0369a1);color:white;padding:40px;text-align:center;font-size:1.8rem;font-weight:600;}
nav{display:flex;justify-content:space-between;padding:15px 6%;background:white;box-shadow:0 4px 15px rgba(0,0,0,0.08);}
.container{max-width:1100px;margin:40px auto;padding:30px;background:white;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.1);}
h2{color:#0b8fa1;margin-bottom:20px;}
select,input,button{width:100%;padding:14px;margin-bottom:15px;border-radius:10px;border:1px solid #cbd5e1;}
button{background:#0b8fa1;color:white;border:none;font-weight:600;cursor:pointer;}
button:hover{background:#0369a1;}
.test-btn{background:#16a34a;}
.test-btn:hover{background:#15803d;}
table{width:100%;border-collapse:collapse;margin-top:20px;}
th{background:#0b8fa1;color:white;padding:14px;}
td{padding:12px;border-bottom:1px solid #eee;text-align:center;}
.badge{padding:6px 14px;border-radius:20px;font-size:0.85rem;font-weight:600;}
.pending{background:#fff7ed;color:#f97316;}
.paid{background:#ecfdf5;color:#16a34a;}
.action a{text-decoration:none;padding:6px 12px;border-radius:8px;background:#16a34a;color:white;font-size:0.85rem;}

/* TEST MODAL */
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);justify-content:center;align-items:center;}
.modal-box{background:white;padding:25px;width:400px;border-radius:18px;}
.card{border:1px solid #ddd;padding:12px;margin:8px 0;border-radius:10px;cursor:pointer;}
.card.active{background:#d1fae5;}

/* PAYMENT ANIMATION */
.pay-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);display:none;justify-content:center;align-items:center;}
.pay-box{background:rgba(255,255,255,0.95);padding:40px;width:360px;border-radius:20px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,0.2);}
.modern-loader{width:60px;height:60px;border:6px solid #e5e7eb;border-top:6px solid #0b8fa1;border-radius:50%;animation: spin 1s linear infinite;margin:0 auto 20px;}
.checkmark{width:80px;height:80px;stroke:#16a34a;stroke-width:3;stroke-linecap:round;stroke-linejoin:round;fill:none;margin:0 auto 15px;}
.checkmark-circle{stroke-dasharray:166;stroke-dashoffset:166;animation: stroke 0.6s forwards;}
.checkmark-check{stroke-dasharray:48;stroke-dashoffset:48;animation: stroke 0.4s 0.6s forwards;}
.loader-section h3, .success-section h3{margin-bottom:10px;color:#0b8fa1;}
.success-section{display:none;}

@keyframes spin{100%{transform:rotate(360deg);}}
@keyframes stroke{to{stroke-dashoffset:0;}}
</style>
</head>
<body>

<div class="hero">Billing & Payments</div>
<nav><h3>HMS Billing</h3><a href="admin.php">← Dashboard</a></nav>

<div class="container">
<h2>Generate Bill</h2>
<form method="POST">
<select name="appointment" required>
<option value="">Select Accepted Appointment</option>
<?php
$q=mysqli_query($conn,"SELECT a.appointment_id,p.first_name,d.name FROM Appointments a JOIN Patients p ON a.patient_id=p.patient_id JOIN Doctors d ON a.doctor_id=d.doctor_id WHERE a.status='Accepted'");
while($r=mysqli_fetch_assoc($q)){
echo "<option value='{$r['appointment_id']}'>Appt #{$r['appointment_id']} - {$r['first_name']} (Dr. {$r['name']})</option>";
}
?>
</select>
<select name="method"><option>Cash</option><option>Card</option><option>UPI</option></select>
<input type="number" name="extra" placeholder="Discount(Adjustment)" step="0.01" value="">
<button type="button" class="test-btn" onclick="openModal()">+ Add Tests</button>
<input type="hidden" name="tests_total" id="tests_total" value="0">
<p><b>Tests Total: ₹<span id="testDisplay">0</span></b></p>
<button name="generate">Generate Bill</button>
</form>
</div>

<div class="container">
<h2>Billing Records</h2>
<table>
<tr><th>ID</th><th>Appointment</th><th>Amount</th><th>Method</th><th>Status</th><th>Action</th></tr>
<?php
$b=mysqli_query($conn,"SELECT * FROM Billing ORDER BY bill_id DESC");
if($b && mysqli_num_rows($b)>0){
while($row=mysqli_fetch_assoc($b)){
$status = $row['payment_status'] ?: 'Pending';
$statusClass = ($status=="Paid") ? "paid":"pending";
echo "<tr>
<td>{$row['bill_id']}</td>
<td>{$row['appointment_id']}</td>
<td>₹{$row['total_amount']}</td>
<td>{$row['payment_method']}</td>
<td><span class='badge $statusClass'>$status</span></td>
<td class='action'>";
if($status=="Pending"){
echo "<a href='javascript:void(0)' onclick='startPayment({$row['bill_id']})'>Mark Paid</a>";
}else{echo "-";}
echo "</td></tr>";
}}else{echo "<tr><td colspan='6'>No Billing Records Found</td></tr>";}
?>
</table>
</div>

<!-- TEST MODAL -->
<div class="modal" id="modal">
<div class="modal-box">
<h3>Select Tests</h3>
<div class="card" onclick="toggleTest(this,500)">Blood Test - ₹500</div>
<div class="card" onclick="toggleTest(this,800)">X-Ray - ₹800</div>
<div class="card" onclick="toggleTest(this,300)">ECG - ₹300</div>
<hr>
<h4>Package</h4>
<div class="card" onclick="selectPackage(this,1600)">Full Body Checkup - ₹1600</div>
<p><b>Total: ₹<span id="modalTotal">0</span></b></p>
<button onclick="applyTests()">Add to Bill</button>
</div>
</div>

<!-- PAYMENT MODAL -->
<div id="paymentModal" class="pay-overlay">
<div class="pay-box">
<div class="loader-section" id="loaderSection">
<div class="modern-loader"></div>
<h3>Processing Payment</h3>
<p>Please wait while we complete the transaction...</p>
</div>
<div class="success-section" id="successSection">
<svg class="checkmark" viewBox="0 0 52 52">
<circle class="checkmark-circle" cx="26" cy="26" r="25"/>
<path class="checkmark-check" d="M14 27l7 7 16-16"/>
</svg>
<h3>Payment Successful</h3>
<p>Transaction completed successfully</p>
</div>
</div>
</div>

<script>
/* TESTS */
let total = 0;
function openModal(){document.getElementById("modal").style.display="flex";}
function toggleTest(el,price){el.classList.toggle("active"); total += el.classList.contains("active")?price:-price; updateTotal();}
function selectPackage(el,price){total=price; document.querySelectorAll(".card").forEach(c=>c.classList.remove("active")); el.classList.add("active"); updateTotal();}
function updateTotal(){document.getElementById("modalTotal").innerText = total;}
function applyTests(){document.getElementById("tests_total").value = total; document.getElementById("testDisplay").innerText = total; document.getElementById("modal").style.display="none";}

/* PAYMENT */
function startPayment(id){
const modal=document.getElementById("paymentModal");
const loader=document.getElementById("loaderSection");
const success=document.getElementById("successSection");
modal.style.display="flex"; loader.style.display="block"; success.style.display="none";
setTimeout(()=>{
loader.style.display="none"; success.style.display="block";
setTimeout(()=>{window.location.href='billing.php?paid='+id;},1500);
},1800);
}

/* POPUP */
<?php if(isset($popupTitle)){echo "alert('$popupTitle:\\n$popupMsg');";} ?>
</script>
</body>
</html>