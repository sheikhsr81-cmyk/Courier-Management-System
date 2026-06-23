<?php
include("../config.php");

$editData = null;

if(isset($_GET['edit']))
{
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn,"SELECT * FROM riders WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

if(isset($_POST['update_rider']))
{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $status = $_POST['status'];

    mysqli_query($conn,"UPDATE riders SET 
        name='$name',
        phone='$phone',
        vehicle='$vehicle',
        status='$status'
        WHERE id=$id
    ");

    header("Location: riders.php");
    exit();
}

if(isset($_POST['add_rider']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $status = $_POST['status'];

    mysqli_query($conn,"INSERT INTO riders (name,phone,vehicle,status)
    VALUES ('$name','$phone','$vehicle','$status')");

    header("Location: riders.php");
    exit();
}

if(isset($_GET['delete']))
{
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM riders WHERE id=$id");
    header("Location: riders.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM riders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>🏍 Rider Management</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f4f6f9;
}

/* SIDEBAR */
.sidebar{
    width:220px;
    height:100vh;
    background:#0f172a;
    position:fixed;
    left:0;
    top:0;
    padding:20px;
}

.sidebar h2{
    color:#fff;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    color:#fff;
    text-decoration:none;
    padding:12px;
    border-radius:6px;
    margin-bottom:8px;
}

.sidebar a:hover{
    background:#2563eb;
}

/* MAIN */
.main{
    margin-left:240px;
    padding:30px;
}

/* CARD */
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    margin-bottom:20px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#0f172a;
    color:#fff;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

/* BUTTONS */
.btn{
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    color:#fff;
    font-size:13px;
}

.edit{ background:#0f172a; }
.delete{ background:#dc2626; }

/* ACTION */
.action-box{
    display:flex;
    justify-content:center;
    gap:8px;
}

/* FORM */
input{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    background:#0f172a;
    color:#fff;
    border:none;
    padding:10px 16px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}
</style>

</head>

<body>

<div class="sidebar">
    <h2>👑 Admin Panel</h2>

    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="users.php">👥 Users</a>
    <a href="agents.php">🧑‍💼 Agents</a>
    <a href="riders.php">🏍 Riders</a>
    <a href="track.php">📦 Tracking</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">

<h1>Rider Management</h1>

<div class="card">

<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Vehicle</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['vehicle']; ?></td>
    <td><?php echo $row['status']; ?></td>

    <td>
        <div class="action-box">

            <a class="btn edit" href="riders.php?edit=<?php echo $row['id']; ?>">Edit</a>

            <a class="btn delete" href="riders.php?delete=<?php echo $row['id']; ?>"
               onclick="return confirm('Delete this rider?')">
               Delete
            </a>

        </div>
    </td>

</tr>

<?php } ?>

</table>

</div>

<!-- FORM -->
<div class="card">

<?php if($editData) { ?>

<h3>Edit Rider</h3>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">

    <input type="text" name="name" value="<?php echo $editData['name']; ?>" required>
    <input type="text" name="phone" value="<?php echo $editData['phone']; ?>" required>
    <input type="text" name="vehicle" value="<?php echo $editData['vehicle']; ?>" required>
    <input type="text" name="status" value="<?php echo $editData['status']; ?>" required>

    <button name="update_rider">Update Rider</button>
</form>

<?php } else { ?>

<h3>Add New Rider</h3>

<form method="POST">

    <input type="text" name="name" placeholder="Rider Name" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="text" name="vehicle" placeholder="Vehicle" required>
    <input type="text" name="status" placeholder="Status" required>

    <button name="add_rider">Add Rider</button>

</form>

<?php } ?>

</div>

</div>

</body>
</html>