<?php
include("../config.php");
session_start();

if (!isset($_SESSION['agent_name'])) {
    header("Location: agent_login.php");
    exit();
}

$showBill = false;
$billUser = null;
$errors = [];
$editData = null;

$allowedStatuses = ['Pending', 'Picked Up', 'In Transit', 'Out For Delivery', 'Delivered'];

function validateShipmentFields($data, $allowedStatuses) {
    $errors = [];

    if (trim($data['tracking_number'] ?? '') === '') {
        $errors[] = "Tracking number is required.";
    } elseif (!preg_match('/^[A-Za-z0-9\-]{3,30}$/', $data['tracking_number'])) {
        $errors[] = "Tracking number may only contain letters, numbers, and dashes (3-30 chars).";
    }

    if (trim($data['sender'] ?? '') === '') {
        $errors[] = "Sender name is required.";
    } elseif (strlen($data['sender']) > 100) {
        $errors[] = "Sender name must be 100 characters or fewer.";
    }

    if (trim($data['phone'] ?? '') === '') {
        $errors[] = "Phone is required.";
    } elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $data['phone'])) {
        $errors[] = "Phone must be 7-15 digits (may include +, -, spaces).";
    }

    if (trim($data['shipment_details'] ?? '') === '') {
        $errors[] = "Shipment details are required.";
    }

    if (trim($data['address'] ?? '') === '') {
        $errors[] = "Address is required.";
    }

    if (!in_array($data['status'] ?? '', $allowedStatuses)) {
        $errors[] = "Invalid status selected.";
    }

    if (!empty($data['rider_id']) && !ctype_digit((string)$data['rider_id'])) {
        $errors[] = "Invalid rider selected.";
    }

    return $errors;
}

if (isset($_POST['save_bill'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];

    if (!is_numeric($amount) || (float)$amount <= 0) {
        $errors[] = "Delivery amount must be a number greater than 0.";
        // re-show the bill modal with the error
        $billUser = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'")
        );
        $showBill = (bool)$billUser;
    } else {
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
}

if (isset($_POST['save'])) {
    $id = $_POST['shipment_id'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    $inlineErrors = [];

    if (!in_array($status, $allowedStatuses)) {
        $inlineErrors[] = "Invalid status selected.";
    }
    if (!empty($rider_id) && !ctype_digit((string)$rider_id)) {
        $inlineErrors[] = "Invalid rider selected.";
    }

    if (empty($inlineErrors)) {
        mysqli_query($conn, "
            UPDATE users
            SET status='$status',
                rider_id='$rider_id'
            WHERE id='$id'
        ");

        header("Location:view_shipments.php");
        exit();
    } else {
        $errors = $inlineErrors;
    }
}

if (isset($_POST['add_shipment'])) {
    $tracking_number = $_POST['tracking_number'];
    $sender = $_POST['sender'];
    $phone = $_POST['phone'];
    $shipment_details = $_POST['shipment_details'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    $errors = validateShipmentFields($_POST, $allowedStatuses);

    if (empty($errors)) {
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
    // fall through to redisplay Add form with entered values + errors
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

    $errors = validateShipmentFields($_POST, $allowedStatuses);

    if (empty($errors)) {
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
    } else {
        // keep entered data so the edit form redisplays with values intact
        $editData = $_POST;
    }
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

        .form-errors {
            background: #fee2e2;
            border: 1px solid #dc2626;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 12px;
            text-align: left;
        }

        .form-errors ul {
            margin: 0;
            padding-left: 18px;
        }

        .field-hint {
            font-size: 11px;
            color: #64748b;
            margin-top: -10px;
            margin-bottom: 8px;
        }
    </style>

</head>

<body>

    <div class="sidebar">
        <h2> Agent Panel</h2>
        <a href="agent_dashboard.php">Dashboard</a>
        <a href="view_shipments.php">Shipments</a>
        <a href="riders.php">Riders</a>
        <a href="agent_login.php">Logout</a>
    </div>

    <div class="main">


        <div class="card">
            <h2>Shipments Management</h2>
            <br>

            <?php if (!empty($errors)) { ?>
                <div class="form-errors">
                    <ul>
                        <?php foreach ($errors as $e) { ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

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
<br>
            <form method="POST" id="shipmentForm" onsubmit="return validateShipmentForm('shipmentForm')" novalidate>

                <?php if ($editData) { ?>

                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">

                <?php } ?>

                <input type="text" name="tracking_number" placeholder="Tracking Number" maxlength="30"
                    value="<?= htmlspecialchars($editData['tracking_number'] ?? '') ?>"><br><br>

                    <input type="text" name="sender" placeholder="Sender Name" maxlength="100"
                    value="<?= htmlspecialchars($editData['name'] ?? '') ?>"><br><br>

                <input type="text" name="phone" placeholder="Phone Number"
                    value="<?= htmlspecialchars($editData['phone'] ?? '') ?>"><br><br>

                <input type="text" name="shipment_details" placeholder="Shipment Details"
                    value="<?= htmlspecialchars($editData['shipment_details'] ?? '') ?>"><br><br>
                    
                <input type="text" name="address" placeholder="Address"
                    value="<?= htmlspecialchars($editData['address'] ?? '') ?>"><br><br>

                <select name="status">

                    <?php foreach ($allowedStatuses as $s) { ?>
                        <option value="<?= htmlspecialchars($s) ?>"
                            <?= (($editData['status'] ?? '') == $s) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s) ?>
                        </option>
                    <?php } ?>

                </select>

                <br><br>

                <select name="rider_id">

                    <option value="">Select Rider</option>

                    <?php
                    $riders = mysqli_query($conn, "SELECT * FROM riders");

                    while ($r = mysqli_fetch_assoc($riders)) {
                        $sel = (isset($editData['rider_id']) && $editData['rider_id'] == $r['id']) ? 'selected' : '';
                        ?>

                        <option value="<?= $r['id'] ?>" <?= $sel ?>>
                            <?= htmlspecialchars($r['name']) ?>
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

                <?php if (!empty($errors)) { ?>
                    <div class="form-errors">
                        <ul>
                            <?php foreach ($errors as $e) { ?>
                                <li><?php echo htmlspecialchars($e); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <form method="POST" id="billForm" onsubmit="return validateBillForm()">

                    <input type="hidden" name="user_id" value="<?php echo $billUser['id']; ?>">

                    <p>

                        <b>Name:</b>

                        <?php echo $billUser['name']; ?>

                    </p>

                    <input type="number" id="amount" name="amount" placeholder="Delivery Amount" min="0.01" step="0.01"
                        oninput="calcGST()">
                    <small id="amountError" style="color:#dc2626; display:none;">Enter an amount greater than 0.</small>

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

        function validateBillForm() {
            const amountInput = document.getElementById("amount");
            const amountError = document.getElementById("amountError");
            const amt = parseFloat(amountInput.value);

            if (isNaN(amt) || amt <= 0) {
                amountError.style.display = "inline";
                amountInput.focus();
                return false;
            }
            amountError.style.display = "none";
            return true;
        }

        function validateShipmentForm(formId) {
            const form = document.getElementById(formId);
            const get = (name) => form.querySelector(`[name="${name}"]`);
            const errors = [];

            const tracking = get("tracking_number").value.trim();
            if (!/^[A-Za-z0-9\-]{3,30}$/.test(tracking)) {
                errors.push("Tracking number may only contain letters, numbers, and dashes (3-30 chars).");
            }

            const sender = get("sender").value.trim();
            if (!sender) errors.push("Sender name is required.");
            else if (sender.length > 100) errors.push("Sender name must be 100 characters or fewer.");

            const phone = get("phone").value.trim();
            if (!/^[0-9+\-\s]{7,15}$/.test(phone)) {
                errors.push("Phone must be 7-15 digits (may include +, -, spaces).");
            }

            const details = get("shipment_details").value.trim();
            if (!details) errors.push("Shipment details are required.");

            const address = get("address").value.trim();
            if (!address) errors.push("Address is required.");

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }

    </script>

</body>

</html>
