<?php
include("config.php");
session_start();

$msg = "";

if(isset($_POST['register']))
{
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $check = mysqli_query($conn,"SELECT id FROM login WHERE email='$email'");

    if(mysqli_num_rows($check) > 0)
    {
        $msg = "❌ Email already exists!";
    }
    else
    {
        mysqli_query($conn,"
            INSERT INTO login (name,email,password)
            VALUES ('$name','$email','$password')
        ");

        header("Location: login.php?registered=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register | Courier System</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;

    background:
        linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.85)),
        url("https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=1600");
    background-size:cover;
    background-position:center;
}

.container{
    width:420px;
    padding:35px;
    border-radius:18px;

    background:rgba(255,255,255,0.95);
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    backdrop-filter:blur(8px);
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#0f172a;
    font-size:26px;
}

.error{
    color:red;
    text-align:center;
    font-size:14px;
    margin-bottom:10px;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#2563eb;
    box-shadow:0 0 5px rgba(37,99,235,0.3);
}

button{
    width:100%;
    padding:12px;
    background:#0f172a;
    color:white;
    border:none;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    margin-top:10px;
    transition:0.3s;
}

button:hover{
    background:#2563eb;
}

.footer{
    text-align:center;
    margin-top:15px;
    font-size:14px;
}

.footer a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}
</style>

</head>

<body>

<div class="container">

    <h2> Create Account</h2>

    <p class="error"><?php echo $msg; ?></p>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Register</button>

    </form>

    <div class="footer">
        Already have an account? <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>