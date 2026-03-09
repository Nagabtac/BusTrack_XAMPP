<?php 
session_start();
require 'db.php';

$message = "";
$message_type = ""; // success or error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    // ================= REGISTER LOGIC =================
    if ($action === "register") {
        $email = trim($_POST["email"]);
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if (empty($email) || empty($username) || empty($password)) {
            $message = "All fields are required.";
            $message_type = "error";
        } else {
            $check = $conn->prepare("SELECT id FROM users WHERE email=? OR username=?");
            $check->bind_param("ss", $email, $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $message = "Username or Email already taken.";
                $message_type = "error";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $username, $hashedPassword);

                if ($stmt->execute()) {
                    $message = "Registration successful! Please login.";
                    $message_type = "success";
                } else {
                    $message = "Error: Registration failed.";
                    $message_type = "error";
                }
                $stmt->close();
            }
            $check->close();
        }
    }

// ================= LOGIN LOGIC =================
if ($action === "login") {
    $username = trim($_POST["username"]); 
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $message = "Enter username and password.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                //Redirect to dashboard.php instead of commuterDashboard.php
                header("Location: dashboard.php");
                exit(); 
                
            } else {
                $message = "Invalid password.";
                $message_type = "error";
            }
        } else {
            $message = "Username not found.";
            $message_type = "error";
        }
        $stmt->close();
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BusTrack Auth</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#1da3b8;
    font-family:Arial, sans-serif;
}

.container{
    position:relative;
    max-width:380px;
    width:100%;
}

.forms{
    background:#fff;
    padding:35px 30px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.form{
    transition:0.3s ease;
}

.forms.show-signup .login-form{ display:none; }
.forms.show-signup .signup-form{ display:block; }
.signup-form{ display:none; }

.logo{
    text-align:center;
    margin-bottom:15px;
}

.logo h3{
    margin:5px 0 0;
    color:#2c6fb7;
}

.logo p{
    font-size:13px;
    color:#666;
}

.input-field{
    position:relative;
    margin-top:15px;
}

.input-field input{
    width:100%;
    padding:12px;
    border-radius:6px;
    border:none;
    background:#eee;
    outline:none;
    box-sizing:border-box;
}

.eye-icon{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#666;
}

button{
    width:100%;
    margin-top:20px;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#2c6fb7;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#1f5aa0;
}

.text{
    text-align:center;
    margin-top:15px;
    font-size:14px;
}

.text a{
    color:#2c6fb7;
    text-decoration:none;
    font-weight:bold;
}

/* ✅ MESSAGE COLORS */
.message{
    text-align:center;
    margin-bottom:10px;
    font-weight:bold;
}

.message.success{
    color:black;
}

.message.error{
    color:#d9534f;
}
</style>
</head>
<body>

<div class="container">
    <div class="forms">

        <?php if($message): ?>
            <div class="message <?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- LOGIN FORM -->
        <div class="form login-form">
            <div class="logo">
                <h3>🚌 BusTrack</h3>
                <p>Track your bus in real time</p>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="login">

                <div class="input-field">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-field">
                    <input type="password" class="password" name="password" placeholder="Password" required>
                    <i class='bx bx-hide eye-icon'></i>
                </div>

                <button type="submit">LOG IN</button>

                <div class="text">
                    Don't have an account? <a href="#" class="link">Sign up</a>
                </div>
            </form>
        </div>

        <!-- SIGNUP FORM -->
        <div class="form signup-form">
            <div class="logo">
                <h3>🚌 BusTrack</h3>
                <p>Join the community</p>
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="register">

                <div class="input-field">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-field">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-field">
                    <input type="password" class="password" name="password" placeholder="Password" required>
                    <i class='bx bx-hide eye-icon'></i>
                </div>

                <button type="submit">Register</button>

                <div class="text">
                    Already have an account? <a href="#" class="link">Sign in</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
const forms = document.querySelector(".forms"),
      pwShowHide = document.querySelectorAll(".eye-icon"),
      links = document.querySelectorAll(".link");

pwShowHide.forEach(eyeIcon => {
    eyeIcon.addEventListener("click", () => {
        let pwField = eyeIcon.parentElement.querySelector(".password");

        if (pwField.type === "password") {
            pwField.type = "text";
            eyeIcon.classList.replace("bx-hide", "bx-show");
        } else {
            pwField.type = "password";
            eyeIcon.classList.replace("bx-show", "bx-hide");
        }
    });
});

links.forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();
        forms.classList.toggle("show-signup");
    });
});
</script>

</body>
</html>