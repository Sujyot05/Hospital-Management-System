<?php
session_start();
if(!isset($_SESSION['patient'])){
    header("Location: patient_login.php");
    exit();
}

include "db.php";
$pid = $_SESSION['patient'];

if(isset($_POST['book'])){
    $doc   = $_POST['doctor'];
    $date  = $_POST['date'];
    $time  = $_POST['time'];
    $reason= $_POST['reason'];
    $govid = $_POST['govt_id'];

    /* TIME VALIDATION (09:00 - 16:00) */
    if($time < "09:00" || $time > "16:00"){
        echo "<script>alert('Appointments allowed only between 9 AM and 4 PM');</script>";
    }else{
        /* save govt id (if not already saved) */
        mysqli_query($conn,"
        UPDATE Patients SET govt_id='$govid'
        WHERE patient_id=$pid
        ");
mysqli_query($conn,"
INSERT INTO Appointments
(patient_id,doctor_id,appointment_date,appointment_time,reason,status)
VALUES('$pid','$doc','$date','$time','$reason','Pending')
");

echo "<script>
alert('Appointment request sent for approval');
window.location.href='patient_dashboard.php';
</script>";
    
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment | HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
min-height:100vh;
background:linear-gradient(135deg,#e0f2fe,#f8fafc);
display:flex;
justify-content:center;
align-items:center;
}

/* CARD */
.card{
background:white;
width:420px;
padding:40px;
border-radius:22px;
box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

.card h2{
color:#0b8fa1;
margin-bottom:5px;
}

.card p{
font-size:0.9rem;
color:#64748b;
margin-bottom:25px;
}

/* FORM */
input, select, textarea{
width:100%;
padding:14px;
margin-bottom:14px;
border-radius:10px;
border:1px solid #cbd5e1;
font-size:0.95rem;
}

textarea{resize:none;height:80px;}

input:focus, select:focus, textarea:focus{
outline:none;
border-color:#0b8fa1;
}

/* BUTTON */
button{
width:100%;
padding:14px;
border:none;
border-radius:10px;
background:#0b8fa1;
color:white;
font-size:1rem;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#0369a1;
transform:translateY(-2px);
}

/* NOTE */
.note{
font-size:0.8rem;
color:#475569;
margin-top:10px;
text-align:center;
}
</style>
</head>

<body>

<div class="card">
<h2>Book Appointment</h2>
<p>Select doctor and preferred time</p>

<form method="POST">

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

<input type="time" name="time" min="09:00" max="16:00" required>

<input type="text" name="govt_id" placeholder="Government ID (Aadhaar / PAN)" required>

<textarea name="reason" placeholder="Reason for visit (optional)"></textarea>

<button name="book">Request Appointment</button>

</form>

<p class="note">
⏰ Appointments available only between <b>9:00 AM – 4:00 PM</b>
</p>
</div>

</body>
</html>
