<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>

<style>

:root{
--bg:#121212;
--card:#1e1e1e;
--accent:#ff6f00;
--accent2:#ff8f00;
--text:#f5f5f5;
--muted:#b0b0b0;
}

body{
font-family:'Poppins',sans-serif;
background:linear-gradient(135deg,#000,#1a1a1a);
margin:0;
color:var(--text);
}

/* HEADER */

header{
background:linear-gradient(90deg,#ff6f00,#ff8f00);
padding:1.2rem 2rem;
display:flex;
justify-content:space-between;
align-items:center;
color:white;
box-shadow:0 4px 10px rgba(255,136,0,0.4);
}

header h1{
margin:0;
font-size:1.6rem;
}

.logout{
background:rgba(255,255,255,0.2);
padding:8px 16px;
border-radius:8px;
text-decoration:none;
color:white;
transition:0.3s;
}

.logout:hover{
background:rgba(255,255,255,0.35);
}

/* DASHBOARD */

.container{
text-align:center;
padding:4rem 2rem;
}

.container h2{
color:var(--accent);
margin-bottom:2rem;
}

/* CARDS */

.cards{
display:flex;
justify-content:center;
gap:2rem;
flex-wrap:wrap;
}

.card{
background:var(--card);
padding:2rem;
width:260px;
border-radius:12px;
border-top:5px solid var(--accent);
cursor:pointer;
transition:0.3s;
box-shadow:0 4px 15px rgba(255,136,0,0.2);
}

.card:hover{
transform:translateY(-6px) scale(1.05);
background:linear-gradient(135deg,#ff6f00,#1e1e1e);
}

.card h3{
margin:0 0 10px 0;
color:var(--accent);
}

.card p{
color:var(--muted);
margin:0;
}

/* FOOTER */

footer{
background:linear-gradient(90deg,#ff6f00,#ff8f00);
text-align:center;
padding:1rem;
margin-top:4rem;
color:white;
}

</style>

</head>

<body>

<header>
<h1>Campus Complaints Portal</h1>
<a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

<div class="cards">

<div class="card" onclick="window.location.href='index.php'">
<h3>📩 Submit Complaint</h3>
<p>Report an issue to the campus administration.</p>
</div>

<div class="card" onclick="window.location.href='my_complaints.php'">
<h3>📋 My Complaints</h3>
<p>Track the status of your submitted complaints.</p>
</div>

<div class="card" onclick="window.location.href='feedback.php'">
<h3>⭐ Give Feedback</h3>
<p>Share your experience about the system.</p>
</div>

</div>

</div>

<footer>
© <?php echo date("Y"); ?> Campus Complaint System
</footer>

</body>
</html>