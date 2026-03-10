<?php
session_start();
include("config.php");

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_SESSION['username'];
  $feedback = $_POST['feedback'];
  $rating = $_POST['rating'];

  if (!empty($feedback)) {
    $stmt = $conn->prepare("INSERT INTO feedback (username, feedback, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $feedback, $rating);
    $stmt->execute();
    $message = "<p class='success'>✅ Feedback submitted successfully! Thank you.</p>";
  } else {
    $message = "<p class='error'>⚠️ Please enter feedback before submitting.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Feedback | Campus Complaints</title>

<style>
:root {
  --bg:#0e0e0e;
  --card:#1a1a1a;
  --accent:#ff6b00;
  --accent-light:#ffa64d;
  --text:#f5f5f5;
  --muted:#aaaaaa;
}

body{
font-family:Poppins;
background:linear-gradient(135deg,#000,#1a1a1a);
margin:0;
color:var(--text);
min-height:100vh;
display:flex;
flex-direction:column;
}

header{
background:linear-gradient(90deg,#000,#1a1a1a);
padding:1.2rem 2rem;
display:flex;
justify-content:space-between;
align-items:center;
}

header a{
color:#fff;
text-decoration:none;
background:rgba(255,255,255,0.1);
padding:6px 14px;
border-radius:6px;
}

form{
background:var(--card);
width:420px;
margin:4rem auto;
padding:2.5rem;
border-radius:14px;
border-top:4px solid var(--accent);
}

textarea,select{
width:100%;
padding:12px;
margin:12px 0;
border-radius:8px;
background:#111;
color:#fff;
border:1px solid #333;
}

button{
width:100%;
background:var(--accent);
color:#fff;
border:none;
padding:12px;
border-radius:8px;
cursor:pointer;
}

.success{
color:#4CAF50;
text-align:center;
}

.error{
color:#ff4d4f;
text-align:center;
}

footer{
margin-top:auto;
background:#000;
color:#aaa;
text-align:center;
padding:1rem;
}
</style>
</head>

<body>

<header>
<h1>Submit Feedback</h1>
<a href="index.php">← Back</a>
</header>

<form method="POST">

<h2 style="text-align:center;">We value your feedback!</h2>

<textarea name="feedback" rows="5" placeholder="Share your thoughts..." required></textarea>

<label>Rate your experience:</label>

<select name="rating" required>
<option value="5">⭐⭐⭐⭐⭐ Excellent</option>
<option value="4">⭐⭐⭐⭐ Good</option>
<option value="3">⭐⭐⭐ Average</option>
<option value="2">⭐⭐ Poor</option>
<option value="1">⭐ Very Poor</option>
</select>

<button type="submit">Submit Feedback</button>

<?php echo $message; ?>

</form>

<footer>
© <?= date("Y") ?> Campus Complaint Management System
</footer>

</body>
</html>