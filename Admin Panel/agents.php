<?php
include("../config.php");

$editData = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM agents WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

if (isset($_POST['update_agent'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];

    mysqli_query($conn, "UPDATE agents SET 
        name='$name',
        phone='$phone',
        region='$region'
        WHERE id=$id
    ");

    header("Location: agents.php");
    exit();
}

if (isset($_POST['add_agent'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];

    mysqli_query($conn, "INSERT INTO agents (name,phone,region)
    VALUES ('$name','$phone','$region')");

    header("Location: agents.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM agents WHERE id=$id");

    header("Location: agents.php");
    exit();
}

$query = mysqli_query($conn, "SELECT * FROM agents ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>📦 Agents</title>
    <link rel="stylesheet" href="../style.css">
</head>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f9;
    }

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

    .main {
        margin-left: 240px;
        padding: 30px;
    }

    .card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #0f172a;
        color: #fff;
        padding: 12px;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    .btn {
        padding: 6px 10px;
        border-radius: 5px;
        text-decoration: none;
        color: #fff;
        font-size: 13px;
        display: inline-block;
    }

    .edit {
        background: #0f172a;
    }

    .delete {
        background: #dc2626;
    }

    .action-box {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    button {
        background: #0f172a;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background: #1d4ed8;
    }
</style>

<body>

    <div class="sidebar">
        <h2>👑 Admin Panel</h2>

        <a href="admin_dashboard.php">Dashboard</a>
        <a href="users.php"> Users</a>
        <a href="agents.php"> Agents</a>
        <a href="riders.php"> Riders</a>
        <a href="track.php"> Tracking</a>
        <a href="logout.php"> Logout</a>
    </div>

    <div class="main">

        <h1>Agent Management</h1>

        <div class="card">

            <table>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Region</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['region']; ?></td>

                        <td>
                            <div class="action-box">

                                <a class="btn edit" href="agents.php?edit=<?php echo $row['id']; ?>">Edit</a>

                                <a class="btn delete" href="agents.php?delete=<?php echo $row['id']; ?>"
                                    onclick="return confirm('Delete this agent?')">
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

            <?php if ($editData) { ?>

                <h3>Edit Agent</h3>

                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">

                    <input type="text" name="name" value="<?php echo $editData['name']; ?>" required>
                    <input type="text" name="phone" value="<?php echo $editData['phone']; ?>" required>
                    <input type="text" name="region" value="<?php echo $editData['region']; ?>" required>

                    <button name="update_agent">Update Agent</button>
                </form>

            <?php } else { ?>

                <h3>Add New Agent</h3>

                <form method="POST">

                    <input type="text" name="name" placeholder="Agent Name" required>
                    <input type="text" name="phone" placeholder="Phone Number" required>
                    <input type="text" name="region" placeholder="Region" required>

                    <button name="add_agent">Add Agent</button>

                </form>

            <?php } ?>

        </div>

    </div>

</body>

</html>