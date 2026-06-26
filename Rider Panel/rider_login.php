<?php
session_start();
include("rider_config.php");

$error = "";

if (isset($_POST['login'])) {

    $rider_id = mysqli_real_escape_string($conn, $_POST['rider_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM riders
     WHERE rider_id='$rider_id'
     AND password='$password'"
    );

    if (mysqli_num_rows($query) == 1) {

        $row = mysqli_fetch_assoc($query);

        $_SESSION['rider_id'] = $row['id'];
        $_SESSION['rider_name'] = $row['name'];

        header("Location: rider_dashboard.php");
        exit();

    } else {
        $error = "Invalid Rider ID or Password";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>🛵Rider Login</title>

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
</head>

<body>

    <div class="login-box">


        <h2>🛵Rider Login</h2>

        <form method="POST">

            <input type="text" name="rider_id" placeholder="Enter Rider ID" required>

            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" name="login">
                Login
            </button>

        </form>

        <?php
        if ($error != "") {
            echo "<div class='error'>$error</div>";
        }
        ?>

    </div>

</body>

</html>