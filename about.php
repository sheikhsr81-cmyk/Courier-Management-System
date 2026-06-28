<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
                        scroll-behavior: smooth;

        }

        body {
            background: #f8fafc;
            color: #333;
        }

        .navbar {
            background: #0f172a;
            padding: 18px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            color: white;
            font-size: 28px;
            font-weight: bold;
        }

        .logo a {
            color: white;
            text-decoration: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            transition: .3s;
        }

        .nav-links a:hover {
            color: #3b82f6;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-links .btn {
            background: #2563eb;
            color: white !important;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            font-size: 15px;
        }

        .nav-links .btn:hover {
            background: #1d4ed8;
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-links .btn.logout-btn {
            background: #dc2626;
        }

        .nav-links .btn.logout-btn:hover {
            background: #b91c1c;
        }

        .nav-links .welcome-text {
            color: #cbd5e1;
            margin-left: 25px;
            font-size: 15px;
        }

        .nav-links a.disabled-link {
            color: #94a3b8;
            cursor: not-allowed;
            opacity: .6;
        }

        .nav-links a.disabled-link:hover {
            color: #94a3b8;
        }

        .hero {
            height: 70vh;
            background: linear-gradient(rgba(15, 23, 42, .80), rgba(15, 23, 42, .80)), url('images/courier-banner.avif');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding-bottom: 120px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 20px;
            color: #e2e8f0;
        }

        .stats {
            width: 90%;
            max-width: 1200px;
            margin: -80px auto 70px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            position: relative;
            z-index: 100;
        }

        .stat-card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .stat-card h2 {
            color: #2563eb;
            font-size: 38px;
            margin-bottom: 10px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }

        .section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            color: #0f172a;
            font-size: 40px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            transition: .4s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, .15);
        }

        .card h3 {
            color: #2563eb;
            margin-bottom: 15px;
        }

        .card p {
            line-height: 1.7;
            color: #555;
        }

        .about {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .about img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        }

        .about p {
            line-height: 1.9;
            color: #555;
            font-size: 17px;
        }

        /* --- New CSS for CTA Section --- */
        .cta-banner {
            background: #2563eb;
            padding: 90px 20px;
            text-align: center;
            color: white;
        }

        .cta-banner h2 {
            font-size: 45px;
            margin-bottom: 20px;
        }

        .cta-banner p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .cta-banner-btn {
            background: white;
            color: #2563eb;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 10px;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .cta-banner-btn:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .cta-banner-btn.disabled-btn {
            background: #cbd5e1;
            color: #475569;
            cursor: not-allowed;
        }

        .footer {
            background: #0f172a;
            color: white;
            padding: 60px 8% 20px;
        }

        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            align-items: start;
        }

        .footer-box h3 {
            margin-bottom: 15px;
            font-size: 20px;
        }

        .footer-box p {
            color: #cbd5e1;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .footer-box a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .footer-box a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-bottom {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }

    /* =========================
   TABLET (768px)
========================= */
@media (max-width:768px){

    body{
        overflow-x:hidden;
    }

    .navbar{
        flex-direction:column;
        justify-content:center;
        align-items:center;
        padding:15px 5%;
        gap:15px;
    }

    .logo{
        font-size:24px;
        text-align:center;
    }

    .nav-links{
        display:flex;
        flex-wrap:wrap;
        justify-content:center;
        align-items:center;
        gap:12px;
        width:100%;
    }

    .nav-links a{
        margin-left:0;
    }

    .welcome-text{
        width:100%;
        text-align:center;
        margin:0;
    }

    .hero{
        height:auto;
        min-height:60vh;
        padding:100px 20px 80px;
    }

    .hero-content{
        max-width:100%;
    }

    .hero h1{
        font-size:42px;
    }

    .hero p{
        font-size:18px;
    }

    .stats{
        width:95%;
        grid-template-columns:repeat(2,1fr);
        margin-top:-60px;
    }

    .about{
        grid-template-columns:1fr;
        gap:35px;
    }

    .about img{
        order:-1;
    }

    .section{
        padding:60px 0;
    }

    .section-title h2{
        font-size:34px;
    }

    .grid{
        grid-template-columns:repeat(2,1fr);
    }

    .cta-banner{
        padding:70px 20px;
    }

    .cta-banner h2{
        font-size:34px;
    }

    .footer-container{
        grid-template-columns:repeat(2,1fr);
        gap:30px;
    }

}
/* =========================
   MOBILE (480px)
========================= */

@media (max-width:480px){

    body{
        overflow-x:hidden;
    }

    .navbar{
        padding:15px;
    }

    .logo{
        font-size:22px;
    }

    .nav-links{
        flex-direction:column;
        gap:10px;
    }

    .nav-links a,
    .nav-links .btn{
        width:100%;
        max-width:260px;
        text-align:center;
        margin-left:0;
    }

    .welcome-text{
        text-align:center;
    }

    .hero{
        min-height:55vh;
        padding:80px 15px 60px;
    }

    .hero h1{
        font-size:30px;
    }

    .hero p{
        font-size:16px;
        line-height:1.6;
    }

    .stats{
        grid-template-columns:1fr;
        margin-top:-50px;
    }

    .stat-card{
        padding:22px;
    }

    .stat-card h2{
        font-size:30px;
    }

    .section{
        padding:50px 15px;
    }

    .section-title{
        margin-bottom:35px;
    }

    .section-title h2{
        font-size:28px;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .card{
        padding:22px;
    }

    .about p{
        font-size:16px;
    }

    .cta-banner{
        padding:60px 15px;
    }

    .cta-banner h2{
        font-size:28px;
    }

    .cta-banner p{
        font-size:16px;
    }

    .cta-banner-btn{
        width:100%;
        max-width:260px;
    }

    .footer{
        padding:45px 20px 20px;
    }

    .footer-container{
        grid-template-columns:1fr;
        text-align:center;
    }

    .footer-box a:hover{
        padding-left:0;
    }

}
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo"><a href="index.php">📦Courier Management System</a></div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>

            <?php if ($loggedIn) { ?>
                <a href="track.php">Track</a>
            <?php } else { ?>
                <a href="index.php?login_required=1" class="disabled-link">Track</a>
            <?php } ?>

            <?php if ($loggedIn) { ?>
                <?php if ($userName) { ?>
                    <span class="welcome-text">Hi, <?php echo htmlspecialchars($userName); ?></span>
                <?php } ?>
                <a href="logout.php" class="btn logout-btn">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn">Register Now</a>
            <?php } ?>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>About CMS</h1>
            <p>Delivering trust, speed and reliability through innovative courier management solutions.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Who We Are</h2>
            </div>
            <div class="about">
                <div>
                    <p>
                        CMS is a modern courier management system designed
                        to provide fast, secure and reliable delivery services.
                        We connect customers, agents and riders through a smart
                        tracking system and efficient delivery network.
                    </p>
                    <br>
                    <p>
                        Our mission is to simplify logistics through technology
                        and ensure every parcel reaches its destination safely,
                        efficiently and on time.
                    </p>
                </div>
                <div>
                    <img src="images/about-banner.avif" alt="About FastCourier">
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Our Delivery Process</h2>
            </div>
            <div class="grid">
                <div class="card">
                    <h3>1️⃣ Book Parcel</h3>
                    <p>Customers submit shipment details and create a delivery request through our system.</p>
                </div>
                <div class="card">
                    <h3>2️⃣ Parcel Pickup</h3>
                    <p>Our rider collects the parcel from the sender and updates the tracking information.</p>
                </div>
                <div class="card">
                    <h3>3️⃣ Transportation</h3>
                    <p>The shipment moves through our courier network with real-time tracking updates.</p>
                </div>
                <div class="card">
                    <h3>4️⃣ Safe Delivery</h3>
                    <p>The parcel is delivered securely to the receiver and marked as completed.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background:#eef4ff;">
        <div class="container">
            <div class="section-title">
                <h2>Mission & Vision</h2>
            </div>
            <div class="grid">
                <div class="card">
                    <h3>🎯 Our Mission</h3>
                    <p>To provide reliable, secure and technology-driven courier services that exceed customer expectations and ensure complete satisfaction.</p>
                </div>
                <div class="card">
                    <h3>🚀 Our Vision</h3>
                    <p>To become the most trusted courier and logistics management company by delivering innovation, speed and excellence.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Our Professional Team</h2>
            </div>
            <div class="grid">
                <div class="card">
                    <h3>👨‍💼 Administration</h3>
                    <p>Our admin team manages operations, shipment records, customer support and system monitoring.</p>
                </div>
                <div class="card">
                    <h3>🏢 Agents</h3>
                    <p>Agents handle parcel bookings, customer interactions and shipment processing.</p>
                </div>
                <div class="card">
                    <h3>🛵 Riders</h3>
                    <p>Professional riders ensure fast pickup and secure delivery of every parcel.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <h2>Ready To Ship Your Parcel?</h2>
        <p>Track shipments, manage deliveries and experience a smarter courier solution with CMS.</p>

        <?php if ($loggedIn) { ?>
            <a href="track.php" class="cta-banner-btn">Track Parcel</a>
        <?php } else { ?>
            <a href="index.php?login_required=1" class="cta-banner-btn disabled-btn">Track Parcel</a>
        <?php } ?>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-box">
                <h3>Courier Management System</h3>
                <p>Delivering trust, speed and reliability through advanced courier management solutions.</p>
            </div>
            <div class="footer-box">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>

                <?php if ($loggedIn) { ?>
                    <a href="track.php">Track Parcel</a>
                    <a href="logout.php">Logout</a>
                <?php } else { ?>
                    <a href="index.php?login_required=1">Track Parcel</a>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register Now</a>
                <?php } ?>
            </div>
            <div class="footer-box">
                <h3>Contact Us</h3>
                <p>Karachi, Pakistan</p>
                <p>+92 300 1234567</p>
            </div>
            <div class="footer-box">
                <h3>Follow Us</h3>
                <p><a href="https://www.facebook.com/">Facebook</a></p>
                <p><a href="https://www.instagram.com/?hl=en">Instagram</a></p>
                <p><a href="https://pk.linkedin.com/">LinkedIn</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 Courier Management System | All Rights Reserved
        </div>
    </footer>

</body>

</html>
