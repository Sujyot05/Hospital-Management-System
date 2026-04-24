<?php
session_start();

/* MOBILE SESSION FIX */
session_set_cookie_params(86400);

/* ADMIN CREDENTIALS */
$ADMIN_ID = "admin@pune.org";
$ADMIN_PASS = "Pass@123";

/* LOGIN CHECK */
if(isset($_POST['email'])){
    if($_POST['email']==$ADMIN_ID && $_POST['password']==$ADMIN_PASS){
        $_SESSION['admin']=$ADMIN_ID;
    }else{
        echo "<script>alert('Wrong ID / Password');window.location='admin.html';</script>";
        exit();
    }
}

/* PROTECT */
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard | HMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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

/* HEADER */
nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:14px 6%;
background:rgba(255,255,255,0.9);
backdrop-filter:blur(10px);
box-shadow:0 6px 20px rgba(0,0,0,0.08);
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

.nav-right{
display:flex;
align-items:center;
gap:20px;
}

.nav-right a{
text-decoration:none;
color:#334155;
font-weight:500;
}

/* BACK BUTTON */
.back-btn{
padding:8px 14px;
border-radius:10px;
background:#e0f2fe;
color:#0369a1;
font-weight:600;
}

/* MAIN */
.dashboard{
padding:40px 6%;
text-align:center;
}

.dashboard h1{
margin-bottom:6px;
}

.dashboard p{
color:#475569;
}

/* CARDS */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:25px;
margin-top:40px;
}

.card{
background:white;
padding:30px;
border-radius:18px;
box-shadow:0 10px 30px rgba(0,0,0,0.06);
text-decoration:none;
color:#0f172a;
transition:0.3s;
position:relative;
}

.card:hover{
transform:translateY(-6px);
box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

.card h3{
margin-bottom:8px;
color:#0b8fa1;
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
margin-top:40px;
}
</style>
</head>

<body>

<!-- HEADER -->
<nav>
<div class="logo">
<img src="v987-18a.png"> HMS Admin
</div>

<div class="nav-right">
<a class="back-btn" href="index.html">← Home</a>
<a href="logout.php">Logout</a>
</div>
</nav>

<!-- DASHBOARD -->
<div class="dashboard">
<h1>Admin Dashboard</h1>
<p>admin@pune.org</p>

<div class="cards">

<a href="manage_doctors.php" class="card">
<h3>Doctors</h3>
<p>Add, update & manage doctors</p>
</a>

<a href="manage_patients.php" class="card">
<h3>Patients</h3>
<p>Patient records & profiles</p>
</a>

<a href="manage_appointments.php" class="card">
<h3>Appointments</h3>
<p>Approve & schedule bookings</p>
</a>

<a href="billing.php" class="card">
<h3>Billing</h3>
<p>Payments & invoices</p>
</a>

<a href="patient_history.php" class="card">
<h3>Patient History</h3>
<p>Medical visit records</p>
</a>

<a href="reports.php" class="card">
<h3>Reports</h3>
<p>Analytics & summary</p>
</a>

</div>
</div>

<footer>
© 2026 Hospital Management System | Admin Panel
</footer>

</body>
</html>
