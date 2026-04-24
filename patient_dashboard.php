<?php
session_start();
if(!isset($_SESSION['patient'])){
    header("Location: patient_login.php");
    exit();
}

include "db.php";
$id=$_SESSION['patient'];

$p=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM Patients WHERE patient_id=$id
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Patient Dashboard | HMS</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

/* BACKGROUND */
body{
min-height:100vh;
background:linear-gradient(135deg,#e0f2fe,#f8fafc);
color:#0f172a;
}

/* NAVBAR */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:14px 6%;
background:white;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
position:sticky;
top:0;
z-index:1000;
}

.logo{
display:flex;
align-items:center;
gap:10px;
font-size:1.3rem;
font-weight:600;
color:#0b8fa1;
}

.logo img{width:42px;}

nav a{
text-decoration:none;
color:#334155;
font-weight:500;
}

/* DASHBOARD */
.dashboard{
padding:50px 6%;
max-width:1200px;
margin:auto;
}

/* WELCOME CARD */
.welcome{
background:linear-gradient(135deg,#0b8fa1,#0369a1);
color:white;
padding:35px;
border-radius:22px;
box-shadow:0 20px 40px rgba(11,143,161,0.3);
margin-bottom:40px;
}

.welcome h2{
margin-bottom:6px;
}

.welcome p{
opacity:0.9;
font-size:0.95rem;
}

/* ACTION CARDS */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
gap:25px;
}

.card{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 12px 30px rgba(0,0,0,0.08);
text-decoration:none;
color:#0f172a;
transition:0.3s;
position:relative;
overflow:hidden;
}

.card::after{
content:"";
position:absolute;
top:0;
left:0;
width:100%;
height:4px;
background:#0b8fa1;
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 25px 50px rgba(0,0,0,0.12);
}

.card i{
font-size:2rem;
color:#0b8fa1;
margin-bottom:12px;
}

.card h3{
margin-bottom:6px;
}

.card p{
font-size:0.9rem;
color:#475569;
}

/* FOOTER */
footer{
text-align:center;
padding:20px;
color:#64748b;
font-size:0.85rem;
margin-top:50px;
}
</style>
</head>

<body>

<!-- NAV -->
<nav>
<div class="logo">
<img src="v987-18a.png"> HMS
</div>
<a href="logout.php">Logout</a>
</nav>

<!-- DASHBOARD -->
<div class="dashboard">

<!-- WELCOME -->
<div class="welcome">
<h2>Welcome, <?php echo $p['first_name']; ?> 👋</h2>
<p><?php echo $p['email']; ?></p>
</div>

<!-- ACTIONS -->
<div class="cards">

<a href="patient_book_appointment.php" class="card">
<i class="fa-solid fa-calendar-check"></i>
<h3>Book Appointment</h3>
<p>Schedule a visit with your doctor</p>
</a>

<a href="my_appointments.php" class="card">
<i class="fa-solid fa-clipboard-list"></i>
<h3>My Appointments</h3>
<p>View status & history of appointments</p>
</a>

<a href="bill.php" class="card">
<i class="fa-solid fa-file-invoice-dollar"></i>
<h3>My Bills</h3>
<p>Check payments & invoices</p>
</a>

</div>

</div>

<footer>
© 2026 Hospital Management System | Patient Portal
</footer>

</body>
</html>
