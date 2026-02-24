<?php
// ===== SIMPLE DEMO AUTH HANDLER =====
// NOTE: replace with database logic in production

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST["action"] == "register") {
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Demo only
        $message = "Registered successfully!";
    }

    if ($_POST["action"] == "login") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Demo only
        $message = "Login successful!";
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
    font-family:Arial, Helvetica, sans-serif;
}

.container{
    position:relative;
    max-width:380px;
    width:100%;
}

.forms{
    position:relative;
    background:#fff;
    padding:35px 30px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    overflow:hidden;
}

.form{
    transition:0.3s ease;
}

.forms.show-signup .login-form{
    display:none;
}

.forms.show-signup .signup-form{
    display:block;
}

.signup-form{
    display:none;
}

.logo{
    text-align:center;
    margin-bottom:15px;
}

.logo img{
    width:55px;
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

.message{
    text-align:center;
    color:green;
    margin-bottom:10px;
}
</style>
</head>
<body>

<div class="container">
    <div class="forms">

        <?php if($message): ?>
            <div class="message"><?= $message ?></div>
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
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-field">
                    <input type="password" class="password" name="password" placeholder="Password" required>
                    <i class='bx bx-hide eye-icon'></i>
                </div>

                <div class="text">
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit">LOG IN</button>

                <div class="text">
                    Don't have an account?
                    <a href="#" class="link">Sign up</a>
                </div>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="form signup-form">
            <div class="logo">
                <h3>🚌 BusTrack</h3>
                <p>Track your bus in real time</p>
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
                    Already have an account?
                    <a href="#" class="link">Sign in</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
// ===== YOUR PROVIDED JS LOGIC =====

const forms = document.querySelector(".forms"),
  pwShowHide = document.querySelectorAll(".eye-icon"),
  links = document.querySelectorAll(".link");

// Toggle password visibility
pwShowHide.forEach(eyeIcon => {
  eyeIcon.addEventListener("click", () => {
    let pwFields = eyeIcon.parentElement.querySelectorAll(".password");

    pwFields.forEach(password => {
      if (password.type === "password") {
        password.type = "text";
        eyeIcon.classList.replace("bx-hide", "bx-show");
        return;
      }
      password.type = "password";
      eyeIcon.classList.replace("bx-show", "bx-hide");
    });
  });
});

// Toggle login & signup forms
links.forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();
    forms.classList.toggle("show-signup");
  });
});
</script>

</body>
</html>