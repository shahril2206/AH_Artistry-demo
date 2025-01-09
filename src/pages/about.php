<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="AH Artistry - About Us">
    <meta name="keywords" content="AH Artistry, makeup, beauty, about us">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/32a41a609f.js" crossorigin="anonymous"></script>
    <title>About | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="home.php"><img class="logo" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="book.php">BOOK NOW</a></li>
                <li><a class="active" href="about.php">ABOUT US</a></li>
                <li><a onclick="ToFooter()" style="cursor: pointer;">CONTACT US</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="about-section"></div>
            <div class="about-container">
                <div class="about-left">
                    <img src="../../img/img1.png" alt="About Img" />
                </div>
                <div class="about-right">
                    <h1>Aiman Hakim</h1>
                    <h2>About Me</h2>
                    <p>In 2018,he launched his business, and it continues to flourish successfully, still going strong to this day. Driven by his deep passion for makeup as a beloved hobby, he decided to turn his creative talent into a profitable venture, seeking a way to make money from what he truly loved. With his hard-earned savings, he embarked on his entrepenaurial journey, transforming his passion for cosmetics into a thriving makeup business.</p>
                    <p>Driven by creativity and a keen eye for beauty, I am dedicated to providing top-notch makeup services that enhance the natural beauty of my clients. Join me on this beautiful journey and let's create stunning looks together!</p>
                    <a class="button" href="book.php">Book Now</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <a href="home.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a href="book.php">BOOK NOW</a></li>
                <li><a class="active" href="about.php">ABOUT US</a></li>
            </ul>
        </div>
        <div class="footer-content" style="display: flex;">
            <div class="socmed-box">
                <a href="https://www.facebook.com/aimanh123?mibextid=LQQJ4d" target="_blank"><span class="fa fa-facebook-square"></span></a>
                <a href="https://www.instagram.com/aimanhhakim" target="_blank"><span class="fa fa-instagram"></span></a>
                <a href="http://www.wasap.my/601112641980" target="_blank"><span class="fa fa-whatsapp"></span></a>
                <a href="mailto: aimanh355@gmail.com"><span class="fas fa-envelope footer-icon"></span></a>
            </div>
        </div>
        <div class="availability-footer">
            <h2>Availability:</h2>
            <pre>
Everyday
4:00AM TO 12:00AM
Kuching & Kota Samarahan
            </pre>
        </div>
        <p class="rights-reserved">2023 © All Rights Reserved</p>
    </footer>
</body>
<script src="../js/main.js?v=<?php echo time(); ?>"></script>
</html>