<?php
include("../config.php");
session_start();

if (!isset($_SESSION['agent_name'])) {
    header("Location: agent_login.php");
    exit();
}

$showBill = false;
$billUser = null;

if (isset($_POST['save_bill'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];

    $gst = $amount * 0.05;
    $total = $amount + $gst;

    mysqli_query($conn, "
        UPDATE users
        SET delivery_amount='$amount',
            gst='$gst',
            total_amount='$total'
        WHERE id='$user_id'
    ");

    header("Location:view_shipments.php");
    exit();
}

if (isset($_POST['save'])) {
    $id = $_POST['shipment_id'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn, "
        UPDATE users
        SET status='$status',
            rider_id='$rider_id'
        WHERE id='$id'
    ");

    header("Location:view_shipments.php");
    exit();
}

if (isset($_POST['add_shipment'])) {
    $tracking_number = $_POST['tracking_number'];
    $sender = $_POST['sender'];
    $phone = $_POST['phone'];
    $shipment_details = $_POST['shipment_details'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn, "
        INSERT INTO users
        (
            name,
            phone,
            shipment_details,
            address,
            tracking_number,
            status,
            rider_id
        )
        VALUES
        (
            '$sender',
            '$phone',
            '$shipment_details',
            '$address',
            '$tracking_number',
            '$status',
            '$rider_id'
        )
    ");

    $last_id = mysqli_insert_id($conn);

    header("Location:view_shipments.php?bill=" . $last_id);
    exit();
}

if (isset($_GET['bill'])) {
    $bill_id = $_GET['bill'];

    $billUser = mysqli_fetch_assoc(
        mysqli_query($conn, "
            SELECT *
            FROM users
            WHERE id='$bill_id'
        ")
    );

    if ($billUser) {
        $showBill = true;
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    mysqli_query($conn, "
        DELETE FROM users
        WHERE id='$id'
    ");

    header("Location:view_shipments.php");
    exit();
}

$editData = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $res = mysqli_query($conn, "
        SELECT *
        FROM users
        WHERE id='$id'
    ");

    $editData = mysqli_fetch_assoc($res);
}

if (isset($_POST['update_shipment'])) {
    $id = $_POST['id'];

    mysqli_query($conn, "
        UPDATE users SET
        tracking_number='" . $_POST['tracking_number'] . "',
        name='" . $_POST['sender'] . "',
        phone='" . $_POST['phone'] . "',
        shipment_details='" . $_POST['shipment_details'] . "',
        address='" . $_POST['address'] . "',
        status='" . $_POST['status'] . "',
        rider_id='" . $_POST['rider_id'] . "'
        WHERE id='$id'
    ");

    header("Location:view_shipments.php");
    exit();
}

$result = mysqli_query($conn, "
    SELECT *
    FROM users
    ORDER BY id DESC
");
?>


<!DOCTYPE html>
<html>

<head>

    <title>📦 Shipments</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .edit,
        .delete,
        .save-btn,
        .view {
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }

        .save-btn {
            background: #0f172a;
        }

        .edit {
            background: #0f172a;
        }

        .delete {
            background: #dc2626;
        }

        .view {
            background: #2563eb;
        }

        .action-box {
            display: flex;
            gap: 6px;
            align-items: center;
            white-space: nowrap;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background: white;
            width: 400px;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
        }

        .modal-content input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .modal-content button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            margin: 5px;
            background: #0f172a;
        }
    </style>

</head>

<body>

    <div class="sidebar">
        <h2>🧑‍💼 Agent Panel</h2>
        <a href="agent_dashboard.php">Dashboard</a>
        <a href="view_shipments.php">Shipments</a>
        <a href="riders.php">Riders</a>
        <a href="agent_login.php">Logout</a>
    </div>

    <div class="main">


        <div class="card">
            <h2>Shipments Management</h2>
            <br>
            <table width="100%" cellpadding="10">

                <tr>
                    <th>ID</th>
                    <th>Tracking</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Details</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Rider</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>
                        <form method="POST">

                            <td><?= $row['id'] ?></td>
                            <td><?= $row['tracking_number'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['shipment_details'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td>

                                <select name="status">
                                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>

                                    <option value="Picked Up" <?= $row['status'] == 'Picked Up' ? 'selected' : '' ?>>
                                        Picked Up
                                    </option>

                                    <option value="In Transit" <?= $row['status'] == 'In Transit' ? 'selected' : '' ?>>
                                        In Transit
                                    </option>

                                    <option value="Out For Delivery" <?= $row['status'] == 'Out For Delivery' ? 'selected' : '' ?>>
                                        Out For Delivery
                                    </option>

                                    <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : '' ?>>
                                        Delivered
                                    </option>
                                </select>

                            </td>

                            <td>

                                <select name="rider_id">

                                    <option value="">Select Rider</option>

                                    <?php
                                    $riders = mysqli_query($conn, "SELECT * FROM riders");

                                    while ($r = mysqli_fetch_assoc($riders)) {
                                        ?>

                                        <option value="<?= $r['id'] ?>" <?= ($row['rider_id'] == $r['id']) ? 'selected' : '' ?>>

                                            <?= $r['name'] ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </td>

                            <td>

                                <div class="action-box">

                                    <input type="hidden" name="shipment_id" value="<?= $row['id'] ?>">

                                    <button class="save-btn" name="save">

                                        Save

                                    </button>

                                    <a class="edit" href="?edit=<?= $row['id'] ?>">

                                        Edit

                                    </a>

                                    <a class="delete" href="?delete=<?= $row['id'] ?>"
                                        onclick="return confirm('Delete this shipment?')">

                                        Delete

                                    </a>

                                    <a class="view" href="view_bill.php?id=<?= $row['id'] ?>">

                                        View Bill

                                    </a>

                                </div>

                            </td>

                        </form>
                    </tr>

                <?php } ?>

            </table>

        </div>


        <div class="card">

            <h3>

                <?= $editData ? "Edit Shipment" : "Add Shipment" ?>

            </h3>

            <form method="POST">

                <?php if ($editData) { ?>

                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                <?php } ?>

                <input type="text" name="tracking_number" placeholder="Tracking Number"
                    value="<?= $editData['tracking_number'] ?? '' ?>" required>

                <br><br>

                <input type="text" name="sender" placeholder="Sender Name" value="<?= $editData['name'] ?? '' ?>"
                    required>

                <br><br>

                <input type="text" name="phone" placeholder="Phone Number" value="<?= $editData['phone'] ?? '' ?>"
                    required>

                <br><br>

                <input type="text" name="shipment_details" placeholder="Shipment Details"
                    value="<?= $editData['shipment_details'] ?? '' ?>" required>

                <br><br>

                <input type="text" name="address" placeholder="Address" value="<?= $editData['address'] ?? '' ?>"
                    required>

                <br><br>

                <select name="status">

                    <option value="Pending">Pending</option>
                    <option value="Picked Up">Picked Up</option>
                    <option value="In Transit">In Transit</option>
                    <option value="Out For Delivery">Out For Delivery</option>
                    <option value="Delivered">Delivered</option>

                </select>

                <br><br>

                <select name="rider_id">

                    <option value="">Select Rider</option>

                    <?php
                    $riders = mysqli_query($conn, "SELECT * FROM riders");

                    while ($r = mysqli_fetch_assoc($riders)) {
                        ?>

                        <option value="<?= $r['id'] ?>">
                            <?= $r['name'] ?>
                        </option>

                    <?php } ?>

                </select>

                <br><br>

                <?php if ($editData) { ?>

                    <button class="save-btn" name="update_shipment">

                        Update Shipment

                    </button>

                <?php } else { ?>

                    <button class="save-btn" name="add_shipment">

                        Add Shipment

                    </button>

                <?php } ?>

            </form>

        </div>

    </div>


    <?php if ($showBill && $billUser) { ?>

        <div class="modal" id="billModal">

            <div class="modal-content">

                <h2>🧾 Create Bill</h2>

                <form method="POST">

                    <input type="hidden" name="user_id" value="<?php echo $billUser['id']; ?>">

                    <p>

                        <b>Name:</b>

                        <?php echo $billUser['name']; ?>

                    </p>

                    <input type="number" id="amount" name="amount" placeholder="Delivery Amount" oninput="calcGST()"
                        required>

                    <br><br>

                    <input type="text" id="gst" readonly placeholder="GST 5%">

                    <br><br>

                    <input type="text" id="total" readonly placeholder="Total Amount">

                    <br><br>

                    <button type="submit" name="save_bill">

                        Save Bill

                    </button>

                    <button type="button" onclick="closeModal()">

                        Close

                    </button>

                </form>

            </div>

        </div>

    <?php } ?>

    <script>

        function calcGST() {
            let amount =
                parseFloat(
                    document.getElementById('amount').value
                ) || 0;

            let gst = amount * 0.05;

            let total = amount + gst;

            document.getElementById('gst').value =
                "Rs " + gst.toFixed(2);

            document.getElementById('total').value =
                "Rs " + total.toFixed(2);
        }

        function closeModal() {
            document.getElementById('billModal').style.display = 'none';
        }

    </script>

</body>

</html>
