<?php
include("../config.php");
session_start();

if(!isset($_SESSION['agent_name']))
{
    header("Location: agent_login.php");
    exit();
}

/* SAVE */
if(isset($_POST['save']))
{
    $id = $_POST['shipment_id'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn,"
        UPDATE users
        SET status='$status',
            rider_id='$rider_id'
        WHERE id='$id'
    ");

    header("Location: view_shipments.php");
    exit();
}

/* ADD */
if(isset($_POST['add_shipment']))
{
    $tracking_number = $_POST['tracking_number'];
    $sender = $_POST['sender'];
    $phone = $_POST['phone'];
    $shipment_details = $_POST['shipment_details'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn,"
        INSERT INTO users
        (name, phone, shipment_details, address, tracking_number, status, rider_id)
        VALUES
        ('$sender','$phone','$shipment_details','$address','$tracking_number','$status','$rider_id')
    ");

    header("Location: view_shipments.php");
    exit();
}

/* DELETE */
if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
    header("Location: view_shipments.php");
    exit();
}

/* EDIT */
$editData = null;
if(isset($_GET['edit']))
{
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
    $editData = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update_shipment']))
{
    $id = $_POST['id'];
    $tracking_number = $_POST['tracking_number'];
    $sender = $_POST['sender'];
    $phone = $_POST['phone'];
    $shipment_details = $_POST['shipment_details'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $rider_id = $_POST['rider_id'];

    mysqli_query($conn,"
        UPDATE users SET
        tracking_number='$tracking_number',
        name='$sender',
        phone='$phone',
        shipment_details='$shipment_details',
        address='$address',
        status='$status',
        rider_id='$rider_id'
        WHERE id='$id'
    ");

    header("Location: view_shipments.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>📦 Shipments</title>

<link rel="stylesheet" href="../style.css">

<style>
.edit,.delete,.save-btn,.view{
    text-decoration:none;
    padding:7px 12px;
    border-radius:5px;
    color:white;
    border:none;
    cursor:pointer;
    font-size:13px;
}

.save-btn{ background: #0f172a }
.edit{ background:#0f172a; }
.delete{ background: #dc2626; }
.view{ background: #2563eb; }

.action-box{
    display:flex;
    gap:6px;
    flex-wrap:nowrap;   
    align-items:center;
    white-space:nowrap;
}
</style>

</head>

<body>

<div class="sidebar">
    <h2>🧑‍💼Agent Panel</h2>
    <a href="agent_dashboard.php">Dashboard</a>
    <a href="view_shipments.php">Shipments</a>
    <a href="agent_logout.php">Logout</a>
</div>

<div class="main">

<h2>Shipments Management</h2>

<div class="card">

<table width="100%"  cellpadding="10">

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

<?php while($row = mysqli_fetch_assoc($result)) { ?>

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
    <option value="Pending" <?= $row['status']=='Pending'?'selected':'' ?>>Pending</option>
    <option value="Picked Up" <?= $row['status']=='Picked Up'?'selected':'' ?>>Picked Up</option>
    <option value="In Transit" <?= $row['status']=='In Transit'?'selected':'' ?>>In Transit</option>
    <option value="Delivered" <?= $row['status']=='Delivered'?'selected':'' ?>>Delivered</option>
</select>
</td>

<td>
<select name="rider_id">
    <option value="">Select Rider</option>
    <?php
    $riders = mysqli_query($conn,"SELECT * FROM riders");
    while($r = mysqli_fetch_assoc($riders)) {
    ?>
        <option value="<?= $r['id'] ?>"
        <?= ($row['rider_id']==$r['id'])?'selected':'' ?>>
            <?= $r['name'] ?>
        </option>
    <?php } ?>
</select>
</td>

<td>
<div class="action-box">

<input type="hidden" name="shipment_id" value="<?= $row['id'] ?>">

<button class="save-btn" name="save">Save</button>

<a class="edit" href="?edit=<?= $row['id'] ?>">Edit</a>

<a class="delete" href="?delete=<?= $row['id'] ?>"
onclick="return confirm('Delete this shipment?')">
Delete
</a>

<!-- ✅ FIXED BILL LINK -->
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

<!-- FORM -->
<div class="card">

<h3><?= $editData ? "Edit Shipment" : "Add Shipment" ?></h3>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id" value="<?= $editData['id'] ?>">
<?php } ?>

<input type="text" name="tracking_number"
value="<?= $editData['tracking_number'] ?? '' ?>"
placeholder="Tracking Number" required><br><br>

<input type="text" name="sender"
value="<?= $editData['name'] ?? '' ?>"
placeholder="Sender Name" required><br><br>

<input type="text" name="phone"
value="<?= $editData['phone'] ?? '' ?>"
placeholder="Phone Number" required><br><br>

<input type="text" name="shipment_details"
value="<?= $editData['shipment_details'] ?? '' ?>"
placeholder="Shipment Details" required><br><br>

<input type="text" name="address"
value="<?= $editData['address'] ?? '' ?>"
placeholder="Address" required><br><br>

<select name="status">
    <option>Pending</option>
    <option>Picked Up</option>
    <option>In Transit</option>
    <option>Delivered</option>
</select><br><br>

<select name="rider_id">
    <option value="">Select Rider</option>
    <?php
    $riders = mysqli_query($conn,"SELECT * FROM riders");
    while($r = mysqli_fetch_assoc($riders)) {
    ?>
        <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
    <?php } ?>
</select><br><br>

<?php if($editData) { ?>
<button class="save-btn" name="update_shipment">Update</button>
<?php } else { ?>
<button class="save-btn" name="add_shipment">Add Shipment</button>
<?php } ?>

</form>

</div>

</div>

</body>
</html>