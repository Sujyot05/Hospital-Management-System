<?php
session_start();
if(!isset($_SESSION['patient'])){
    header("Location: patient_login.php");
    exit();
}

include "db.php";
$id = $_SESSION['patient'];

/* Cancel by patient */
if(isset($_GET['cancel'])){
    $aid = $_GET['cancel'];
    mysqli_query($conn,"
        UPDATE Appointments 
        SET status='Cancelled by User'
        WHERE appointment_id=$aid
        AND patient_id=$id
    ");

    echo "<script>
    alert('Appointment cancelled successfully');
    window.location.href='my_appointments.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Appointments | HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
color:#0f172a;
}

/* HEADER */
header{
padding:20px 6%;
display:flex;
justify-content:space-between;
align-items:center;
}

header h2{
color:#0b8fa1;
}

header a{
text-decoration:none;
color:#334155;
font-weight:500;
}

/* CONTAINER */
.container{
max-width:1100px;
margin:20px auto 60px;
padding:0 20px;
}

/* CARD LIST */
.card{
background:white;
border-radius:18px;
padding:25px;
margin-bottom:20px;
box-shadow:0 15px 35px rgba(0,0,0,0.08);
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
transition:0.3s;
}

.card:hover{
transform:translateY(-4px);
box-shadow:0 25px 50px rgba(0,0,0,0.12);
}

/* LEFT */
.info{
display:flex;
flex-direction:column;
gap:6px;
}

.info h3{
color:#0b8fa1;
}

.info span{
font-size:0.9rem;
color:#475569;
}

/* STATUS BADGES */
.status{
padding:6px 14px;
border-radius:20px;
font-size:0.8rem;
font-weight:600;
text-align:center;
display:inline-block;
}

.pending{
background:#fff7ed;
color:#f97316;
}

.accepted{
background:#ecfdf5;
color:#16a34a;
}

.cancelled{
background:#fee2e2;
color:#dc2626;
}

/* ACTION */
.action{
display:flex;
align-items:center;
gap:15px;
margin-top:10px;
}

.cancel-btn{
background:#dc2626;
color:white;
padding:8px 14px;
border-radius:10px;
text-decoration:none;
font-size:0.85rem;
transition:0.3s;
}

.cancel-btn:hover{
background:#b91c1c;
}

/* EMPTY */
.empty{
text-align:center;
color:#64748b;
margin-top:80px;
}
</style>
</head>

<body>

<header>
<h2>My Appointments</h2>
<a href="patient_dashboard.php">← Back to Dashboard</a>
</header>

<div class="container">

<?php
$q = mysqli_query($conn,"
SELECT a.*, d.name
FROM Appointments a
JOIN Doctors d ON a.doctor_id=d.doctor_id
WHERE a.patient_id=$id
ORDER BY appointment_date DESC
");

if(mysqli_num_rows($q)==0){
    echo "<div class='empty'>
    <i class='fa-solid fa-calendar-xmark fa-2x'></i>
    <p>No appointments found</p>
    </div>";
}

while($r = mysqli_fetch_assoc($q)){

$statusClass = "pending";
$statusText  = $r['status'];

if($r['status']=="Accepted"){
    $statusClass="accepted";
}
if($r['status']=="Cancelled by User"){
    $statusClass="cancelled";
    $statusText="Cancelled by You";
}
if($r['status']=="Cancelled"){
    $statusClass="cancelled";
}

echo "
<div class='card'>
    <div class='info'>
        <h3>Dr. {$r['name']}</h3>
        <span>📅 {$r['appointment_date']} | ⏰ {$r['appointment_time']}</span>
        <span class='status $statusClass'>$statusText</span>
    </div>

    <div class='action'>
";

if($r['status']=="Pending" || $r['status']=="Accepted"){
    echo "<a class='cancel-btn' href='?cancel={$r['appointment_id']}'>
    Cancel Appointment
    </a>";
}

echo "
    </div>
</div>
";
}
?>

</div>

</body>
</html>
