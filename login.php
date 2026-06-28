<?php
session_start();
require 'db.php';

if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Demo admin credentials
    $adminUser = "admin";
    $adminPass = "admin123";

    if ($username === $adminUser && $password === $adminPass) {

        $_SESSION['admin'] = $username;

        header("Location: index.php");
        exit;

    } else {

        $error = "Invalid username or password.";

    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Admin Login</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

body{

display:flex;
justify-content:center;
align-items:center;
height:100vh;

background:linear-gradient(135deg,#4f46e5,#7c3aed);

}

.login-card{

width:380px;

background:white;

padding:40px;

border-radius:18px;

box-shadow:0 20px 60px rgba(0,0,0,.2);

}

.login-card h2{

text-align:center;

margin-bottom:30px;

color:#4f46e5;

}

.login-card input{

width:100%;

padding:14px;

margin-bottom:18px;

border:1px solid #ddd;

border-radius:10px;

font-size:16px;

}

.login-card button{

width:100%;

padding:14px;

background:#4f46e5;

color:white;

border:none;

border-radius:10px;

font-size:16px;

cursor:pointer;

}

.login-card button:hover{

background:#4338ca;

}

.error{

background:#fee2e2;

color:#b91c1c;

padding:10px;

margin-bottom:20px;

border-radius:8px;

text-align:center;

}

</style>

</head>

<body>

<div class="login-card">

<h2><i class="fas fa-hotel"></i> LuxeStay Admin</h2>

<?php if($error): ?>

<div class="error"><?= $error ?></div>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="username"
placeholder="Username"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button type="submit">

Login

</button>

</form>

</div>

</body>

</html>