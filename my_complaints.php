<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include("config.php");
$user = $_SESSION['username'];
$result = $conn->query("SELECT * FROM complaints WHERE username='$user' ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Complaints | Campus Complaints</title>

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
color:white;
padding:1.2rem 2rem;
text-align:center;
box-shadow:0 4px 10px rgba(255,136,0,0.4);
}

/* TITLE */

h2{
text-align:center;
margin:2rem 0;
color:var(--accent);
}

/* TABLE */

table{
width:90%;
margin:auto;
border-collapse:collapse;
background:var(--card);
border-radius:12px;
overflow:hidden;
box-shadow:0 6px 20px rgba(255,136,0,0.2);
}

th,td{
padding:14px;
border-bottom:1px solid #333;
text-align:center;
}

th{
background:linear-gradient(90deg,#ff6f00,#ff8f00);
color:white;
text-transform:uppercase;
font-size:0.85rem;
}

tr{
transition:0.3s;
}

tr:nth-child(even){
background:#181818;
}

tr:hover{
background:#222;
transform:scale(1.01);
}

/* FILE LINK */

a{
color:#ffa000;
text-decoration:none;
}

a:hover{
text-decoration:underline;
}

/* STATUS BADGES */

.status{
padding:6px 10px;
border-radius:6px;
font-weight:600;
color:white;
}

.unviewed{background:#b3261e;}
.in_progress{background:#f39c12;}
.resolved{background:#27ae60;}

/* BACK BUTTON */

.back{
display:block;
text-align:center;
margin:2rem 0;
color:#ffa000;
font-weight:500;
text-decoration:none;
}

.back:hover{
text-decoration:underline;
}

/* FOOTER */

footer{
background:linear-gradient(90deg,#ff6f00,#ff8f00);
text-align:center;
padding:1rem;
margin-top:3rem;
color:white;
}

</style>
</head>

<body>

<header>
<h1>My Complaints</h1>
</header>

<h2>📋 Complaints Submitted by You</h2>

<table>

<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
<th>Urgency</th>
<th>Category</th>
<th>File</th>
<th>Status</th>
<th>Submitted</th>
</tr>

<?php if ($result && $result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['description']) ?></td>
<td><?= htmlspecialchars($row['urgency']) ?></td>
<td><?= htmlspecialchars($row['category']) ?></td>

<td>
<?php if (!empty($row['attachment'])): ?>
<a href="<?= htmlspecialchars($row['attachment']) ?>" target="_blank">View</a>
<?php else: ?>
-
<?php endif; ?>
</td>

<td>
<span class="status <?= $row['status'] ?>">
<?= ucfirst(str_replace('_',' ',$row['status'])) ?>
</span>
</td>

<td><?= $row['submitted_at'] ?></td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="8">No complaints found.</td>
</tr>

<?php endif; ?>

</table>

<a href="index.php" class="back">⬅ Back to Home</a>

<footer>
© <?php echo date("Y"); ?> Campus Complaint Management System
</footer>

</body>
</html>