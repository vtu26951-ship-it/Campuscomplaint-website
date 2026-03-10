
<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit();
}
include("config.php");

$result = $conn->query("SELECT * FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - View Feedback</title>
    <style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #121212;
  color: #f5f5f5;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

/* HEADER */
header {
  background: linear-gradient(90deg, #ff6f00, #ff8f00);
  color: #ffffff;
  padding: 1.2rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 10px rgba(255,136,0,0.4);
  animation: slideDown 0.8s ease;
}

@keyframes slideDown {
  from { transform: translateY(-100%); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

header h1 {
  font-size: 1.7rem;
  font-weight: 600;
  margin: 0;
  letter-spacing: 0.5px;
}

a {
  color: #ffffff;
  text-decoration: none;
  background: rgba(255,255,255,0.15);
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

a:hover {
  background: rgba(255,255,255,0.3);
}

/* TABLE */
table {
  width: 90%;
  margin: 2.5rem auto;
  border-collapse: collapse;
  background: #1e1e1e;
  box-shadow: 0 6px 20px rgba(255,136,0,0.2);
  border-radius: 12px;
  overflow: hidden;
  animation: fadeIn 1s ease;
}

th, td {
  padding: 14px 16px;
  border-bottom: 1px solid #333;
  text-align: left;
}

th {
  background: linear-gradient(90deg, #ff6f00, #ff8f00);
  color: #fff;
  text-transform: uppercase;
  font-size: 0.9rem;
}

tr {
  transition: all 0.3s ease;
}

tr:nth-child(even) {
  background-color: #181818;
}

tr:hover {
  background: #222;
  transform: scale(1.01);
}

/* ANIMATIONS */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* STAR RATING */
td:nth-child(4) {
  color: #ffb300;
  font-size: 1.1rem;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  table {
    width: 100%;
    font-size: 0.9rem;
  }
  header h1 {
    font-size: 1.3rem;
  }
  a {
    padding: 6px 12px;
  }
}
    </style>
</head>
<body>

<header>
  <h1>Feedback Management</h1>
  <a href="admin.php">← Back to Dashboard</a>
</header>

<table>
  <tr>
    <th>ID</th>
    <th>Username</th>
    <th>Feedback</th>
    <th>Rating</th>
    <th>Date</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['feedback']) ?></td>
    <td><?= str_repeat('⭐', $row['rating']) ?></td>
    <td><?= $row['created_at'] ?></td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
