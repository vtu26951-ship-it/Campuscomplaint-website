<?php
// ✅ Force HTTPS redirection (InfinityFree auto SSL)
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: " . $redirect);
    exit();
}

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
include("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Campus Complaints Portal</title>
  <style>
    :root {
      --bg-light: #121212;
      --primary: #ff6f00;
      --secondary: #ff8f00;
      --accent: #ffa000;
      --deep-accent: #ffb300;
      --white: #ffffff;
      --text-dark: #f5f5f5;
      --text-muted: #b0b0b0;
      --radius: 12px;
      --shadow: rgba(255, 136, 0, 0.15);
      --transition: all 0.35s ease;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-light);
      margin: 0;
      color: var(--text-dark);
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

.header-buttons {
  display: flex;
  gap: 12px;
}

.header-buttons a {
  text-decoration: none;
  color: #ffffff;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
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

    .auth-buttons {
      display: flex;
      align-items: center;
    }

    .auth-buttons button {
      background-color: var(--accent);
      color: var(--bg-light);
      border: none;
      padding: 9px 16px;
      border-radius: var(--radius);
      margin-left: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 2px 6px var(--shadow);
    }

    .auth-buttons button:hover {
      background-color: var(--white);
      color: var(--primary);
      transform: translateY(-3px);
      box-shadow: 0 4px 12px var(--shadow);
    }

    /* MAIN CONTENT */
    .container {
      padding: 3rem 2rem;
      text-align: center;
      max-width: 1200px;
      margin: auto;
      animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .container h2 {
      font-weight: 600;
      color: var(--primary);
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
    }

    /* CATEGORY GRID */
    .category-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    /* CATEGORY CARD */
    .category-card {
      background: #1e1e1e;
      padding: 2rem 1.5rem;
      border-radius: var(--radius);
      box-shadow: 0 4px 15px var(--shadow);
      border-top: 6px solid var(--accent);
      transition: var(--transition);
      cursor: pointer;
      text-align: left;
      transform: scale(1);
      opacity: 0;
      animation: cardFadeIn 0.8s ease forwards;
    }

    .category-card:nth-child(1) { animation-delay: 0.2s; }
    .category-card:nth-child(2) { animation-delay: 0.4s; }
    .category-card:nth-child(3) { animation-delay: 0.6s; }
    .category-card:nth-child(4) { animation-delay: 0.8s; }

    @keyframes cardFadeIn {
      from { transform: translateY(30px) scale(0.95); opacity: 0; }
      to { transform: translateY(0) scale(1); opacity: 1; }
    }

    .category-card h3 {
      color: var(--accent);
      font-size: 1.2rem;
      margin-bottom: 0.6rem;
    }

    .category-card p {
      color: var(--text-muted);
      font-size: 0.95rem;
      margin: 0;
    }

    .category-card:hover {
      transform: translateY(-6px) scale(1.03);
      background: linear-gradient(180deg, var(--accent), #1e1e1e);
      box-shadow: 0 8px 20px rgba(255,136,0,0.3);
    }

    /* FOOTER */
    footer {
      background: linear-gradient(135deg, #ff8f00, #ff6f00);
      color: var(--white);
      text-align: center;
      padding: 1.5rem;
      margin-top: 3rem;
      font-weight: 500;
      font-size: 0.9rem;
      box-shadow: 0 -3px 10px var(--shadow);
    }

    footer a {
      color: var(--accent);
      text-decoration: none;
    }

    /* MODALS */
    .modal {
      display: none;
      position: fixed;
      z-index: 10;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.7);
      backdrop-filter: blur(4px);
    }

    .modal-content {
      background-color: #1c1c1c;
      color: var(--white);
      margin: 8% auto;
      padding: 2.5rem 2rem;
      border-radius: var(--radius);
      width: 360px;
      text-align: center;
      box-shadow: 0 6px 20px var(--shadow);
      animation: scaleUp 0.4s ease;
    }

    @keyframes scaleUp {
      from { transform: scale(0.8); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .close {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: var(--accent);
    }

    .modal h2 {
      color: var(--accent);
      margin-bottom: 1.2rem;
    }

    .modal input {
      width: 90%;
      padding: 11px;
      margin: 10px 0;
      border: 1px solid var(--accent);
      border-radius: 6px;
      background-color: #2a2a2a;
      color: var(--white);
      transition: var(--transition);
    }

    .modal input:focus {
      border-color: var(--secondary);
      outline: none;
      box-shadow: 0 0 6px rgba(255,136,0,0.4);
    }

    .modal button {
      background-color: var(--accent);
      color: var(--bg-light);
      border: none;
      padding: 11px 18px;
      border-radius: 8px;
      cursor: pointer;
      transition: var(--transition);
      width: 90%;
      font-weight: 500;
    }

    .modal button:hover {
      background-color: var(--primary);
      transform: translateY(-2px);
    }

    .switch-link {
      margin-top: 10px;
      color: var(--accent);
      cursor: pointer;
      font-size: 14px;
      transition: 0.2s;
    }

    .switch-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
<header>
  <h1>Campus Complaints Portal</h1>
  <div class="auth-buttons">
    <?php if (isset($_SESSION['username'])): ?>
      <span style="margin-right: 10px; font-weight: 500; color: white;">
        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋
      </span>

      <button onclick="window.location.href='my_complaints.php'">View Complaints</button>

      <form action="logout.php" method="POST" style="display:inline;">
        <button type="submit">Logout</button>
      </form>
    <?php else: ?>
      <button onclick="openLogin()">Login</button>
      <button onclick="openSignup()">Sign Up</button>
    <?php endif; ?>
     <button class="feedback-btn" onclick="window.location.href='feedback.php'">Feedback</button>

  </div>
</header>

<div class="container" id="home">
  <h2>Select Complaint Category</h2>
  <div class="category-grid">
    <?php if (isset($_SESSION['username'])): ?>
      <div class="category-card" onclick="window.location.href='send_mail.php?category=Hostel Issues'">
        <h3>🏠 Hostel Issues</h3>
        <p>Water Leakage, Mess Food, Cleanliness</p>
      </div>
      <div class="category-card" onclick="window.location.href='send_mail.php?category=Library Issues'">
        <h3>📚 Library Issues</h3>
        <p>Book Shortage, WiFi Problems, Seating Issues</p>
      </div>
      <div class="category-card" onclick="window.location.href='send_mail.php?category=Transport Issues'">
        <h3>🚌 Transport Issues</h3>
        <p>Bus Delay, Safety Concerns, Staff Behaviour</p>
      </div>
      <div class="category-card" onclick="window.location.href='send_mail.php?category=Academic Issues'">
        <h3>🏫 Academic Issues</h3>
        <p>Teaching Quality, Schedule Conflicts, Materials</p>
      </div>
    <?php else: ?>
      <div class="category-card" onclick="alert('Please log in first!')">
        <h3>🏠 Hostel Issues</h3>
        <p>Water Leakage, Mess Food, Cleanliness</p>
      </div>
      <div class="category-card" onclick="alert('Please log in first!')">
        <h3>📚 Library Issues</h3>
        <p>Book Shortage, WiFi Problems, Seating Issues</p>
      </div>
      <div class="category-card" onclick="alert('Please log in first!')">
        <h3>🚌 Transport Issues</h3>
        <p>Bus Delay, Safety Concerns, Staff Behaviour</p>
      </div>
      <div class="category-card" onclick="alert('Please log in first!')">
        <h3>🏫 Academic Issues</h3>
        <p>Teaching Quality, Schedule Conflicts, Materials</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<footer>
  © 2025 Campus Complaints System | Designed by Karthikeya Reddy, Nijam, and ChennaKeshava Reddy.<br><br><br>
Contact: campuscomplaintssystem@gmail.com
</footer>

<!-- LOGIN MODAL -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLogin()">&times;</span>
    <h2>Login</h2>
    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
    <p style="text-align:center; margin-top:10px;">
      <a href="forgot_password.php" style="color:#ff9800; text-decoration:none; font-weight:bold;">
        Forgot Password?
      </a>
    </p>
    <div class="switch-link" onclick="switchToSignup()">Don’t have an account? Create one</div>
  </div>
</div>

<!-- SIGNUP MODAL -->
<div id="signupModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeSignup()">&times;</span>
    <h2>Create Account</h2>
    <form method="POST" action="register.php">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Register</button>
    </form>
    <div class="switch-link" onclick="switchToLogin()">Already have an account? Login</div>
  </div>
</div>

<script>
  function openLogin() { document.getElementById('loginModal').style.display = 'block'; }
  function closeLogin() { document.getElementById('loginModal').style.display = 'none'; }

  function openSignup() { document.getElementById('signupModal').style.display = 'block'; }
  function closeSignup() { document.getElementById('signupModal').style.display = 'none'; }

  function switchToSignup() { closeLogin(); openSignup(); }
  function switchToLogin() { closeSignup(); openLogin(); }

  window.onclick = function(event) {
    const login = document.getElementById('loginModal');
    const signup = document.getElementById('signupModal');
    if (event.target == login) login.style.display = 'none';
    if (event.target == signup) signup.style.display = 'none';
  };
</script>
</body>
</html>
