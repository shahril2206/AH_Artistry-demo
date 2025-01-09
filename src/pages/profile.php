<?php
session_start();
include("../../config/connection.php");
include("../server.php");
?>

<?php
// Check if the user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

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
    <title>Profile | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar admin-element">
            <a href="appointments.php"><img class="logo-admin" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a href="appointments.php" class="admin-element">APPOINTMENTS</a></li>
                <li><a href="posts.php" class="admin-element">POSTS</a></li>
                <li><a class="active" href="profile.php"><i class="fa-solid fa-user profile-icon"></i>Aiman Hakim</a></li>
                <li><a href="../logout.php" class="logout">LOGOUT</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="about-section"></div>
        <?php
            $admin_query = "SELECT * FROM admin LIMIT 1";
            $admin = mysqli_query($conn, $admin_query);

            $row_admin = $admin->fetch_assoc();
        ?>
            <div class="about-container">
                <div class="about-left">
                    <img src="../../img/<?php echo $row_admin["ProfilePic"]; ?>" alt="About Img" />
                </div>
                <div class="about-right admin-element">
                    <h1 class="admin-element"><?php echo $row_admin["username"]; ?></h1>
                    <h2 class="admin-element">About Me</h2>
                    <p>In 2018,he launched his business, and it continues to flourish successfully, still going strong to this day. Driven by his deep passion for makeup as a beloved hobby, he decided to turn his creative talent into a profitable venture, seeking a way to make money from what he truly loved. With his hard-earned savings, he embarked on his entrepenaurial journey, transforming his passion for cosmetics into a thriving makeup business.</p>
                    <p>Driven by creativity and a keen eye for beauty, I am dedicated to providing top-notch makeup services that enhance the natural beauty of my clients. Join me on this beautiful journey and let's create stunning looks together!</p>
                    <button class="button admin-element" onclick="notyet()">Edit About Me</button>
                </div>
            </div>
        </div>

        <div class="services-section" style="top: 0vh;margin-bottom: 7%;">
            <h2>Services List</h2>
            <?php
                $services_query = "SELECT * FROM services ORDER BY serviceID ASC";
                $services = mysqli_query($conn, $services_query);
            ?>
            <div class="service-box-container">
                <?php while($row_service = $services->fetch_assoc()): ?>
                <div class="service-box admin-element" style="grid-template-rows: 9fr 1.5fr 1fr;">
                    <img src="../../img/<?php echo $row_service["ServiceImg"]; ?>" alt="img">
                    <div>       
                        <p class="title"><?php echo $row_service["ServiceName"]; ?></p>
                        <p class="price">RM 450</p>
                    </div>
                    <div class="service-btn-box">
                        <button class="edit-service" onclick="notyet()">EDIT</button>
                        <button class="delete-service" onclick="notyet()">DELETE</button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <button class="addservice-btn" onclick="notyet()">+ADD SERVICE</button>
        </div>

        <div class="availability-setting">
            <h3>Availability Setting</h3>
            <fieldset class="admin-element">
                <input class="admin-element" type="text" placeholder="EVERY WEEKDAYS">
                <div class="admin-element">
                    <input type="time" class="admin-element">
                    TO
                    <input type="time" class="admin-element">
                </div>
                <input class="admin-element" type="text" placeholder="KUCHING,SARAWAK">
                <button onclick="notyet()">SET</button>
            </fieldset>
        </div>

        <div class="availability-setting cred-setting">
            <h3>Account Credibility Setting</h3>
            <fieldset class="admin-element">
                <input class="admin-element" type="text" placeholder="OLD USERNAME">
                <input class="admin-element" type="text" placeholder="NEW USERNAME">
                <input class="admin-element" type="password" placeholder="OLD PASSWORD">
                <input class="admin-element" type="password" placeholder="NEW PASSWORD">
                <input class="admin-element" type="password" placeholder="RETYPE NEW PASSWORD">
                <button onclick="notyet()">SET</button>
            </fieldset>
        </div>

    </main>

    <footer class="admin-element">
        <div class="footer-content">
            <a href="appointments.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo-admin"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a class="admin-element" href="appointments.php">APPOINTMENTS</a></li>
                <li><a href="posts.php" class="admin-element">POSTS</a></li>
                <li><a class="active" href="profile.php" class="admin-element"><i class="fa-solid fa-user profile-icon"></i>Aiman Hakim</a></li>
            </ul>
        </div>
        <div class="footer-content">
            <div class="socmed-box">
                <a href="https://www.facebook.com/aimanh123?mibextid=LQQJ4d" target="_blank"><span class="fa fa-facebook-square admin-element"></span></a>
                <a href="https://www.instagram.com/aimanhhakim" target="_blank"><span class="fa fa-instagram admin-element"></span></a>
                <a href="http://www.wasap.my/601112641980" target="_blank"><span class="fa fa-whatsapp admin-element"></span></a>
                <a href="mailto: aimanh355@gmail.com"><span class="fas fa-envelope footer-icon admin-element"></span></a>
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