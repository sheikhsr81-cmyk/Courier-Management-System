<?php
include("../config.php");

$editData = null;
$errors = [];

$allowedStatuses = ['Available', 'On Delivery', 'Offline'];
$allowedVehicles = ['Bike'];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM riders WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

function validateRiderFields($data, $allowedStatuses, $allowedVehicles) {
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

    if (!in_array($data['vehicle'] ?? '', $allowedVehicles)) {
        $errors[] = "Invalid vehicle selected.";
    }

    if (!in_array($data['status'] ?? '', $allowedStatuses)) {
        $errors[] = "Invalid status selected.";
    }

    return $errors;
}

if (isset($_POST['update_rider'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $status = $_POST['status'];

    $errors = validateRiderFields($_POST, $allowedStatuses, $allowedVehicles);

    if (empty($errors)) {
        mysqli_query($conn, "UPDATE riders SET 
            name='$name',
            phone='$phone',
            vehicle='$vehicle',
            status='$status'
            WHERE id=$id
        ");

        header("Location: riders.php");
        exit();
    } else {
        $editData = $_POST;
    }
}

if (isset($_POST['inline_save'])) {
    $id = $_POST['id'];
    $vehicle = $_POST['vehicle'];
    $status = $_POST['status'];

    $inlineErrors = [];

    if (!in_array($vehicle, $allowedVehicles)) {
        $inlineErrors[] = "Invalid vehicle selected.";
    }
    if (!in_array($status, $allowedStatuses)) {
        $inlineErrors[] = "Invalid status selected.";
    }

    if (empty($inlineErrors)) {
        mysqli_query($conn, "UPDATE riders SET 
            vehicle='$vehicle',
            status='$status'
            WHERE id=$id
        ");
    } else {
        $errors = $inlineErrors;
    }

    header("Location: riders.php");
    exit();
}

if (isset($_POST['add_rider'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $status = $_POST['status'];

    $errors = validateRiderFields($_POST, $allowedStatuses, $allowedVehicles);

    if (empty($errors)) {
        mysqli_query($conn, "INSERT INTO riders (name,phone,vehicle,status)
        VALUES ('$name','$phone','$vehicle','$status')");

        header("Location: riders.php");
        exit();
    }
    // fall through to redisplay Add form with entered values + errors
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM riders WHERE id=$id");
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
        body {
            margin: 0;
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
            margin-left: 260px;
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
            border: none;
            cursor: pointer;
        }

        .edit {
            background: #0f172a;
        }

        .delete {
            background: #dc2626;
        }

        .save {
            background: #0f172a;
        }

        .action-box {
            display: flex;
            justify-content: center;
            gap: 8px;
            align-items: center;
            white-space: nowrap;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        table select {
            width: auto;
            margin: 0;
            padding: 6px;
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
            margin-top: -6px;
            margin-bottom: 6px;
        }
    </style>

</head>

<body>

    <div class="sidebar">
                <h2 style="margin-right:25px"> Admin Panel</h2>


        <a href="admin_dashboard.php">Dashboard</a>
        <a href="users.php"> Users</a>
        <a href="agents.php"> Agents</a>
        <a href="riders.php"> Riders</a>
        <a href="track.php"> Tracking</a>
        <a href="logout.php"> Logout</a>
    </div>

    <div class="main">
        <div class="card">
 <h1>Rider Management</h1>

            <?php if (!empty($errors)) { ?>
                <div class="form-errors">
                    <ul>
                        <?php foreach ($errors as $e) { ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <table>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['phone']; ?></td>

                            <td>
                                <select name="vehicle">
                                    <?php foreach ($allowedVehicles as $v) {
                                        $sel = ($row['vehicle'] == $v) ? "selected" : "";
                                        echo "<option $sel>$v</option>";
                                    } ?>
                                </select>
                            </td>

                            <td>
                                <select name="status">
                                    <?php foreach ($allowedStatuses as $s) {
                                        $sel = ($row['status'] == $s) ? "selected" : "";
                                        echo "<option $sel>$s</option>";
                                    } ?>
                                </select>
                            </td>

                            <td>
                                <div class="action-box">

                                    <button name="inline_save" class="btn save">Save</button>

                                    <a class="btn edit" href="riders.php?edit=<?php echo $row['id']; ?>">Edit</a>

                                    <a class="btn delete" href="riders.php?delete=<?php echo $row['id']; ?>"
                                        onclick="return confirm('Delete this rider?')">
                                        Delete
                                    </a>

                                </div>
                            </td>

                        </tr>
                    </form>

                <?php } ?>

            </table>

        </div>

        <div class="card">

            <?php if ($editData) { ?>

                <h3>Edit Rider</h3>

                <form method="POST" id="editRiderForm" onsubmit="return validateRiderForm('editRiderForm')" novalidate>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editData['id']); ?>">

                    <input type="text" name="name" placeholder="Rider Name" maxlength="100"
                        value="<?php echo htmlspecialchars($editData['name'] ?? ''); ?>">
                    <div class="field-hint">Required, max 100 characters.</div>

                    <input type="text" name="phone" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($editData['phone'] ?? ''); ?>">
                    <div class="field-hint">Required, 7-15 digits (may include + - and spaces).</div>

                    <select name="vehicle">
                        <?php foreach ($allowedVehicles as $v) { ?>
                            <option value="<?php echo htmlspecialchars($v); ?>"
                                <?php echo (($editData['vehicle'] ?? '') == $v) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($v); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select name="status">
                        <?php foreach ($allowedStatuses as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s); ?>"
                                <?php echo (($editData['status'] ?? '') == $s) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button name="update_rider">Update Rider</button>
                </form>

            <?php } else { ?>

                <h3>Add New Rider</h3>

                <form method="POST" id="addRiderForm" onsubmit="return validateRiderForm('addRiderForm')" novalidate>

                    <input type="text" name="name" placeholder="Rider Name" maxlength="100"
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

                    <input type="text" name="phone" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">

                    <select name="vehicle">
                        <?php foreach ($allowedVehicles as $v) { ?>
                            <option value="<?php echo htmlspecialchars($v); ?>"
                                <?php echo (($_POST['vehicle'] ?? '') == $v) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($v); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select name="status">
                        <?php foreach ($allowedStatuses as $s) { ?>
                            <option value="<?php echo htmlspecialchars($s); ?>"
                                <?php echo (($_POST['status'] ?? '') == $s) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($s); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button name="add_rider">Add Rider</button>

                </form>

            <?php } ?>

        </div>

    </div>

    <script>
        function validateRiderForm(formId) {
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

            const vehicle = get("vehicle").value.trim();
            if (!vehicle) errors.push("Vehicle is required.");

            const status = get("status").value.trim();
            if (!status) errors.push("Status is required.");

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
