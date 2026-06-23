<?php
include("../config.php");

$result = null;
$error = "";

if(isset($_GET['track']))
{
    $tracking = mysqli_real_escape_string($conn,$_GET['track']);

    $query = mysqli_query($conn,
    "SELECT tracking_number,status FROM users
     WHERE tracking_number='$tracking'");

    if(mysqli_num_rows($query)>0)
    {
        $result = mysqli_fetch_assoc($query);
    }
    else
    {
        $error = "Tracking Number Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>📦 Track Shipment</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}


.container{
    width:90%;
    max-width:650px;
    margin:60px auto;
    background:white;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

h2{
    text-align:center;
    margin-bottom:25px;
    color:#0f172a;
}

input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
    font-size:15px;
}

button{
    width:100%;
    padding:14px;
    margin-top:12px;
    border:none;
    background:#0f172a;
    color:white;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    transition:.3s;
}

button:hover{
    background:#1d4ed8;
}

.error{
    color:red;
    text-align:center;
    margin-top:15px;
}

.result{
    margin-top:30px;
}

.track-number{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin-bottom:25px;
    color:#0f172a;
}

.timeline{
    position:relative;
    padding-left:20px;
    border-left:3px solid #e5e7eb;
}

.step{
    padding:15px 15px;
    margin-bottom:10px;
    background:#f9fafb;
    border-radius:10px;
    color:#6b7280;
    font-weight:500;
    position:relative;
}

.step.active{
    background:#dcfce7;
    color:#166534;
    border-left:5px solid #22c55e;
    font-weight:bold;
}

.step::before{
    content:"";
    width:10px;
    height:10px;
    background:#d1d5db;
    position:absolute;
    left:-26px;
    top:18px;
    border-radius:50%;
}

.step.active::before{
    background:#22c55e;
}


@media(max-width:768px){
    body{
        padding:10px;
    }

    .container{
        width:95%;
        margin:30px auto;
        padding:20px;
    }
}

@media(max-width:600px){
    input, button{
        width:100%;
        box-sizing:border-box;
    }
}

@media(max-width:600px){
    .timeline{
        padding-left:18px;
    }

    .step{
        padding:12px;
        font-size:14px;
    }

    .step::before{
        left:-24px;
    }
}
@media(max-width:480px){
    h2{
        font-size:18px;
    }

    .track-number{
        font-size:16px;
    }
}
</style>
</head>

<body>

<br><br><br><br>
<div class="container">

<h2>📍 Track Your Shipment</h2>

<form method="GET">
    <input type="text" name="track" placeholder="Enter Tracking Number" required>
    <button type="submit">Track Now</button>
</form>

<?php if($error!=""){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<?php if($result){

$status = $result['status'];

$steps = [
    "Pending",
    "Picked Up",
    "In Transit",
    "Delivered",
    "Cancelled"
];

$current = array_search($status,$steps);
?>

<div class="result">

<div class="track-number">
Tracking #: <?php echo $result['tracking_number']; ?>
</div>

<div class="timeline">

<?php
foreach($steps as $index=>$step)
{
    $class = ($index <= $current)
    ? "step active"
    : "step";

    echo "<div class='$class'>$step</div>";
}
?>

</div>

</div>

<?php } ?>

</div>

</body>
</html>