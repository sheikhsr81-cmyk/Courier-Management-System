<?php
include("config.php");

$result = null;
$error = "";

if (isset($_GET['track'])) {
    $tracking = mysqli_real_escape_string($conn, $_GET['track']);

    $query = mysqli_query($conn, "
        SELECT *
        FROM users
        WHERE tracking_number='$tracking'
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
    <title>📦 Track Shipment</title>

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

        /* NAVBAR */
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

        /* CONTAINER */
        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        /* HEADER */
        .header {
            background: #0f172a;
            color: white;
            padding: 25px;
            text-align: center;
        }

        /* CONTENT */
        .content {
            padding: 25px;
        }

        /* SEARCH */
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
            background: #2563eb;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
        }

        .search-box button:hover {
            background: #1d4ed8;
        }

        /* ERROR */
        .error {
            color: #dc2626;
            text-align: center;
            margin-top: 15px;
        }

        /* CARD */
        .card {
            margin-top: 25px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 15px;
        }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 30px;
            background: #22c55e;
            color: white;
            margin-top: 10px;
            font-size: 13px;
        }

        /* INFO GRID */
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

        /* PROGRESS BAR */
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

        /* STEPS */
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

        /* FOOTER */

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

        /* MOBILE */
        @media(max-width:600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .search-box {
                flex-direction: column;
            }
        }

        /* ===== RESPONSIVE PATCH (NO LAYOUT CHANGE) ===== */

        /* Prevent horizontal scroll on small screens */
        @media(max-width:768px) {
            body {
                overflow-x: hidden;
            }
        }

        /* Make only input/button usable on small screens (no layout shift) */
        @media(max-width:600px) {
            .search-box input {
                width: 100%;
            }

            .search-box button {
                width: 100%;
            }
        }

        /* Prevent grid breaking visually (NO structure change) */
        @media(max-width:600px) {
            .info-grid {
                width: 100%;
            }
        }

        /* Text scaling only (no layout movement) */
        @media(max-width:480px) {
            .header h2 {
                font-size: 18px;
            }

            .badge {
                font-size: 12px;
            }

            .step {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <a href="index.php">📦 Courier Management System</a>
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="track.php">Track</a>
            <a href="register.php">Register Now</a>

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
                    "delivered"
                ];

                $current = array_search($status, $steps);
                if ($current === false)
                    $current = -1;

                $progress = "10%";

                if ($status == "pending")
                    $progress = "25%";
                elseif ($status == "picked up")
                    $progress = "50%";
                elseif ($status == "in transit")
                    $progress = "75%";
                elseif ($status == "delivered")
                    $progress = "100%";
                ?>

                <div class="card">

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

                        <div class="info-item" style="grid-column:span 2;">
                            <div class="label">Shipment Details</div>
                            <div class="value"><?php echo $result['shipment_details']; ?></div>
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

                </div>

            <?php } ?>

        </div>

    </div>
    <br><br><br><br>
    <footer class="footer">

        <div class="footer-container">

            <div class="footer-box">
                <h3>Courier Management System</h3>
                <p>
                    Delivering trust, speed and reliability
                    with advanced courier management system.
                </p>
            </div>

            <div class="footer-box">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="track.php">Track Parcel</a>
                <a href="register.php">Register Now</a>

            </div>

            <div class="footer-box">
                <h3>Contact Us</h3>
                <p>Karachi, Pakistan</p>
                <p>+92 300 1234567</p>
            </div>

            <div class="footer-box">
                <h3>Follow Us</h3>
                <p><a href="https://www.facebook.com/">Facebook</a></p>
                <p><a href="https://www.instagram.com/?hl=en">Instagram</a></p>
                <p><a href="https://pk.linkedin.com/">LinkedIn</a></p>
            </div>

        </div>

        <div class="copyright">
            © Courier Management System | All Rights Reserved
        </div>

    </footer>
</body>

</html>