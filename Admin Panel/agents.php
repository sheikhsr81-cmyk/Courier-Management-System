<?php
include("../config.php");

$editData = null;
$errors = [];

$allowedRegions = ['Karachi'];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM agents WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}

function validateAgentFields($data, $allowedRegions) {
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

    if (!in_array($data['region'] ?? '', $allowedRegions)) {
        $errors[] = "Invalid region selected.";
    }

    return $errors;
}

if (isset($_POST['update_agent'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];

    $errors = validateAgentFields($_POST, $allowedRegions);

    if (empty($errors)) {
        mysqli_query($conn, "UPDATE agents SET 
            name='$name',
            phone='$phone',
            region='$region'
            WHERE id=$id
        ");

        header("Location: agents.php");
        exit();
    } else {
        $editData = $_POST;
    }
}

if (isset($_POST['add_agent'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $region = $_POST['region'];

    $errors = validateAgentFields($_POST, $allowedRegions);

    if (empty($errors)) {
        mysqli_query($conn, "INSERT INTO agents (name,phone,region)
        VALUES ('$name','$phone','$region')");

        header("Location: agents.php");
        exit();
    }
    // fall through to redisplay Add form with entered values + errors
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

    input, select {
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
 <h1>Agent Management</h1>
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

                <form method="POST" id="editAgentForm" onsubmit="return validateAgentForm('editAgentForm')" novalidate>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editData['id']); ?>">

                    <input type="text" name="name" placeholder="Agent Name" maxlength="100"
                        value="<?php echo htmlspecialchars($editData['name'] ?? ''); ?>">
                    <div class="field-hint">Required, max 100 characters.</div>

                    <input type="text" name="phone" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($editData['phone'] ?? ''); ?>">
                    <div class="field-hint">Required, 7-15 digits (may include + - and spaces).</div>

                    <select name="region">
                        <?php foreach ($allowedRegions as $region) { ?>
                            <option value="<?php echo htmlspecialchars($region); ?>"
                                <?php echo (($editData['region'] ?? '') == $region) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($region); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button name="update_agent">Update Agent</button>
                </form>

            <?php } else { ?>

                <h3>Add New Agent</h3>

                <form method="POST" id="addAgentForm" onsubmit="return validateAgentForm('addAgentForm')" novalidate>

                    <input type="text" name="name" placeholder="Agent Name" maxlength="100"
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

                    <input type="text" name="phone" placeholder="Phone Number"
                        value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">

                    <select name="region">
                        <?php foreach ($allowedRegions as $region) { ?>
                            <option value="<?php echo htmlspecialchars($region); ?>"
                                <?php echo (($_POST['region'] ?? '') == $region) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($region); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button name="add_agent">Add Agent</button>

                </form>

            <?php } ?>

        </div>

    </div>

    <script>
        function validateAgentForm(formId) {
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

            const region = get("region").value.trim();
            if (!region) errors.push("Region is required.");

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
