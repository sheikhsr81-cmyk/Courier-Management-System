<?php
include("../config.php");

$result = null;
$error = "";

if (isset($_GET['track'])) {
    $tracking = mysqli_real_escape_string($conn, $_GET['track']);

    $query = mysqli_query(
        $conn,
        "SELECT tracking_number,status FROM users
     WHERE tracking_number='$tracking'"
    );

    if (mysqli_num_rows($query) > 0) {
        $result = mysqli_fetch_assoc($query);
    } else {
        $error = "Tracking Number Not Found";
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

        .badge.cancelled {
            background: #dc2626;
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

        .progress-fill.cancelled {
            background: #dc2626;
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

        .step.active.cancelled {
            border-left: 4px solid #dc2626;
            color: #b91c1c;
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

        @media(max-width:600px) {
            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .search-box button {
                width: 100%;
            }
        }

        @media(max-width:768px) {
            body {
                overflow-x: hidden;
            }
        }

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

        /* --- PRINT SETTINGS --- */
        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                background: white;
            }

            .print-btn {
                display: none !important;
            }
        }

        @media print {
            .print-title {
                display: block !important;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <br><br><br>

    <div class="container">
        <div class="header">
            <h2>📍 Track Your Shipment</h2>
        </div>

        <div class="content">
            <form method="GET">
                <div class="search-box">
                    <input type="text" name="track" placeholder="Enter Tracking Number"
                        value="<?php echo isset($_GET['track']) ? htmlspecialchars($_GET['track']) : ''; ?>" required>
                    <button type="submit">Track Now</button>
                </div>
            </form>

            <?php if ($error != "") { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>

            <?php if ($result) {

                $status = $result['status'];

                $steps = [
                    "Pending",
                    "Picked Up",
                    "In Transit",
                    "Out For Delivery",
                    "Delivered",
                    "Cancelled"
                ];

                $current = array_search($status, $steps);
                if ($current === false)
                    $current = -1;

                $isCancelled = (strtolower(trim($status)) == "cancelled");

                $progress = "10%";
                if ($status == "Pending")
                    $progress = "20%";
                elseif ($status == "Picked Up")
                    $progress = "40%";
                elseif ($status == "In Transit")
                    $progress = "60%";
                elseif ($status == "Out For Delivery")
                    $progress = "80%";
                elseif ($status == "Delivered")
                    $progress = "100%";
                elseif ($isCancelled)
                    $progress = "100%";
                ?>

                <div class="card" id="printArea">
                    <h2 style="text-align:center; display:none;" class="print-title">Courier Tracking Details</h2>

                    <h3>Tracking #: <?php echo $result['tracking_number']; ?></h3>

                    <div class="badge<?php echo $isCancelled ? ' cancelled' : ''; ?>">
                        <?php echo strtoupper($status); ?>
                    </div>

                    <div class="progress">
                        <div class="progress-fill<?php echo $isCancelled ? ' cancelled' : ''; ?>"
                            style="width:<?php echo $progress; ?>"></div>
                    </div>

                    <?php
                    foreach ($steps as $i => $step) {
                        $class = "step";
                        if ($i <= $current) {
                            $class .= " active";
                            if ($isCancelled)
                                $class .= " cancelled";
                        }
                        echo "<div class='$class'>" . $step . "</div>";
                    }
                    ?>

                    <button type="button" class="print-btn" onclick="printStatus()">
                        🖨️ Print Status
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function printStatus() {
            window.print();
        }
    </script>
</body>

</html>