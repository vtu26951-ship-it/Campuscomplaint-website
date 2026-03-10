<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("config.php");

// ✅ Only allow admins to access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
// ✅ Update complaint status
if (isset($_POST['update_status'])) {
    $id = intval($_POST['complaint_id']);
    $new_status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE complaints SET status='$new_status' WHERE id=$id");
}

// ✅ Delete resolved complaint
if (isset($_POST['delete_complaint'])) {
    $id = intval($_POST['complaint_id']);
    $conn->query("DELETE FROM complaints WHERE id=$id");
}

// ✅ Fetch complaints
$complaints = $conn->query("SELECT * FROM complaints WHERE status='unviewed' ORDER BY submitted_at DESC");
$progress   = $conn->query("SELECT * FROM complaints WHERE status='in_progress' ORDER BY submitted_at DESC");
$resolved   = $conn->query("SELECT * FROM complaints WHERE status='resolved' ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Campus Complaint System</title>

<style>
:root {
  --bg: #0e0e0e;
  --card: #1a1a1a;
  --accent: #ff6b00;
  --accent-light: #ffa64d;
  --text: #f5f5f5;
  --muted: #aaaaaa;
}

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #000000, #1a1a1a);
  color: var(--text);
  margin: 0;
  padding: 0;
}

header {
  background: linear-gradient(90deg, #000000, #1a1a1a);
  color: var(--text);
  padding: 1.2rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.6);
}

.top-right {
  position: absolute;
  top: 18px;
  right: 25px;
  display: flex;
  align-items: center;
  gap: 15px;
}

.welcome {
  color: var(--accent-light);
  font-weight: 500;
}

.logout-btn,
.feedback-btn {
  background: var(--accent);
  color: #fff;
  padding: 8px 16px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.logout-btn:hover,
.feedback-btn:hover {
  background: var(--accent-light);
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(255, 107, 0, 0.4);
}

.dashboard-cards {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin: 3rem auto;
  flex-wrap: wrap;
}

.card {
  background: var(--card);
  width: 280px;
  padding: 2rem 1.5rem;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.7);
  text-align: center;
  transition: all 0.4s ease;
  cursor: pointer;
  border-top: 4px solid var(--accent);
}

.card:hover { 
  transform: translateY(-6px) scale(1.05);
}

.section {
  display: none;
}

table {
  width: 90%;
  margin: 1rem auto 3rem;
  border-collapse: collapse;
  background: var(--card);
}

th, td { 
  padding: 14px; 
  border-bottom: 1px solid #2a2a2a; 
  text-align: center; 
}

th { 
  background-color: var(--accent);
  color: #fff;
}

select, button {
  padding: 8px 12px;
  border-radius: 6px;
  border: none;
}

button {
  background: var(--accent);
  color: white;
  cursor: pointer;
}

button:hover { 
  background: var(--accent-light);
}

.delete-btn { background: #B22222; }

footer {
  background: linear-gradient(90deg, #000000, #1a1a1a);
  color: var(--muted);
  text-align: center;
  padding: 1.2rem 0;
  margin-top: 3rem;
}
</style>
</head>

<body>

<header>
  <h1>Admin Dashboard - Campus Complaint System</h1>
  <div class="top-right">
    <span class="welcome">Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋</span>
    <a href="logout.php" class="logout-btn">Logout</a>
    <a href="view_feedback.php" class="feedback-btn">View Feedback</a>
  </div>
</header>

<div class="dashboard-cards" id="mainCards">
  <div class="card" onclick="showSection('unviewed')">
    <h3>📋 Unviewed Complaints</h3>
  </div>

  <div class="card" onclick="showSection('progress')">
    <h3>⚙️ In Progress</h3>
  </div>

  <div class="card" onclick="showSection('resolved')">
    <h3>✅ Resolved</h3>
  </div>
</div>

<?php
function renderTable($title, $data, $statuses) {
  echo "<div class='section' id='$title'>";
  echo "<h2 style='text-align:center;'>".ucfirst($title)." Complaints</h2>";

  echo "<table>
  <tr>
  <th>ID</th>
  <th>User</th>
  <th>Title</th>
  <th>Description</th>
  <th>Urgency</th>
  <th>Category</th>
  <th>File</th>
  <th>Submitted</th>
  <th>Action</th>
  </tr>";

  while($row = $data->fetch_assoc()) {

    echo "<tr>
    <td>{$row['id']}</td>
    <td>".htmlspecialchars($row['username'])."</td>
    <td>".htmlspecialchars($row['title'])."</td>
    <td>".htmlspecialchars($row['description'])."</td>
    <td>".htmlspecialchars($row['urgency'])."</td>
    <td>".htmlspecialchars($row['category'])."</td>
    <td>".($row['attachment'] ? "<a href='".htmlspecialchars($row['attachment'])."' target='_blank'>View</a>" : "-")."</td>
    <td>{$row['submitted_at']}</td>

    <td>
    <form method='POST'>
    <input type='hidden' name='complaint_id' value='{$row['id']}'>
    <select name='status'>";

    foreach($statuses as $s){
      echo "<option value='$s'>$s</option>";
    }

    echo "</select>
    <button type='submit' name='update_status'>Update</button>
    </form>";

    if ($title === 'resolved') {
      echo "<form method='POST'>
      <input type='hidden' name='complaint_id' value='{$row['id']}'>
      <button type='submit' name='delete_complaint' class='delete-btn'>Delete</button>
      </form>";
    }

    echo "</td></tr>";
  }

  echo "</table></div>";
}

renderTable('unviewed',$complaints,['in_progress','resolved']);
renderTable('progress',$progress,['resolved','unviewed']);
renderTable('resolved',$resolved,['in_progress','unviewed']);
?>

<footer>
© <?= date("Y") ?> Campus Complaint Management System
</footer>

<script>

function showSection(id){
document.getElementById('mainCards').style.display='none';
document.getElementById(id).style.display='block';
}

</script>

</body>
</html>