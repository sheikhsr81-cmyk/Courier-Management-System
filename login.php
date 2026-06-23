<?php
include("config.php");
session_start();

$msg = "";

if(isset($_POST['login']))
{
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $query = mysqli_query($conn,"
        SELECT * FROM login 
        WHERE email='$email' AND password='$password'
    ");

    if(mysqli_num_rows($query) == 1)
    {
        $user = mysqli_fetch_assoc($query);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: index.php");
        exit();
    }
    else
    {
        $msg = "❌ Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login | Courier System</title>

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
        linear-gradient(rgba(15,23,42,0.88), rgba(15,23,42,0.88)),
        url("https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600");
    background-size:cover;
    background-position:center;
}

.box{
    width:380px;
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

.msg{
    text-align:center;
    color:red;
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

<div class="box">

    <h2>Login</h2>

    <p class="msg"><?php echo $msg; ?></p>

    <form method="POST">

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <button name="login">Login</button>

    </form>

    <div class="footer">
        Don't have an account? <a href="register.php">Register</a>
    </div>

</div>

</body>
</html>