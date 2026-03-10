
<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    $check = $conn->prepare("SELECT * FROM users WHERE email=? OR username=?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        showMessage("User already exists! Please log in.", "error");
    } else {

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();

        showMessage("Account created successfully! Please log in.", "success");
    }
}

function showMessage($message, $type) {

    $color = ($type === "success") ? "#ff8f00" : "#ff3d00";

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
      <meta charset='UTF-8'>
      <title>Registration</title>

      <style>
        body{
        background:#121212;
        color:#fff;
        font-family:Poppins;
        display:flex;
        justify-content:center;
        align-items:center;
        height:100vh;
        margin:0;
        }

        .message-box{
        background:#1e1e1e;
        padding:40px;
        border-radius:12px;
        text-align:center;
        box-shadow:0 0 20px rgba(255,136,0,0.3);
        }

        h2{color:$color;}

        button{
        background:$color;
        border:none;
        padding:10px 20px;
        border-radius:8px;
        font-weight:600;
        cursor:pointer;
        }

        button:hover{
        background:#fff;
        color:#000;
        }
      </style>
    </head>

    <body>

    <div class='message-box'>
    <h2>$message</h2>

    <button onclick=\"window.location.href='index.php'\">Return Home</button>

    </div>

    </body>
    </html>
    ";

    exit;
}
?>