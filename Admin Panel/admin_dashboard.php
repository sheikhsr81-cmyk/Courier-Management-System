<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include("../config.php");

$user_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$user_row = mysqli_fetch_assoc($user_query);
$users = $user_row['total'];

$agent_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM agents");
$agent_row = mysqli_fetch_assoc($agent_query);
$agents = $agent_row['total'];

$rider_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM riders");
$rider_row = mysqli_fetch_assoc($rider_query);
$riders = $rider_row['total'];

$shipment_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$shipment_row = mysqli_fetch_assoc($shipment_query);
$total_shipments = $shipment_row['total'];

$pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='Pending'");
$pending_row = mysqli_fetch_assoc($pending_query);
$pending = $pending_row['total'];

$picked_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='Picked Up'");
$picked_row = mysqli_fetch_assoc($picked_query);
$picked_up = $picked_row['total'];

$transit_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='In Transit'");
$transit_row = mysqli_fetch_assoc($transit_query);
$in_transit = $transit_row['total'];

$delivered_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE status='Delivered'");
$delivered_row = mysqli_fetch_assoc($delivered_query);
$delivered = $delivered_row['total'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>👑 Admin Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Segoe UI, sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #0f172a;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }

        .sidebar h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            transition: .3s;
        }

        .sidebar a:hover {
            background: #2563eb;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .main h2 {
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .1);
            text-align: center;
        }

        .stat h2 {
            font-size: 35px;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .stat p {
            font-size: 16px;
            color: #475569;
        }

        .card {
            margin-top: 30px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .1);
        }
    </style>

</head>

<body>

    <div class="sidebar">
        <h2 style="margin-left:25px; margin-top:20px">👑Admin<br><span style="margin-left:25px;"> Panel</span></h2>

        <a href="admin_dashboard.php">Dashboard</a>
        <a href="users.php"> Users</a>
        <a href="agents.php"> Agents</a>
        <a href="riders.php"> Riders</a>
        <a href="track.php"> Tracking</a>
        <a href="logout.php"> Logout</a>
    </div>

    <div class="main">

        <h2>Dashboard Overview</h2>

        <div class="grid">

            <div class="stat">
                <h2><?php echo $users; ?></h2>
                <p>Total Users</p>
            </div>

            <div class="stat">
                <h2><?php echo $agents; ?></h2>
                <p>Total Agents</p>
            </div>

            <div class="stat">
                <h2><?php echo $riders; ?></h2>
                <p>Total Riders</p>
            </div>

            <div class="stat">
                <h2><?php echo $total_shipments; ?></h2>
                <p>Total Shipments</p>
            </div>

            <div class="stat">
                <h2><?php echo $pending; ?></h2>
                <p>Pending</p>
            </div>

            <div class="stat">
                <h2><?php echo $picked_up; ?></h2>
                <p>Picked Up</p>
            </div>

            <div class="stat">
                <h2><?php echo $in_transit; ?></h2>
                <p>In Transit</p>
            </div>

            <div class="stat">
                <h2><?php echo $delivered; ?></h2>
                <p>Delivered</p>
            </div>

        </div>

        <div class="card">
            <h3>System Status</h3>
            <br>
            <p>All systems running smoothly 🚀</p>
        </div>

    </div>

</body>

</html>
