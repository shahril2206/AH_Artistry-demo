<?php
session_start();
include("../../config/connection.php");
include("../server.php");
?>

<?php
$post_id = 0;
if(isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
}else{
    $post_query = "SELECT * FROM posts ORDER BY ABS(DATEDIFF(PostDate, CURDATE())) LIMIT 1";
    $post = mysqli_query($conn, $post_query);

    $current_post = $post->fetch_assoc();

    $post_id = $current_post["PostID"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Male_Fashion Template">
    <meta name="keywords" content="Male_Fashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/32a41a609f.js" crossorigin="anonymous"></script>
    <title>Home | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="home.php"><img class="logo" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a class="active" href="home.php">HOME</a></li>
                <li><a href="book.php">BOOK NOW</a></li>
                <li><a href="about.php">ABOUT US</a></li>
                <li><a onclick="ToFooter()"  style="cursor: pointer;">CONTACT US</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="post-section">
            <?php
                $prev_post_query = "SELECT * FROM posts WHERE PostID<'$post_id' ORDER BY PostID DESC";
                $prev_post = mysqli_query($conn, $prev_post_query);
            ?>
            <?php if($prev_row = $prev_post->fetch_assoc()): ?>
            <button class="post-section-btn" onclick="window.location.href='home.php?post_id=<?php echo $prev_row['PostID']; ?>';"><</button>
            <?php else: ?>
            <button class="post-section-btn" disabled><</button>
            <?php endif; ?>
            <?php
                $curr_post_query = "SELECT * FROM posts WHERE PostID = '$post_id' LIMIT 1";
                $curr_post = mysqli_query($conn, $curr_post_query);

                $row = $curr_post->fetch_assoc();
            ?>
            <div class="post-container">
                <div class="post-img">
                    <img src="../../img/posts/<?php echo $row["PostPic"]; ?>" alt="img">
                </div>
                <div class="post">
                    <div class="post-header">
                        <p class="post-title"><?php echo $row["PostTitle"]; ?></p>
                        <p class="post-date"><?php echo $row["PostDate"]; ?></p>
                    </div>
                    <div class="post-content">
                        <p><?php echo $row["PostContent"]; ?></p>
                    </div>
                </div>
            </div>
            <?php
                $next_post_query = "SELECT * FROM posts WHERE PostID>'$post_id' ORDER BY PostID ASC";
                $next_post = mysqli_query($conn, $next_post_query);
            ?>
            <?php if($next_row = $next_post->fetch_assoc()): ?>
            <button class="post-section-btn" onclick="window.location.href='home.php?post_id=<?php echo $next_row['PostID']; ?>';">></button>
            <?php else: ?>
            <button class="post-section-btn" disabled>></button>
            <?php endif; ?>
        </div>

        <div class="services-section">
            <h2>What we offer</h2>
            <?php
                $services_query = "SELECT * FROM services ORDER BY serviceID ASC";
                $services = mysqli_query($conn, $services_query);
            ?>
            <div class="service-box-container">
            <?php while($row = $services->fetch_assoc()): ?>
                <div class="service-box">
                    <img src="../../img/<?php echo $row["ServiceImg"]; ?>" alt="img">
                    <div>
                        <p class="title"><?php echo $row["ServiceName"]; ?></p>
                        <p class="price">RM <?php echo $row["ServicePrice"]; ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
            <p>*Note that the price is not fixed</p>
        </div>

    </main>
    <footer>
        <div class="footer-content">
            <a href="home.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a class="active" href="home.php">HOME</a></li>
                <li><a href="book.php">BOOK NOW</a></li>
                <li><a href="about.php">ABOUT US</a></li>
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