<?php
include("../config.php");
session_start();

$editData = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
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
}

if (isset($_POST['inline_save'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn, "UPDATE users SET 
        status='$status',
        rider_id='$rider_id'
        WHERE id=$id
    ");

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

    mysqli_query($conn, "INSERT INTO users 
    (name,phone,address,shipment_details,tracking_number,status,rider_id)
    VALUES 
    ('$name','$phone','$address','$shipment_details','$tracking_number','$status','$rider_id')");

    $_SESSION['new_user_id'] = mysqli_insert_id($conn);

    header("Location: users.php?bill=1");
    exit();
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
</style>

<body>

    <div class="sidebar">
        <h2>👑 Admin Panel</h2>

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

                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['shipment_details']; ?></td>
                            <td><?php echo $row['tracking_number']; ?></td>

                            <td>
                                <select name="status">
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

                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">

                    <input name="name" value="<?php echo $editData['name']; ?>"><br><br>
                    <input name="phone" value="<?php echo $editData['phone']; ?>"><br><br>
                    <input name="address" value="<?php echo $editData['address']; ?>"><br><br>
                    <input name="shipment_details" value="<?php echo $editData['shipment_details']; ?>"><br><br>
                    <input name="tracking_number" value="<?php echo $editData['tracking_number']; ?>"><br><br>

                    <button name="update_user">Update</button>
                </form>

            <?php } else { ?>

                <h3>Add User</h3>

                <form method="POST">

                    <input name="name" placeholder="Name"><br><br>
                    <input name="phone" placeholder="Phone"><br><br>
                    <input name="address" placeholder="Address"><br><br>
                    <input name="shipment_details" placeholder="Shipment Details"><br><br>
                    <input name="tracking_number" placeholder="Tracking Number"><br><br>

                    <select name="status">
                        <option>Pending</option>
                        <option>Picked Up</option>
                        <option>In Transit</option>
                        <option>Out For Delivery</option>
                        <option>Delivered</option>
                    </select><br><br>

                    <select name="rider_id">
                        <option value="">None</option>
                        <?php foreach ($riders as $r) { ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
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

                <form method="POST">

                    <input type="hidden" name="user_id" value="<?php echo $billUser['id']; ?>">

                    <p><b>Sender:</b> <?php echo $billUser['name']; ?></p>

                    <input type="number" id="amount" name="amount" placeholder="Delivery Amount" oninput="calcGST()"
                        required><br><br>

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
        </script>

    <?php } ?>

</body>

</html>