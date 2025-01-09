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
    <title>Posts | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar admin-element">
            <a href="appointments.php"><img class="logo-admin" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a href="appointments.php" class="admin-element">APPOINTMENTS</a></li>
                <li><a class="active" href="posts.php" class="admin-element">POSTS</a></li>
                <li><a href="profile.php" class="admin-element"><i class="fa-solid fa-user profile-icon"></i>Aiman Hakim</a></li>
                <li><a href="../logout.php " class="logout">LOGOUT</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="post-section" style="margin-bottom: 15%;top: 17vh;">
            <?php
                $prev_post_query = "SELECT * FROM posts WHERE PostID<'$post_id' ORDER BY PostID DESC";
                $prev_post = mysqli_query($conn, $prev_post_query);
            ?>
            <?php if($prev_row = $prev_post->fetch_assoc()): ?>
            <button class="post-section-btn" onclick="window.location.href='posts.php?post_id=<?php echo $prev_row['PostID']; ?>';"><</button>
            <?php else: ?>
            <button class="post-section-btn" disabled><</button>
            <?php endif; ?>
            <?php
                $curr_post_query = "SELECT * FROM posts WHERE PostID = '$post_id' LIMIT 1";
                $curr_post = mysqli_query($conn, $curr_post_query);

                $row = $curr_post->fetch_assoc();
            ?>
            <div class="post-container admin-element">
                <div class="post-img">
                    <img src="../../img/posts/<?php echo $row["PostPic"]; ?>" alt="img">
                </div>
                <div class="post">
                    <div class="post-header admin-element">
                        <p class="post-title"><?php echo $row["PostTitle"]; ?></p>
                        <p class="post-date"><?php echo $row["PostDate"]; ?></p>
                    </div>
                    <div class="post-content">
                        <p><?php echo $row["PostContent"]; ?></p>
                    </div>
                </div>
                <button class="del-post" title="Delete Post" onclick="DeletePost(<?php echo $row['PostID']; ?>)"><span class="fa-solid fa-trash"></span></button>
            </div>
            <form action="" method="post" class="booking-form admin-element popup-form delete-post-form" id="delete_post_form<?php echo $row['PostID']; ?>">
                <h3>Delete this post?</h3>

                <input type="hidden" name="del_post_id" value="<?php echo $row["PostID"]; ?>">
                    
                <div style="display: flex;">
                    <button type="submit" class="set-appointment-btn" name="delete_post">YES</button>
                    <button onclick="closePopup()">NO</button>
                </div>
            </form>
            <?php
                $next_post_query = "SELECT * FROM posts WHERE PostID>'$post_id' ORDER BY PostID ASC";
                $next_post = mysqli_query($conn, $next_post_query);
            ?>
            <?php if($next_row = $next_post->fetch_assoc()): ?>
            <button class="post-section-btn" onclick="window.location.href='posts.php?post_id=<?php echo $next_row['PostID']; ?>';">></button>
            <?php else: ?>
            <button class="post-section-btn" disabled>></button>
            <?php endif; ?>
        </div>
        <button class="addpost-btn" onclick="showAddPost()">+ADD POST</button>
        <div class="background-popup" title="cancel and close form" style="height: 95vh;" id="background_popup" onclick="closeAddPost()"></div>
        <form action="" method="post" class="booking-form popup-form addpost-form admin-element" id="AddPost_Form" enctype="multipart/form-data">
            <button class="close-btn" title="cancel and close form" style="top: 5%;" onclick="closeAddPost()">X</button>
            <h3>Add Post Form</h3>
            <label for="post_img"><b>Upload an Image</b></label>
            <input type="file" name="post_img" style="background: white;color: #1E1E1E;" required>
            
            <label for="post_title"><b>Post Title</b></label>
            <input type="text" placeholder="Enter the Post Title" name="post_title" required>
            
            <label for="post"><b>Post</b></label>
            <textarea name="post" rows="10" cols="50" placeholder="Write something..." required></textarea>

            <button type="submit" class="post-btn" name="add_post">POST</button>
        </form>
    </main>
    <footer class="admin-element">
        <div class="footer-content">
            <a href="appointments.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo-admin"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a class="admin-element" href="appointments.php">APPOINTMENTS</a></li>
                <li><a class="active" href="posts.php" class="admin-element">POSTS</a></li>
                <li><a href="profile.php" class="admin-element"><i class="fa-solid fa-user profile-icon"></i>Aiman Hakim</a></li>
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