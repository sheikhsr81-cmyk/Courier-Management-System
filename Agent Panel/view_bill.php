<?php
include("../config.php");
session_start();

if (!isset($_SESSION['agent_name'])) {
    header("Location: agent_login.php");
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

if (isset($_POST['update_bill'])) {
    $delivery_amount = $_POST['delivery_amount'];

    $gst = $delivery_amount * 0.05;
    $total_amount = $delivery_amount + $gst;

    mysqli_query($conn, "
        UPDATE users
        SET
        delivery_amount='$delivery_amount',
        gst='$gst',
        total_amount='$total_amount'
        WHERE id='$id'
    ");

    header("Location: view_bill.php?id=" . $id);
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
            width: 550px;
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
            align-items: center;
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

        input {
            width: 180px;
            padding: 8px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
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
            font-size: 15px;
        }

        .update {
            background: #2563eb;
        }

        .print {
            background: #16a34a;
        }

        .back {
            background: #0f172a;
        }

        .btn:hover {
            opacity: .9;
        }
    </style>

</head>

<body>

    <div class="bill-box">

        <h2>🧾 Courier Bill</h2>

        <form method="POST">

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

                <input type="number" name="delivery_amount" value="<?php echo $bill['delivery_amount']; ?>" required>
            </div>

            <div class="row">
                <span class="label">GST (5%)</span>
                <span class="value">
                    Rs <?php echo $bill['gst']; ?>
                </span>
            </div>

            <div class="row">
                <span class="label total">Total Amount</span>
                <span class="value total">
                    Rs <?php echo $bill['total_amount']; ?>
                </span>
            </div>

            <div class="row">
                <span class="label balance">Balance</span>
                <span class="value balance">
                    Rs <?php echo $balance; ?>
                </span>
            </div>

            <div class="row">
                <span class="label">Shipment Status</span>
                <span class="value">
                    <?php echo $bill['status']; ?>
                </span>
            </div>

            <div class="btns">

                <button type="submit" name="update_bill" class="btn update">
                    Update Bill
                </button>

                <button type="button" class="btn print" onclick="window.print()">
                    Print Bill
                </button>

                <a href="view_shipments.php" class="btn back">
                    ⬅ Back
                </a>

            </div>

        </form>

    </div>

</body>

</html>