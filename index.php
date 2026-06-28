<?php
session_start();

$loggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$loginRequired = isset($_GET['login_required']) && !$loggedIn;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📦Courier Management System</title>

    <style>
* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
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
            color: #fff;
            font-size: 28px;
            font-weight: bold;
        }

        .logo a {
            color: white;
            list-style: none;
            text-decoration: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-size: 16px;
            transition: .3s;
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
            color: #cbd5e1 !important;
            margin-left: 25px;
            font-size: 15px;
        }

        .nav-links a.disabled-link {
            color: #64748b;
            cursor: not-allowed;
            opacity: .6;
        }

        .nav-links a.disabled-link:hover {
            color: #64748b;
        }

        .track-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, .65);
            backdrop-filter: blur(2px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            opacity: 0;
            animation: fadeInOverlay .25s ease forwards;
        }

        @keyframes fadeInOverlay {
            to { opacity: 1; }
        }

        .track-modal-box {
            background: #fff;
            width: 100%;
            max-width: 420px;
            margin: 20px;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .25);
            padding: 36px 32px 30px;
            text-align: center;
            position: relative;
            transform: translateY(-10px);
            animation: slideInModal .25s ease forwards;
        }

        @keyframes slideInModal {
            to { transform: translateY(0); }
        }

        .track-modal-close {
            position: absolute;
            top: 14px;
            right: 16px;
            background: none;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
            padding: 4px;
        }

        .track-modal-close:hover {
            color: #475569;
        }

        .track-modal-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 18px;
            background: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #2563eb;
        }

        .track-modal-box h3 {
            font-size: 21px;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .track-modal-box p {
            font-size: 14.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 26px;
        }

        .track-modal-actions {
            display: flex;
            gap: 12px;
        }

        .track-modal-actions a {
            flex: 1;
            padding: 12px 0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
            transition: .2s;
        }

        .track-modal-actions .primary-action {
            background: #2563eb;
            color: #fff;
        }

        .track-modal-actions .primary-action:hover {
            background: #1d4ed8;
        }

        .track-modal-actions .secondary-action {
            background: #f1f5f9;
            color: #0f172a;
        }

        .track-modal-actions .secondary-action:hover {
            background: #e2e8f0;
        }

        .hero {
            min-height: 85vh;
            background: linear-gradient(rgba(15, 23, 42, .85), rgba(15, 23, 42, .85)), url('images/Hero.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 40px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 20px;
            color: #e2e8f0;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: .3s;
        }

        .btn:hover {
            background: #2563eb;
        }

        .btn.disabled-btn {
            background: #64748b;
            cursor: not-allowed;
        }

        .btn.disabled-btn:hover {
            background: #64748b;
        }

        .hero-note {
            display: block;
            margin-top: 12px;
            font-size: 14px;
            color: #cbd5e1;
        }

        .hero-note a {
            color: #93c5fd;
            text-decoration: underline;
        }

        .stats {
            width: 90%;
            max-width: 1200px;
            margin: 60px auto 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat-box {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .1);
        }

        .stat-box h2 {
            color: #2563eb;
            font-size: 36px;
            margin-bottom: 10px;
        }

        .section {
            padding: 80px 8%;
        }

        .section h2 {
            text-align: center;
            font-size: 40px;
            margin-bottom: 50px;
            color: #0f172a;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .box {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .box:hover {
            transform: translateY(-10px);
        }

        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .box h3 {
            margin-bottom: 15px;
            color: #1d4ed8;
        }

        .box p {
            line-height: 1.7;
            color: #555;
        }

        .cta {
            background: #2563eb;
            color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .cta h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .cta .btn {
            background: white;
            color: #2563eb;
        }

        .cta .btn.disabled-btn {
            background: #cbd5e1;
            color: #64748b;
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

        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            color: #94a3b8;
        }

     /* ===========================
   TABLETS
=========================== */
@media (max-width:768px){

    body{
        overflow-x:hidden;
    }

    .navbar{
        flex-direction:column;
        align-items:center;
        padding:15px 5%;
        gap:15px;
    }

    .logo{
        font-size:24px;
        text-align:center;
    }

    .nav-links{
        width:100%;
        flex-wrap:wrap;
        justify-content:center;
        gap:10px;
    }

    .nav-links a{
        margin:0;
        font-size:15px;
    }

    .nav-links .btn{
        padding:10px 18px;
    }

    .welcome-text{
        width:100%;
        text-align:center;
        margin:5px 0;
    }

    .hero{
        min-height:70vh;
        padding:60px 20px;
    }

    .hero-content{
        max-width:100%;
    }

    .hero h1{
        font-size:42px;
        line-height:1.2;
    }

    .hero p{
        font-size:18px;
    }

    .stats{
        width:95%;
        grid-template-columns:repeat(2,1fr);
    }

    .section{
        padding:60px 5%;
    }

    .section h2{
        font-size:34px;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .box{
        padding:25px;
    }

    .cta h2{
        font-size:34px;
    }

    .footer-container{
        grid-template-columns:repeat(2,1fr);
        gap:25px;
    }

}

/* ===========================
   MOBILE
=========================== */

@media (max-width:480px){

    .navbar{
        padding:12px;
    }

    .logo{
        font-size:20px;
    }

    .nav-links{
        flex-direction:column;
        align-items:center;
    }

    .nav-links a,
    .nav-links .btn{
        width:100%;
        max-width:250px;
        text-align:center;
    }

    .hero{
        padding:50px 15px;
        min-height:65vh;
    }

    .hero h1{
        font-size:30px;
    }

    .hero p{
        font-size:16px;
        line-height:1.6;
    }

    .btn{
        width:100%;
        max-width:260px;
    }

    .hero-note{
        font-size:13px;
    }

    .stats{
        grid-template-columns:1fr;
    }

    .stat-box{
        padding:25px;
    }

    .stat-box h2{
        font-size:30px;
    }

    .section{
        padding:45px 15px;
    }

    .section h2{
        font-size:28px;
        margin-bottom:30px;
    }

    .box{
        padding:20px;
    }

    .icon{
        font-size:42px;
    }

    .cta{
        padding:50px 15px;
    }

    .cta h2{
        font-size:28px;
    }

    .cta p{
        font-size:16px;
    }

    .footer{
        padding:40px 15px 20px;
    }

    .footer-container{
        grid-template-columns:1fr;
        text-align:center;
    }

    .footer-box a:hover{
        padding-left:0;
    }

    .track-modal-box{
        width:95%;
        padding:25px 20px;
    }

    .track-modal-actions{
        flex-direction:column;
    }

}
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo"><a href="index.php">📦 Courier Management System</a></div>
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

    <?php if ($loginRequired) { ?>
        <div class="track-modal-overlay" id="trackModal">
            <div class="track-modal-box">
                <button class="track-modal-close" onclick="closeTrackModal()" aria-label="Close">&times;</button>
                <div class="track-modal-icon">🔒</div>
                <h3>Login Required</h3>
                <p>
                    You need an account to track your shipment.
                    Please log in or create a free account to continue.
                </p>
                <div class="track-modal-actions">
                    <a href="login.php" class="primary-action">Login</a>
                    <a href="register.php" class="secondary-action">Register</a>
                </div>
            </div>
        </div>
    <?php } ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Delivering Packages With Speed & Trust</h1>
            <p>
                Courier management system provides secure courier services,
                real-time shipment tracking, professional riders,
                and nationwide parcel delivery solutions.
            </p>

            <?php if ($loggedIn) { ?>
                <a href="track.php" class="btn">Track Shipment</a>
            <?php } else { ?>
                <a href="index.php?login_required=1" class="btn disabled-btn">Track Shipment</a>
                <span class="hero-note">
                    Already a customer? <a href="login.php">Login</a> or
                    <a href="register.php">Register</a> to track your shipment.
                </span>
            <?php } ?>
        </div>
    </section>

    <section class="stats">
        <div class="stat-box">
            <h2>15K+</h2>
            <p>Successful Deliveries</p>
        </div>
        <div class="stat-box">
            <h2>500+</h2>
            <p>Business Clients</p>
        </div>
        <div class="stat-box">
            <h2>50+</h2>
            <p>Areas Covered</p>
        </div>
        <div class="stat-box">
            <h2>99%</h2>
            <p>Success Rate</p>
        </div>
    </section>

    <section class="section">
        <h2>Our Services</h2>
        <div class="grid">
            <div class="box">
                <div class="icon">🚚</div>
                <h3>Fast Delivery</h3>
                <p>Same-day and next-day delivery services for urgent parcels and business shipments.</p>
            </div>
            <div class="box">
                <div class="icon">📦</div>
                <h3>Live Parcel Tracking</h3>
                <p>Track your shipment in real-time using your unique tracking number.</p>
            </div>
            <div class="box">
                <div class="icon">🛡️</div>
                <h3>Secure Handling</h3>
                <p>Every package is carefully managed to ensure safe and secure delivery.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Why Choose Courier Management System?</h2>
        <div class="grid">
            <div class="box">
                <h3>24/7 Support</h3>
                <p>Dedicated support team available whenever you need assistance.</p>
            </div>
            <div class="box">
                <h3>Nationwide Coverage</h3>
                <p>Delivering parcels across Pakistan quickly and efficiently.</p>
            </div>
            <div class="box">
                <h3>Affordable Pricing</h3>
                <p>Competitive delivery rates for individuals and businesses.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Customer Reviews</h2>
        <div class="grid">
            <div class="box">
                ⭐⭐⭐⭐⭐
                <br><br>
                "Very fast service and excellent tracking system."
                <br><br>
                <strong>– Ahmed R.</strong>
            </div>
            <div class="box">
                ⭐⭐⭐⭐⭐
                <br><br>
                "Safe delivery and professional staff."
                <br><br>
                <strong>– Sara K.</strong>
            </div>
            <div class="box">
                ⭐⭐⭐⭐⭐
                <br><br>
                "Best courier company I've used so far."
                <br><br>
                <strong>– Ali M.</strong>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Ready To Ship Your Parcel?</h2>
        <p>Track your shipment or create a new delivery request today.</p>

        <?php if ($loggedIn) { ?>
            <a href="track.php" class="btn">Track Now</a>
        <?php } else { ?>
            <a href="index.php?login_required=1" class="btn disabled-btn">Track Now</a>
        <?php } ?>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-box">
                <h3>Courier Management System</h3>
                <p>Delivering trust, speed and reliability with advanced courier management system.</p>
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
        <div class="copyright">
            © Courier Management System | All Rights Reserved
        </div>
    </footer>
    <script>
        function closeTrackModal() {
            const modal = document.getElementById('trackModal');
            if (modal) {
                modal.style.display = 'none';
            }
            const url = new URL(window.location);
            url.searchParams.delete('login_required');
            window.history.replaceState({}, '', url);
        }

        document.addEventListener('click', function (e) {
            const modal = document.getElementById('trackModal');
            if (modal && e.target === modal) {
                closeTrackModal();
            }
        });
    </script>
</body>

</html>
