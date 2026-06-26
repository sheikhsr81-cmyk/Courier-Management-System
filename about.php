<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - FastCourier</title>

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


        .hero {
            height: 70vh;
            background:
                linear-gradient(rgba(15, 23, 42, .80), rgba(15, 23, 42, .80)),
                url('images/courier-banner.avif');

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


        @media(max-width:768px) {

            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .about {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 38px;
            }

            .hero p {
                font-size: 17px;
            }
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
    </style>
</head>

<body>


    <nav class="navbar">

        <div class="logo"><a href="index.php">📦Courier Management System</a></div>

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

            <h1>About CMS</h1>

            <p>
                Delivering trust, speed and reliability through
                innovative courier management solutions.
            </p>

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
                    <p>
                        Customers submit shipment details and create
                        a delivery request through our system.
                    </p>
                </div>

                <div class="card">
                    <h3>2️⃣ Parcel Pickup</h3>
                    <p>
                        Our rider collects the parcel from the sender
                        and updates the tracking information.
                    </p>
                </div>

                <div class="card">
                    <h3>3️⃣ Transportation</h3>
                    <p>
                        The shipment moves through our courier network
                        with real-time tracking updates.
                    </p>
                </div>

                <div class="card">
                    <h3>4️⃣ Safe Delivery</h3>
                    <p>
                        The parcel is delivered securely to the receiver
                        and marked as completed.
                    </p>
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

                    <p>
                        To provide reliable, secure and technology-driven
                        courier services that exceed customer expectations
                        and ensure complete satisfaction.
                    </p>

                </div>

                <div class="card">

                    <h3>🚀 Our Vision</h3>

                    <p>
                        To become the most trusted courier and logistics
                        management company by delivering innovation,
                        speed and excellence.
                    </p>

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

                    <p>
                        Our admin team manages operations, shipment
                        records, customer support and system monitoring.
                    </p>

                </div>

                <div class="card">

                    <h3>🏢 Agents</h3>

                    <p>
                        Agents handle parcel bookings, customer
                        interactions and shipment processing.
                    </p>

                </div>

                <div class="card">

                    <h3>🛵 Riders</h3>

                    <p>
                        Professional riders ensure fast pickup and
                        secure delivery of every parcel.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <section style="
background:#2563eb;
padding:90px 20px;
text-align:center;
color:white;
">

        <h2 style="
    font-size:45px;
    margin-bottom:20px;
    ">
            Ready To Ship Your Parcel?
        </h2>

        <p style="
    font-size:18px;
    margin-bottom:30px;
    ">
            Track shipments, manage deliveries and experience
            a smarter courier solution with CMS.
        </p>

        <a href="track.php" style="
    background:white;
    color:#2563eb;
    text-decoration:none;
    padding:15px 35px;
    border-radius:10px;
    font-weight:bold;
    display:inline-block;
    ">
            Track Parcel
        </a>

    </section>


    <footer class="footer">

        <div class="footer-container">

            <div class="footer-box">
                <h3>Courier Management System</h3>
                <p>
                    Delivering trust, speed and reliability through
                    advanced courier management solutions.
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

        <div class="footer-bottom">
            © 2026 Courier Management System | All Rights Reserved
        </div>

    </footer>

</body>

</html>