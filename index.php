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


        .hero {
            min-height: 85vh;
            background: linear-gradient(rgba(15, 23, 42, .85),
                    rgba(15, 23, 42, .85)),
                url('https://images.unsplash.com/photo-1553413077-190dd305871c?w=1600');
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


        .stats {
            width: 90%;
            max-width: 1200px;
            margin: -60px auto 50px;
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

        @media(max-width:768px) {

            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .hero h1 {
                font-size: 40px;
            }

            .hero p {
                font-size: 18px;
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
            <a href="track.php">Track</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn">Register Now</a>
        </div>
    </nav>


    <section class="hero">
        <div class="hero-content">
            <h1>Delivering Packages With Speed & Trust</h1>

            <p>
                Courier management system provides secure courier services,
                real-time shipment tracking, professional riders,
                and nationwide parcel delivery solutions.
            </p>

            <a href="track.php" class="btn">Track Shipment</a>
        </div>
    </section>

    <br><br><br><br><br><br>

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
                <p>
                    Same-day and next-day delivery services
                    for urgent parcels and business shipments.
                </p>
            </div>

            <div class="box">
                <div class="icon">📦</div>
                <h3>Live Parcel Tracking</h3>
                <p>
                    Track your shipment in real-time using
                    your unique tracking number.
                </p>
            </div>

            <div class="box">
                <div class="icon">🛡️</div>
                <h3>Secure Handling</h3>
                <p>
                    Every package is carefully managed
                    to ensure safe and secure delivery.
                </p>
            </div>

        </div>
    </section>


    <section class="section">
        <h2>Why Choose Courier Management System?</h2>

        <div class="grid">

            <div class="box">
                <h3>24/7 Support</h3>
                <p>
                    Dedicated support team available
                    whenever you need assistance.
                </p>
            </div>

            <div class="box">
                <h3>Nationwide Coverage</h3>
                <p>
                    Delivering parcels across Pakistan
                    quickly and efficiently.
                </p>
            </div>

            <div class="box">
                <h3>Affordable Pricing</h3>
                <p>
                    Competitive delivery rates for
                    individuals and businesses.
                </p>
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

        <p>
            Track your shipment or create a new delivery request today.
        </p>

        <a href="track.php" class="btn">Track Now</a>
    </section>

    <footer class="footer">

        <div class="footer-container">

            <div class="footer-box">
                <h3>Courier Management System</h3>
                <p>
                    Delivering trust, speed and reliability
                    with advanced courier management system.
                </p>
            </div>

            <div class="footer-box">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="track.php">Track Parcel</a>
                <a href="register.php">Register Now</a>
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

</body>

</html>