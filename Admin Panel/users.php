<?php
include("../config.php");
session_start();

$editData = null;
$errors = [];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

// ---------- Validation helper ----------
function validateUserFields($data)
{
    $errors = [];

    if (trim($data['name'] ?? '') === '') {
        $errors[] = "Name is required.";
    } elseif (strlen($data['name']) > 100) {
        $errors[] = "Name must be 100 characters or fewer.";
    }

    if (trim($data['phone'] ?? '') === '') {
        $errors[] = "Phone is required.";
    } elseif (!preg_match('/^[0-9+\-\s]{7,15}$/', $data['phone'])) {
        $errors[] = "Phone must be 7-15 digits (may include +, -, spaces).";
    }

    if (trim($data['address'] ?? '') === '') {
        $errors[] = "Address is required.";
    }

    if (trim($data['shipment_details'] ?? '') === '') {
        $errors[] = "Shipment details are required.";
    }

    if (trim($data['tracking_number'] ?? '') === '') {
        $errors[] = "Tracking number is required.";
    } elseif (!preg_match('/^[A-Za-z0-9\-]{3,30}$/', $data['tracking_number'])) {
        $errors[] = "Tracking number may only contain letters, numbers, and dashes (3-30 chars).";
    }

    $allowedStatuses = ['Pending', 'Picked Up', 'In Transit', 'Out For Delivery', 'Delivered'];
    if (!in_array($data['status'] ?? '', $allowedStatuses)) {
        $errors[] = "Invalid status selected.";
    }

    if (!empty($data['rider_id']) && !ctype_digit((string) $data['rider_id'])) {
        $errors[] = "Invalid rider selected.";
    }

    return $errors;
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $shipment_details = $_POST['shipment_details'];
    $tracking_number = $_POST['tracking_number'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    $errors = validateUserFields($_POST);

    if (empty($errors)) {
        mysqli_query($conn, "UPDATE users SET 
            name='$name',
            phone='$phone',
            address='$address',
            shipment_details='$shipment_details',
            tracking_number='$tracking_number',
            status='$status',
            rider_id='$rider_id'
            WHERE id=$id
        ");

        header("Location: users.php");
        exit();
    } else {
        // keep the entered data so the edit form can be redisplayed with values intact
        $editData = $_POST;
    }
}

if (isset($_POST['inline_save'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    $allowedStatuses = ['Pending', 'Picked Up', 'In Transit', 'Out For Delivery', 'Delivered'];
    $inlineErrors = [];

    if (!in_array($status, $allowedStatuses)) {
        $inlineErrors[] = "Invalid status selected.";
    }
    if (!empty($rider_id) && !ctype_digit((string) $rider_id)) {
        $inlineErrors[] = "Invalid rider selected.";
    }

    if (empty($inlineErrors)) {
        mysqli_query($conn, "UPDATE users SET 
            status='$status',
            rider_id='$rider_id'
            WHERE id=$id
        ");
    } else {
        $errors = $inlineErrors;
    }

    header("Location: users.php");
    exit();
}

if (isset($_POST['add_user'])) {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $shipment_details = $_POST['shipment_details'];
    $tracking_number = $_POST['tracking_number'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    $errors = validateUserFields($_POST);

    if (empty($errors)) {
        mysqli_query($conn, "INSERT INTO users 
        (name,phone,address,shipment_details,tracking_number,status,rider_id)
        VALUES 
        ('$name','$phone','$address','$shipment_details','$tracking_number','$status','$rider_id')");

        $_SESSION['new_user_id'] = mysqli_insert_id($conn);

        header("Location: users.php?bill=1");
        exit();
    }
    // on failure, fall through to redisplay the Add User form with entered values + errors
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
    header("Location: users.php");
    exit();
}

if (isset($_POST['save_bill'])) {

    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];

    if (!is_numeric($amount) || (float) $amount <= 0) {
        $errors[] = "Delivery amount must be a number greater than 0.";
    } else {
        $gst = $amount * 0.05;
        $total = $amount + $gst;

        mysqli_query($conn, "UPDATE users SET 
            delivery_amount='$amount',
            gst='$gst',
            total_amount='$total',
            bill_created=1
            WHERE id=$user_id
        ");

        header("Location: users.php");
        exit();
    }
}

$query = mysqli_query($conn, "
    SELECT users.*, riders.name AS rider_name 
    FROM users 
    LEFT JOIN riders ON users.rider_id = riders.id 
    ORDER BY users.id DESC
");

$ridersQuery = mysqli_query($conn, "SELECT id, name FROM riders ORDER BY name ASC");
$riders = [];
while ($r = mysqli_fetch_assoc($ridersQuery)) {
    $riders[] = $r;
}

$showBill = isset($_GET['bill']) && isset($_SESSION['new_user_id']);
$billUser = null;

if (isset($_SESSION['new_user_id'])) {
    $uid = $_SESSION['new_user_id'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE id=$uid");
    $billUser = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>📦 User Management</title>
    <link rel="stylesheet" href="../style.css">
</head>

<style>
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
    }

    .sidebar a:hover {
        background: #2563eb;
    }

    .btn {
        padding: 6px 10px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
        color: #fff;
    }

    .save {
        background: #0f172a;
        border: none;
        cursor: pointer;
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
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 300px;
    }

    .form-errors {
        background: #fee2e2;
        border: 1px solid #dc2626;
        color: #991b1b;
        padding: 10px 14px;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .form-errors ul {
        margin: 0;
        padding-left: 18px;
    }

    .field-hint {
        font-size: 11px;
        color: #64748b;
        margin-top: -8px;
        margin-bottom: 8px;
    }
</style>

<body>

    <div class="sidebar">
        <h2 style="margin-right:40px"> Admin Panel</h2>

        <a href="admin_dashboard.php"> Dashboard</a>
        <a href="users.php"> Users</a>
        <a href="agents.php"> Agents</a>
        <a href="riders.php"> Riders</a>
        <a href="track.php"> Tracking</a>
        <a href="logout.php"> Logout</a>
    </div>

    <div class="main">


        <div class="card">
            <h1>User Management</h1>
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
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Shipment</th>
                    <th>Tracking</th>
                    <th>Status</th>
                    <th>Rider</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                    <form method="POST" onsubmit="return true;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['shipment_details']; ?></td>
                            <td><?php echo $row['tracking_number']; ?></td>

                            <td>
                                <select name="status" required>
                                    <?php
                                    $statuses = ['Pending', 'Picked Up', 'In Transit', 'Out For Delivery', 'Delivered'];
                                    foreach ($statuses as $s) {
                                        $sel = ($row['status'] == $s) ? "selected" : "";
                                        echo "<option $sel>$s</option>";
                                    }
                                    ?>
                                </select>
                            </td>

                            <td>
                                <select name="rider_id">
                                    <option value="">None</option>
                                    <?php foreach ($riders as $r) {
                                        $sel = ($row['rider_id'] == $r['id']) ? "selected" : "";
                                        echo "<option value='{$r['id']}' $sel>{$r['name']}</option>";
                                    } ?>
                                </select>
                            </td>

                            <td>
                                <div class="action-box">

                                    <button name="inline_save" class="btn save">Save</button>

                                    <a class="btn edit" href="users.php?edit=<?php echo $row['id']; ?>">Edit</a>

                                    <a class="btn delete" href="users.php?delete=<?php echo $row['id']; ?>"
                                        onclick="return confirm('Delete?')">Delete</a>

                                    <?php if ($row['bill_created']) { ?>
                                        <a class="btn view" href="view_bill.php?id=<?php echo $row['id']; ?>">View Bill</a>
                                    <?php } ?>

                                </div>
                            </td>
                        </tr>

                    </form>

                <?php } ?>

            </table>

        </div>

        <div class="card">

            <?php if ($editData) { ?>

                <h3>Edit User</h3>

                <form method="POST" id="editForm" onsubmit="return validateUserForm('editForm')" novalidate>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editData['id']); ?>">

                    <input name="name" placeholder="Name" maxlength="100"
                        value="<?php echo htmlspecialchars($editData['name'] ?? ''); ?>"><br>
                    <div class="field-hint">Required, max 100 characters.</div>

                    <input name="phone" placeholder="Phone"
                        value="<?php echo htmlspecialchars($editData['phone'] ?? ''); ?>"><br>
                    <div class="field-hint">Required, 7-15 digits (may include + - and spaces).</div>

                    <input name="address" placeholder="Address"
                        value="<?php echo htmlspecialchars($editData['address'] ?? ''); ?>"><br>
                    <div class="field-hint">Required.</div>

                    <input name="shipment_details" placeholder="Shipment Details"
                        value="<?php echo htmlspecialchars($editData['shipment_details'] ?? ''); ?>"><br>
                    <div class="field-hint">Required.</div>

                    <input name="tracking_number" placeholder="Tracking Number"
                        value="<?php echo htmlspecialchars($editData['tracking_number'] ?? ''); ?>"><br>
                    <div class="field-hint">Required, letters/numbers/dashes, 3-30 characters.</div>

                    <button name="update_user">Update</button>
                </form>

            <?php } else { ?>

                <h3>Add User</h3>
                <br>
                <form method="POST" id="addForm" onsubmit="return validateUserForm('addForm')" novalidate>

                    <input name="name" placeholder="Name" maxlength="100"
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"><br>
                    <br>
                    <input name="phone" placeholder="Phone"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"><br>
                    <br>

                    <input name="address" placeholder="Address"
                        value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>"><br>
                    <br>

                    <input name="shipment_details" placeholder="Shipment Details"
                        value="<?php echo htmlspecialchars($_POST['shipment_details'] ?? ''); ?>"><br>
                    <br>

                    <input name="tracking_number" placeholder="Tracking Number"
                        value="<?php echo htmlspecialchars($_POST['tracking_number'] ?? ''); ?>"><br>
                    <br>

                    <select name="status">
                        <option <?php echo (($_POST['status'] ?? '') == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option <?php echo (($_POST['status'] ?? '') == 'Picked Up') ? 'selected' : ''; ?>>Picked Up</option>
                        <option <?php echo (($_POST['status'] ?? '') == 'In Transit') ? 'selected' : ''; ?>>In Transit
                        </option>
                        <option <?php echo (($_POST['status'] ?? '') == 'Out For Delivery') ? 'selected' : ''; ?>>Out For
                            Delivery</option>
                        <option <?php echo (($_POST['status'] ?? '') == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                    </select><br><br>

                    <select name="rider_id">
                        <option value="">None</option>
                        <?php foreach ($riders as $r) {
                            $sel = (isset($_POST['rider_id']) && $_POST['rider_id'] == $r['id']) ? 'selected' : '';
                            ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo $sel; ?>>
                                <?php echo htmlspecialchars($r['name']); ?></option>
                        <?php } ?>
                    </select><br><br>

                    <button name="add_user">Add User</button>

                </form>

            <?php } ?>

        </div>

    </div>

    <?php if ($showBill && $billUser) { ?>

        <div class="modal" id="billModal">
            <div class="modal-content">

                <h2>🧾 Create Bill</h2>

                <form method="POST" id="billForm" onsubmit="return validateBillForm()">

                    <input type="hidden" name="user_id" value="<?php echo $billUser['id']; ?>">

                    <p><b>Sender:</b> <?php echo htmlspecialchars($billUser['name']); ?></p>

                    <input type="number" id="amount" name="amount" placeholder="Delivery Amount" min="0.01" step="0.01"
                        oninput="calcGST()" required><br>
                    <small id="amountError" style="color:#dc2626; display:none;">Enter an amount greater than 0.</small>
                    <br><br>

                    <input type="text" id="gst" readonly placeholder="GST 5%"><br><br>

                    <input type="text" id="total" readonly placeholder="Total"><br><br>

                    <button type="submit" name="save_bill">Save Bill</button>
                    <button type="button" onclick="closeModal()">Close</button>

                </form>

            </div>
        </div>


        <script>
            function calcGST() {
                let amt = document.getElementById("amount").value || 0;
                let gst = amt * 0.05;
                let total = parseFloat(amt) + gst;

                document.getElementById("gst").value = gst.toFixed(2);
                document.getElementById("total").value = total.toFixed(2);
            }

            function closeModal() {
                document.getElementById("billModal").style.display = "none";
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
        </script>

    <?php } ?>

    <script>
        function validateUserForm(formId) {
            const form = document.getElementById(formId);
            const get = (name) => form.querySelector(`[name="${name}"]`);
            const errors = [];

            const name = get("name").value.trim();
            if (!name) errors.push("Name is required.");
            else if (name.length > 100) errors.push("Name must be 100 characters or fewer.");

            const phone = get("phone").value.trim();
            if (!/^[0-9+\-\s]{7,15}$/.test(phone)) {
                errors.push("Phone must be 7-15 digits (may include +, -, spaces).");
            }

            const address = get("address").value.trim();
            if (!address) errors.push("Address is required.");

            const shipment = get("shipment_details").value.trim();
            if (!shipment) errors.push("Shipment details are required.");

            const tracking = get("tracking_number").value.trim();
            if (!/^[A-Za-z0-9\-]{3,30}$/.test(tracking)) {
                errors.push("Tracking number may only contain letters, numbers, and dashes (3-30 chars).");
            }

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
