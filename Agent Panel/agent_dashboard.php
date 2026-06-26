<?php
session_start();

if (!isset($_SESSION['agent_name'])) {
    header("Location: agent_login.php");
    exit();
}

include("../config.php");


$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_row = mysqli_fetch_assoc($total_query);
$total_shipments = $total_row['total'];

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
    <title>🧑‍💼Agent Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <div class="sidebar">
        <h2>🧑‍💼Agent Panel</h2>

        <a href="agent_dashboard.php">Dashboard</a>
        <a href="view_shipments.php">Shipments</a>
        <a href="riders.php">Riders</a>
        <a href="agent_login.php">Logout</a>
    </div>

    <div class="main">

        <h1 style="margin-left:20px">
            Welcome, <?php echo $_SESSION['agent_name']; ?>
        </h1>

        <div class="grid">

            <div class="card">
                <h2><?php echo $total_shipments; ?></h2>
                <p>Total Shipments</p>
            </div>

            <div class="card">
                <h2><?php echo $pending; ?></h2>
                <p>Pending</p>
            </div>

            <div class="card">
                <h2><?php echo $picked_up; ?></h2>
                <p>Picked Up</p>
            </div>

            <div class="card">
                <h2><?php echo $in_transit; ?></h2>
                <p>In Transit</p>
            </div>

            <div class="card">
                <h2><?php echo $delivered; ?></h2>
                <p>Delivered</p>
            </div>

        </div>

        <div class="card">
            <h3>📦 Shipments</h3>
            <p>View all shipments</p>
        </div>

        <div class="card">
            <h3>🚚 Riders</h3>
            <p>Assign riders to shipments</p>
        </div>

        <div class="card">
            <h3>📊 Status</h3>
            <p>Update delivery status</p>
        </div>

    </div>

</body>

</html>
