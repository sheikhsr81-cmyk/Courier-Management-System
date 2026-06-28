<?php
include("config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?login_required=1");
    exit();
}

$userName = $_SESSION['user_name'] ?? '';

$result = null;
$error = "";

if (isset($_GET['track'])) {
    $tracking = mysqli_real_escape_string($conn, $_GET['track']);

    $query = mysqli_query($conn, "
        SELECT users.*, riders.name AS rider_name
        FROM users
        LEFT JOIN riders ON users.rider_id = riders.id
        WHERE users.tracking_number='$tracking'
        LIMIT 1
    ");

    if (mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    } else {
        $error = "Tracking Number Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>📍Track Shipment</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f6fb;
        }

        .navbar {
            background: #0f172a;
            padding: 16px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            transition: .3s;
        }

        .nav-links a:hover {
            color: #3b82f6;
        }

        .nav-links .btn {
            background: #2563eb;
            color: white !important;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;

            font-size: 15px;
        }

        .nav-links .btn:hover {
            background: #1d4ed8;
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-links .btn.logout-btn {
            background: #dc2626;
        }

        .nav-links .btn.logout-btn:hover {
            background: #b91c1c;
        }

        .nav-links .welcome-text {
            color: #cbd5e1;
            margin-left: 20px;
            font-size: 14px;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .header {
            background: #0f172a;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .content {
            padding: 25px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
        }

        .search-box button {
            padding: 14px 18px;
            border: none;
            background: #0f172a;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
        }

        .search-box button:hover {
            background: #1d4ed8;
        }

        .error {
            color: #dc2626;
            text-align: center;
            margin-top: 15px;
        }

        .card {
            margin-top: 25px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 15px;
        }

        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 30px;
            background: #22c55e;
            color: white;
            margin-top: 10px;
            font-size: 13px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 15px;
        }

        .info-item {
            background: white;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
        }

        .value {
            font-weight: 600;
            margin-top: 5px;
            color: #111827;
        }

        .progress {
            width: 100%;
            height: 10px;
            background: #e5e7eb;
            border-radius: 20px;
            margin: 20px 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #22c55e;
        }

        .step {
            background: white;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 4px solid #d1d5db;
            border-radius: 8px;
            color: #6b7280;
        }

        .step.active {
            border-left: 4px solid #22c55e;
            color: #16a34a;
            font-weight: 600;
        }

        .print-btn {
            background: #16a34a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            margin-top: 15px;
            transition: .3s;
        }

        .print-btn:hover {
            background: #15803d;
        }

        .footer {
            background: #0f172a;
            color: white;
            padding: 60px 8% 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            align-items: start;
        }

        .footer-box h3 {
            margin-bottom: 15px;
        }

        .footer-box p {
            color: #cbd5e1;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .footer-box a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .footer-box a:hover {
            color: white;
            padding-left: 5px;
        }

        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            color: #94a3b8;
        }

        /* ==========================
   TABLET RESPONSIVE
========================== */

        @media (max-width:768px) {

            body {
                overflow-x: hidden;
            }

            .navbar {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 15px 5%;
                gap: 15px;
            }

            .logo {
                text-align: center;
            }

            .logo a {
                font-size: 20px;
            }

            .nav-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                gap: 12px;
                width: 100%;
            }

            .nav-links a {
                margin-left: 0;
            }

            .welcome-text {
                width: 100%;
                text-align: center;
                margin: 0;
            }

            .container {
                width: 95%;
                margin: 30px auto;
            }

            .header {
                padding: 22px;
            }

            .header h2 {
                font-size: 24px;
            }

            .content {
                padding: 20px;
            }

            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .search-box button {
                width: 100%;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item {
                width: 100%;
            }

            .info-item[style] {
                grid-column: auto !important;
            }

            .progress {
                margin: 20px 0;
            }

            .print-btn {
                width: 100%;
            }

            .footer {
                padding: 50px 5% 20px;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
            }

        }


        /* ==========================
   MOBILE RESPONSIVE
========================== */

        @media (max-width:480px) {

            body {
                overflow-x: hidden;
            }

            .navbar {
                padding: 15px;
            }

            .logo a {
                font-size: 18px;
            }

            .nav-links {
                flex-direction: column;
                gap: 10px;
            }

            .nav-links a,
            .nav-links .btn {
                width: 100%;
                max-width: 260px;
                text-align: center;
                margin-left: 0;
            }

            .welcome-text {
                text-align: center;
                margin: 0;
            }

            .container {
                width: 96%;
                border-radius: 12px;
                margin: 20px auto;
            }

            .header {
                padding: 18px;
            }

            .header h2 {
                font-size: 20px;
            }

            .content {
                padding: 15px;
            }

            .search-box {
                gap: 12px;
            }

            .search-box input {
                font-size: 15px;
                padding: 12px;
            }

            .search-box button {
                font-size: 15px;
                padding: 12px;
            }

            .card {
                padding: 15px;
            }

            .card h3 {
                font-size: 18px;
                word-break: break-word;
            }

            .badge {
                width: 100%;
                text-align: center;
                font-size: 13px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .info-item {
                padding: 12px;
            }

            .label {
                font-size: 11px;
            }

            .value {
                font-size: 14px;
                word-break: break-word;
            }

            .progress {
                height: 8px;
            }

            .step {
                font-size: 14px;
                padding: 10px;
            }

            .print-btn {
                width: 100%;
                padding: 14px;
                font-size: 15px;
            }

            .footer {
                padding: 40px 20px 20px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-box a:hover {
                padding-left: 0;
            }

        }

        .shipment-box{
    grid-column:span 2;
    margin-top:9px;
}

@media (max-width:768px){
    .shipment-box{
        grid-column:span 1;
    }
}
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index.php">📦 Courier Management System</a>
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="track.php">Track</a>
            <?php if ($userName) { ?>
                <span class="welcome-text">Hi, <?php echo htmlspecialchars($userName); ?></span>
            <?php } ?>
            <a href="logout.php" class="btn logout-btn">Logout</a>

        </div>
    </nav>
    <br><br><br>

    <div class="container">
        <div class="header">
            <h2>📍 Shipment Tracking</h2>
        </div>

        <div class="content">
            <form method="GET">
                <div class="search-box">
                    <input type="text" name="track" placeholder="Enter Tracking Number"
                        value="<?php echo isset($_GET['track']) ? htmlspecialchars($_GET['track']) : ''; ?>" required>
                    <button type="submit">Track</button>
                </div>
            </form>

            <?php if ($error != "") { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>

            <?php if ($result) {
                $status = strtolower(trim($result['status']));
                $steps = [
                    "pending",
                    "picked up",
                    "in transit",
                    "out for delivery",
                    "delivered"
                ];

                $current = array_search($status, $steps);
                if ($current === false)
                    $current = -1;

                $progress = "10%";
                if ($status == "pending")
                    $progress = "20%";
                elseif ($status == "picked up")
                    $progress = "40%";
                elseif ($status == "in transit")
                    $progress = "60%";
                elseif ($status == "out for delivery")
                    $progress = "80%";
                elseif ($status == "delivered")
                    $progress = "100%";
                ?>

                <div class="card" id="printArea">
                    <h2 style="text-align:center; display:none;" class="print-title">Courier Tracking Details</h2>

                    <h3>Tracking #: <?php echo $result['tracking_number']; ?></h3>

                    <div class="badge">
                        <?php echo strtoupper($result['status']); ?>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">Customer Name</div>
                            <div class="value"><?php echo $result['name']; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="label">Phone</div>
                            <div class="value"><?php echo $result['phone']; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="label">Address</div>
                            <div class="value"><?php echo $result['address']; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="label">Tracking No</div>
                            <div class="value"><?php echo $result['tracking_number']; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="label">Rider Name</div>
                            <div class="value">
                                <?php echo !empty($result['rider_name']) ? $result['rider_name'] : 'Not Assigned'; ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="label">Delivery Amount</div>
                            <div class="value">
                                Rs.
                                <?php echo number_format($result['delivery_amount'], 2); ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-item shipment-box">
                        <div class="label">Shipment Details</div>
                        <div class="value">
                            <?php echo $result['shipment_details']; ?>
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-fill" style="width:<?php echo $progress; ?>"></div>
                    </div>

                    <?php
                    foreach ($steps as $i => $step) {
                        $class = ($i <= $current) ? "step active" : "step";
                        echo "<div class='$class'>" . ucwords($step) . "</div>";
                    }
                    ?>

                    <button type="button" class="print-btn" onclick="printStatus()">
                        🖨️ Print Status
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>


    <style>
        @media print {
            .print-title {
                display: block !important;
                margin-bottom: 20px;
            }
        }
    </style>

    <script>
        function printStatus() {
            window.print();
        }
    </script>
</body>

</html>
