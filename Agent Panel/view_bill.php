<?php
include("../config.php");

if(!isset($_GET['id']))
{
    echo "No bill found!";
    exit();
}

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT * FROM users WHERE id=$id
");

$bill = mysqli_fetch_assoc($query);

if(!$bill)
{
    echo "Bill not found!";
    exit();
}

/* UPDATE BILL */
if(isset($_POST['update_bill']))
{
    $amount = $_POST['delivery_amount'];

    $gst = $amount * 0.05;
    $total = $amount + $gst;

    mysqli_query($conn,"
        UPDATE users SET
        delivery_amount='$amount',
        gst='$gst',
        total_amount='$total'
        WHERE id='$id'
    ");

    header("Location: view_bill.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>🧾 View Bill</title>

    <style>
        body{
            font-family:Segoe UI;
            background:#f1f5f9;
            padding:30px;
        }

        .bill-box{
            width:450px;
            background:#fff;
            margin:auto;
            padding:25px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.2);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        .row{
            display:flex;
            justify-content:space-between;
            margin-bottom:10px;
            padding:8px 0;
            border-bottom:1px solid #eee;
        }

        .label{
            font-weight:bold;
            color:#333;
        }

        .value{
            color:#555;
        }

        .total{
            font-size:18px;
            font-weight:bold;
            color:#0f172a;
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
            border:1px solid #ddd;
            border-radius:6px;
        }

        .btn{
            display:flex;
            gap:10px;
            margin-top:20px;
        }

        button{
            flex:1;
            padding:10px;
            border:none;
            background:#0f172a;
            color:#fff;
            border-radius:5px;
            cursor:pointer;
        }

        .print{
            background:#16a34a;
        }
    </style>
</head>

<body>

<div class="bill-box">

    <h2>🧾 Courier Bill</h2>

    <div class="row">
        <div class="label">Sender Name:</div>
        <div class="value"><?php echo $bill['name']; ?></div>
    </div>

    <div class="row">
        <div class="label">Phone:</div>
        <div class="value"><?php echo $bill['phone']; ?></div>
    </div>

    <div class="row">
        <div class="label">Tracking No:</div>
        <div class="value"><?php echo $bill['tracking_number']; ?></div>
    </div>

    <!-- EDIT BILL FORM -->
    <form method="POST">

        <div class="row">
            <div class="label">Delivery Amount:</div>
        </div>

        <input type="number" name="delivery_amount"
               value="<?php echo $bill['delivery_amount']; ?>"
               required>

        <div class="row">
            <div class="label">GST (5%):</div>
            <div class="value">Rs <?php echo $bill['gst']; ?></div>
        </div>

        <div class="row">
            <div class="label total">Total Amount:</div>
            <div class="value total">Rs <?php echo $bill['total_amount']; ?></div>
        </div>

        <div class="btn">
            <button type="submit" name="update_bill">💾 Update Bill</button>
            <button type="button" class="print" onclick="window.print()">🖨 Print</button>
        </div>

    </form>

</div>

</body>
</html>