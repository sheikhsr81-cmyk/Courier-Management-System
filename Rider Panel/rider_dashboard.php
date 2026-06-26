<?php
include("../config.php");
session_start();

if (!isset($_SESSION['rider_id'])) {
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    mysqli_query($conn, "
        UPDATE users
        SET status='$status'
        WHERE id='$id'
    ");

    header("Location: rider_dashboard.php");
    exit();
}

$result = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE rider_id='$rider_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>

<html>

<head>
    <title>🛵 Rider Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            padding: 25px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar a {
            display: block;
            padding: 16px 25px;
            color: white;
            text-decoration: none;
            transition: .3s;
        }

        .sidebar a:hover {
            background: #2563eb;
            padding-left: 35px;
        }

        .main {
            margin-left: 270px;
            padding: 30px;
        }

        .card {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0f172a;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background: #f8fafc;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .delivered {
            background: #dcfce7;
            color: #166534;
        }

        select {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        button {
            background: #0f172a;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .view-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            display: inline-block;
            white-space: nowrap;
        }

        .view-btn:hover {
            background: #1d4ed8;
        }

        .action-box {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        @media(max-width:768px) {

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
                padding: 15px;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

</head>

<body>

    <div class="sidebar">
        <h2>🛵 Rider Panel</h2>
        <a href="rider_dashboard.php">Dashboard</a>
        <a href="rider_logout.php">Logout</a>
    </div>

    <div class="main">

        <div class="card">

            <h2>📦 Delivery Orders</h2>
            <br>
            <table>

                <tr>
                    <th>Tracking</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <td><?php echo $row['tracking_number']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['address']; ?></td>

                        <td>

                            <?php
                            $class = "pending";

                            if ($row['status'] == "Picked Up" || $row['status'] == "In Transit") {
                                $class = "progress";
                            } elseif ($row['status'] == "Out For Delivery") {
                                $class = "outdelivery";
                            } elseif ($row['status'] == "Delivered") {
                                $class = "delivered";
                            }
                            ?>

                            <span class="status <?php echo $class; ?>">
                                <?php echo $row['status']; ?>
                            </span>

                        </td>

                        <td>

                            <form method="POST">

                                <div class="action-box">

                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                    <select name="status">

                                        <option value="Picked Up" <?php if ($row['status'] == "Picked Up")
                                            echo "selected"; ?>>
                                            Picked Up
                                        </option>

                                        <option value="In Transit" <?php if ($row['status'] == "In Transit")
                                            echo "selected"; ?>>
                                            In Transit
                                        </option>

                                        <option value="Out For Delivery" <?php if ($row['status'] == "Out For Delivery")
                                            echo "selected"; ?>>
                                            Out For Delivery
                                        </option>

                                        <option value="Delivered" <?php if ($row['status'] == "Delivered")
                                            echo "selected"; ?>>
                                            Delivered
                                        </option>

                                    </select>

                                    <button type="submit" name="update">
                                        Update
                                    </button>

                                    <a class="view-btn" href="view_bill.php?id=<?php echo $row['id']; ?>">
                                        View Bill
                                    </a>

                                </div>

                            </form>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>

</html>