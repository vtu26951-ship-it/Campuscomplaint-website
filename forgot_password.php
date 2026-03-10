<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $current_pass = md5($_POST['current_password']);
    $new_pass = md5($_POST['new_password']);
    $confirm_pass = md5($_POST['confirm_password']);

    if (empty($_POST['username']) || empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
        $message = "<span class='error'>⚠️ Please fill in all fields.</span>";
    } else {

        $check = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$current_pass'");

        if ($check && $check->num_rows > 0) {

            if ($new_pass !== $confirm_pass) {

                $message = "<span class='error'>⚠️ New passwords do not match.</span>";

            } else {

                $update = $conn->query("UPDATE users SET password='$new_pass' WHERE username='$username'");

                if ($update) {
                    $message = "<span class='success'>✅ Password successfully changed! <a href='index.php'>Login now</a></span>";
                } else {
                    $message = "<span class='error'>❌ Something went wrong. Try again.</span>";
                }

            }

        } else {
            $message = "<span class='error'>❌ Incorrect current password or username.</span>";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password | Campus Complaints</title>

<style>

:root {
--bg-light:#121212;
--primary:#ff6f00;
--secondary:#ff8f00;
--accent:#ffa000;
--text:#f5f5f5;
--muted:#b0b0b0;
}

body{
margin:0;
font-family:Poppins,sans-serif;
background:linear-gradient(135deg,#000,#1a1a1a);
color:var(--text);
display:flex;
align-items:center;
justify-content:center;
height:100vh;
}

.container{
background:#1e1e1e;
width:400px;
padding:40px;
border-radius:12px;
box-shadow:0 6px 20px rgba(255,136,0,0.25);
border-top:5px solid var(--accent);
text-align:center;
animation:fadeIn 0.7s ease;
}

h2{
color:var(--accent);
margin-bottom:5px;
}

.subtitle{
color:var(--muted);
font-size:14px;
margin-bottom:20px;
}

input{
width:100%;
padding:11px;
margin:10px 0;
border-radius:6px;
border:1px solid #333;
background:#111;
color:white;
}

input:focus{
border-color:var(--accent);
outline:none;
box-shadow:0 0 6px rgba(255,136,0,0.4);
}

button{
width:100%;
background:var(--accent);
border:none;
padding:12px;
color:black;
border-radius:8px;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:var(--secondary);
transform:translateY(-2px);
}

.message{
margin-top:15px;
}

.success{
color:#4CAF50;
}

.error{
color:#ff4d4f;
}

.back-link{
display:block;
margin-top:20px;
color:var(--accent);
text-decoration:none;
}

.back-link:hover{
text-decoration:underline;
}

@keyframes fadeIn{
from{opacity:0;transform:translateY(20px);}
to{opacity:1;transform:translateY(0);}
}

</style>
</head>

<body>

<div class="container">

<h2>🔒 Change Password</h2>
<p class="subtitle">Confirm your current password to set a new one.</p>

<form method="POST">

<input type="text" name="username" placeholder="Enter Username" required>

<input type="password" name="current_password" placeholder="Current Password" required>

<input type="password" name="new_password" placeholder="New Password" required>

<input type="password" name="confirm_password" placeholder="Confirm New Password" required>

<button type="submit">Update Password</button>

</form>

<div class="message"><?= $message ?></div>

<a href="index.php" class="back-link">⬅ Back to Home</a>

</div>

</body>
</html>