<?php
session_start();
include("db.php");

if (isset($_POST['login'])) {

    $u = $_POST['username'];
    $p = $_POST['password'];

    $q = "SELECT * FROM agent WHERE username='$u' AND password='$p'";
    $r = mysqli_query($conn, $q);

    if (mysqli_num_rows($r) == 1) {
        $row = mysqli_fetch_assoc($r);

        $_SESSION['agent_id'] = $row['id'];
        $_SESSION['agent_name'] = $row['name'];

        header("Location: agent_dashboard.php");
    } else {
        $error = "Invalid Login";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>📦Agent Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Segoe UI;
    }

    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(rgba(15, 23, 42, .9), rgba(15, 23, 42, .9)),
            url('https://images.unsplash.com/photo-1553413077-190dd305871c?w=1600');
        background-size: cover;
        background-position: center;
    }

    .login-box {
        width: 380px;
        background: #fff;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .login-box h2 {
        margin-bottom: 20px;
        color: #0f172a;
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        background: #0f172a;
        color: white;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background: #2563eb;
    }

    .error {
        color: red;
        margin-bottom: 10px;
    }
</style>

<body>

    <div class="login-box">

        <h2>🧑‍💼Agent Login</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button name="login">Login</button>
        </form>

        <p style="color:red;">
            <?php if (isset($error))
                echo $error; ?>
        </p>

    </div>

</body>

</html>