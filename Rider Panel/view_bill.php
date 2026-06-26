<?php
include("../config.php");
session_start();

if (!isset($_SESSION['rider_id'])) {
    header("Location: rider_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No bill found!";
    exit();
}

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT * FROM users
    WHERE id='$id'
");

$bill = mysqli_fetch_assoc($query);

if (!$bill) {
    echo "Bill not found!";
    exit();
}

if ($bill['status'] == 'Delivered') {
    $balance = 0;
} else {
    $balance = $bill['total_amount'];
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>🧾 Courier Bill</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            padding: 40px;
        }

        .bill-box {
            width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0f172a;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .label {
            font-weight: bold;
            color: #0f172a;
        }

        .value {
            color: #475569;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
        }

        .balance {
            color: #dc2626;
            font-weight: bold;
        }

        .btns {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            text-align: center;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            color: white;
            border: none;
            cursor: pointer;
        }

        .print {
            background: #16a34a;
        }

        .back {
            background: #0f172a;
        }
    </style>

</head>

<body>

    <div class="bill-box">

        <h2>🧾 Courier Bill</h2>

        <div class="row">
            <span class="label">Sender Name</span>
            <span class="value"><?php echo $bill['name']; ?></span>
        </div>

        <div class="row">
            <span class="label">Phone</span>
            <span class="value"><?php echo $bill['phone']; ?></span>
        </div>

        <div class="row">
            <span class="label">Tracking No</span>
            <span class="value"><?php echo $bill['tracking_number']; ?></span>
        </div>

        <div class="row">
            <span class="label">Delivery Amount</span>
            <span class="value">Rs <?php echo $bill['delivery_amount']; ?></span>
        </div>

        <div class="row">
            <span class="label">GST (5%)</span>
            <span class="value">Rs <?php echo $bill['gst']; ?></span>
        </div>

        <div class="row">
            <span class="label total">Total Amount</span>
            <span class="value total">Rs <?php echo $bill['total_amount']; ?></span>
        </div>

        <div class="row">
            <span class="label balance">Balance</span>
            <span class="value balance">Rs <?php echo $balance; ?></span>
        </div>

        <div class="row">
            <span class="label">Shipment Status</span>
            <span class="value"><?php echo $bill['status']; ?></span>
        </div>

        <div class="btns">

            <button class="btn print" onclick="window.print()">
                Print Bill
            </button>

            <a href="rider_dashboard.php" class="btn back">
                ⬅ Back
            </a>

        </div>

    </div>

</body>

</html>