<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $urgency = $_POST['urgency'];
    $category = $_POST['category'];
    $file_name = "";

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {

        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["attachment"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $file_name = $target_file;
        }
    }

    $user = $_SESSION['username'];

    $stmt = $conn->prepare(
        "INSERT INTO complaints (username, title, description, urgency, category, attachment, status) 
         VALUES (?, ?, ?, ?, ?, ?, 'unviewed')"
    );

    $stmt->bind_param("ssssss", $user, $title, $description, $urgency, $category, $file_name);

    if ($stmt->execute()) {
        header("Location: success.HTML");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : "General";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Complaint | Campus Complaints</title>
<style>
  :root {
    --bg: #0e0e0e;
    --card: #1a1a1a;
    --accent: #ff7a00;
    --accent-dark: #cc6300;
    --text: #f1f1f1;
    --text-light: #cccccc;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg);
    color: var(--text);
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fadeIn 0.8s ease-in-out;
  }

  header {
    width: 100%;
    background: linear-gradient(90deg, #000, var(--accent-dark));
    color: var(--text);
    text-align: center;
    padding: 1.5rem 0;
    font-size: 1.6rem;
    letter-spacing: 0.5px;
    box-shadow: 0 0 15px rgba(255, 122, 0, 0.3);
  }

  form {
    background: var(--card);
    width: 420px;
    margin-top: 3rem;
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 0 25px rgba(255, 122, 0, 0.1);
    transition: all 0.4s ease;
    animation: popUp 0.9s ease;
  }

  form:hover {
    box-shadow: 0 0 35px rgba(255, 122, 0, 0.2);
  }

  form h2 {
    margin-bottom: 1rem;
    color: var(--accent);
    text-align: center;
    font-size: 1.4rem;
  }

  input, textarea, select {
    width: 100%;
    padding: 12px;
    margin: 12px 0;
    background: #111;
    border: 1px solid #333;
    color: var(--text);
    border-radius: 8px;
    font-size: 0.95rem;
    transition: border 0.3s ease, box-shadow 0.3s ease;
  }

  input:focus, textarea:focus, select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 8px rgba(255, 122, 0, 0.4);
    outline: none;
  }

  label {
    font-weight: 500;
    color: var(--accent);
    display: block;
    margin-top: 1rem;
  }

  button {
    width: 100%;
    background: var(--accent);
    color: #000;
    border: none;
    padding: 12px 0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 1.2rem;
    transition: all 0.3s ease;
  }

  button:hover {
    background: var(--accent-dark);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 0 10px rgba(255, 122, 0, 0.3);
  }

  a {
    color: var(--accent);
    text-decoration: none;
    display: inline-block;
    margin-top: 15px;
    text-align: center;
    width: 100%;
    transition: color 0.3s ease;
  }

  a:hover {
    color: #fff;
    text-decoration: underline;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes popUp {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
</style>
</head>
<body>

<header>
  <h1>Submit Your Complaint</h1>
</header>

<form method="POST" enctype="multipart/form-data">
  <h2><?php echo $category; ?></h2>

  <input type="hidden" name="category" value="<?php echo $category; ?>">

  <input type="text" name="title" placeholder="Complaint Title" required>

  <textarea name="description" placeholder="Describe your issue..." rows="5" required></textarea>

  <label for="urgency">Urgency Level:</label>
  <select name="urgency" required>
    <option value="Low">Low</option>
    <option value="Medium">Medium</option>
    <option value="High">High</option>
  </select>

  <label for="attachment">Attach File (optional):</label>
  <input type="file" name="attachment">

  <button type="submit">Submit Complaint</button>
  <a href="index.php">← Back to Home</a>
</form>

</body>
</html>
