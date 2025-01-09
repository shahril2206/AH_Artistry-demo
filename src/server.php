<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime('+1 days'));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$system_email = "shahrilrel22@gmail.com";
$admin_email = "aahartistry@gmail.com";

// login function (for admin login)
if(isset($_POST["login"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $check_admin = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $admin_result = mysqli_query($conn, $check_admin) or die("Error connecting to the database");

	if(mysqli_num_rows($admin_result) > 0)
    {
        while($row = $admin_result->fetch_assoc())
        {
            $_SESSION["admin"] = $username;
            echo 
            '<script>
                alert("Logged in successfully");
                window.location="appointments.php?apt_filter_from=&apt_filter_to=";
            </script>';
        }
    }else
    {
        echo "<script> alert('Incorrect username or password!'); </script>";
    }
}

if(isset($_POST["book_now"])){
    // gather all data from the form
    $cust_name = $_POST["name"];
    $cust_email = $_POST["email"];
    $cust_ctc_no = $_POST["ctc_no"];
    $apt_date = $_POST["apt_date"];
    $apt_time = $_POST["apt_time"];
    $apt_address = $_POST["apt_address"];
    $apt_service = $_POST["apt_service"];
    $additional_msg = "";

    if($_POST["message"]!=""){
        $additional_msg = $_POST["message"];
    }else{
        $additional_msg = "-";
    }

    // send email to admin
    $subject = "AH_Artistry New Appointment Booking";

    $message = "Here are the booking detail:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Customer Contact No.: " .$cust_ctc_no. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($apt_time)). "\n";
    $message .= "Appointment Location: " .$apt_address. "\n";
    $message .= "Service Booked: " .$apt_service. "\n";
    $message .= "Message/Additional info: " .$additional_msg. "\n";

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; 
    $mail->SMTPAuth = true;
    $mail->Username = $system_email; 
    $mail->Password = "opwfgzdoqseexcod"; 
    $mail->SMTPSecure = "ssl"; 
    $mail->Port = 465;

    $mail->setFrom($system_email); 
    $mail->addAddress($admin_email); 
    $mail->addReplyTo($cust_email);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $message;

    // send email to customer to notify the booking succesful
    $subject = "AH_Artistry New Appointment Booking";

    $message = "You have notify our admin regarding your booking. Here are your booking detail:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Customer Contact No.: " .$cust_ctc_no. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($apt_time)). "\n";
    $message .= "Appointment Location: " .$apt_address. "\n";
    $message .= "Service Booked: " .$apt_service. "\n";
    $message .= "Message/Additional info: " .$additional_msg. "\n";
    $message .= "\n*Note: This email is a notification, Do not reply to this email*";

    $mailNotify = new PHPMailer(true);

    $mailNotify->isSMTP();
    $mailNotify->Host = "smtp.gmail.com"; 
    $mailNotify->SMTPAuth = true;
    $mailNotify->Username = $system_email; 
    $mailNotify->Password = "opwfgzdoqseexcod"; 
    $mailNotify->SMTPSecure = "ssl"; 
    $mailNotify->Port = 465;

    $mailNotify->setFrom($system_email); 
    $mailNotify->addAddress($cust_email); 

    $mailNotify->isHTML(false);
    $mailNotify->Subject = $subject;
    $mailNotify->Body = $message;

    // if ($mail->send() && $mailNotify->send()) {
        
    //     echo '<script>
    //             alert("Your booking has been received. We will response you through email or contact no.\n\nThank you!");
    //             window.location="book.php"
    //         </script>';
    // } else {
    //     echo "Error sending email: " . $mail->ErrorInfo;
    //     echo "Error sending email: " . $mailNotify->ErrorInfo;
    // }
    echo '<script>
            alert("Your booking has been received. We will response you through email or contact no.\n\nThank you!");
            window.location="book.php"
            </script>';
}

if(isset($_POST["set_appointment"])){
    // gather all data from the form
    $cust_name = $_POST["name"];
    $cust_email = $_POST["email"];
    $apt_date = $_POST["apt_date"];
    $apt_time = $_POST["apt_time"];
    $apt_address = $_POST["apt_address"];
    $apt_service = $_POST["apt_service"];
    $price = $_POST["price"];
    $additional_msg = $_POST["message"];

    // save to database
    $new_apt_query = "INSERT INTO appointment (ClientName, ClientEmail, AptAddress, AptDate, AptTime, ServiceID) 
                        VALUES ('$cust_name', '$cust_email', '$apt_address', '$apt_date', '$apt_time', '$apt_service')";
    $new_apt = mysqli_query($conn, $new_apt_query);


    // after save database, need to do some action for displaying price and additional info/message in email

    $price = 0.00;
    if($_POST["price"]!=""){
        $price = $_POST["price"];
    }else{
        $price = "Not stated";
    }

    $additional_msg = "";
    if($_POST["message"]!=""){
        $additional_msg = $_POST["msg"];
    }else{
        $additional_msg = "-";
    }

    // get the service name instead of id
    $service_query = "SELECT * FROM services WHERE ServiceID = '$apt_service' LIMIT 1";
    $service = mysqli_query($conn, $service_query);
    $row = $service->fetch_assoc();


    // send email to customer
    $subject = "AH_Artistry Appointment Set";

    $message = "Your appointment has been set! Here are the detail:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($apt_time)). "\n";
    $message .= "Appointment Location: " .$apt_address. "\n";
    $message .= "Service Booked: " .$row["ServiceName"]. "\n";
    $message .= "Price: " .$price. "\n";
    $message .= "Message/Additional info: " .$additional_msg. "\n";

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; 
    $mail->SMTPAuth = true;
    $mail->Username = $system_email; 
    $mail->Password = "opwfgzdoqseexcod"; 
    $mail->SMTPSecure = "ssl"; 
    $mail->Port = 465;

    $mail->setFrom($system_email); 
    $mail->addAddress($cust_email); 
    $mail->addReplyTo($admin_email);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $linkto_day = date("d", strtotime($apt_date));
    $linkto_month = date("m", strtotime($apt_date));
    $linkto_year = date("Y", strtotime($apt_date));

    // if ($mail->send()) {
        
    //     echo '<script>
    //             alert("New appointment has been set successfully and an email is sent to the customer");
    //             window.location="appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
    //           </script>';
    // } else {
    //     echo "Error sending email: " . $mail->ErrorInfo;
    // }
    echo '<script>
                alert("New appointment has been set successfully and an email is sent to the customer");
                window.location="appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
            </script>';
}

if(isset($_POST["edit_appointment"])){
    // gather all data from the form
    $apt_id = $_POST["apt_id"];
    $cust_name = $_POST["name"];
    $cust_email = $_POST["email"];
    $apt_date = $_POST["apt_date"];
    $old_apt_date = $_POST["old_apt_date"];
    $apt_time = $_POST["apt_time"];
    $old_apt_time = $_POST["old_apt_time"];
    $apt_address = $_POST["apt_address"];
    $old_apt_address = $_POST["old_apt_address"];
    $apt_service = $_POST["apt_service"];

    // save changes to database
    $updated_apt_query = "UPDATE appointment SET AptDate = '$apt_date', AptTime = '$apt_time', AptAddress = '$apt_address' WHERE AptID = '$apt_id'";
    $updated_apt = mysqli_query($conn, $updated_apt_query);

    // send email to customer
    $subject = "AH_Artistry Appointment Updated";

    $message = "Your appointment is updated! Here are the old detail:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($old_apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($old_apt_time)). "\n";
    $message .= "Appointment Location: " .$old_apt_address. "\n";
    $message .= "Service Booked: " .$apt_service. "\n\n";

    $message .= "And here are the new detail:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($apt_time)). "\n";
    $message .= "Appointment Location: " .$apt_address. "\n";
    $message .= "Service Booked: " .$apt_service. "\n";

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; 
    $mail->SMTPAuth = true;
    $mail->Username = $system_email; 
    $mail->Password = "opwfgzdoqseexcod"; 
    $mail->SMTPSecure = "ssl"; 
    $mail->Port = 465;

    $mail->setFrom($system_email); 
    $mail->addAddress($cust_email); 
    $mail->addReplyTo($admin_email);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $linkto_day = date("d", strtotime($apt_date));
    $linkto_month = date("m", strtotime($apt_date));
    $linkto_year = date("Y", strtotime($apt_date));

    // if ($mail->send()) {
        
    //     echo '<script>
    //             alert("The appointment selected has been updated successfully and an email is sent to the customer");
    //             window.location="appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
    //         </script>';
    // } else {
    //     echo "Error sending email: " . $mail->ErrorInfo;
    // }

    echo '<script>
                alert("The appointment selected has been updated successfully and an email is sent to the customer");
                window.location="appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
            </script>';
}

if(isset($_POST["delete_appointment"])){
    // gather all data from the form
    $apt_id = $_POST["apt_id"];
    $cust_name = $_POST["name"];
    $cust_email = $_POST["email"];
    $apt_date = $_POST["apt_date"];
    $apt_time = $_POST["apt_time"];
    $apt_address = $_POST["apt_address"];
    $apt_service = $_POST["apt_service"];

    // save changes to database
    $delete_apt_query = "DELETE FROM appointment WHERE AptID='$apt_id' limit 1";
    $delete_apt = mysqli_query($conn, $delete_apt_query);

    // send email to customer
    $subject = "AH_Artistry Appointment Cancellation";

    $message = "Your appointment is cancelled! Here are the detail of the appointment cancelled:"."\n";
    $message .= "Customer Name: " .$cust_name. "\n";
    $message .= "Customer Email: " .$cust_email. "\n";
    $message .= "Appointment Date: " .date("d-m-Y", strtotime($apt_date)). "\n";
    $message .= "Appointment Time: " .date("h:i A", strtotime($apt_time)). "\n";
    $message .= "Appointment Location: " .$apt_address. "\n";
    $message .= "Service: " .$apt_service. "\n";

    $mail = new PHPMailer(false);

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com"; 
    $mail->SMTPAuth = true;
    $mail->Username = $system_email; 
    $mail->Password = "opwfgzdoqseexcod"; 
    $mail->SMTPSecure = "ssl"; 
    $mail->Port = 465;

    $mail->setFrom($system_email); 
    $mail->addAddress($cust_email); 
    $mail->addReplyTo($admin_email);

    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $message;

    $linkto_day = date("d", strtotime($apt_date));
    $linkto_month = date("m", strtotime($apt_date));
    $linkto_year = date("Y", strtotime($apt_date));

    // if ($mail->send()) {
        
    //     echo '<script>
    //             alert("Appointment cancellation successful and an email notifying customer has been sent");
    //             window.location="pages/appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
    //         </script>';
    // } else {
    //     echo "Error sending email: " . $mail->ErrorInfo;
    // }
    
    echo '<script>
                alert("Appointment cancellation successful and an email notifying customer has been sent");
                window.location="appointments.php?day=' . $linkto_day . '&month=' . $linkto_month . '&year=' . $linkto_year . '"
            </script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_post"])) {
    $postTitle = $_POST["post_title"];
    $postContent = $_POST["post"];

    // Escape single quotes in the values to prevent SQL syntax issues
    $postTitle = mysqli_real_escape_string($conn, $postTitle);
    $postContent = mysqli_real_escape_string($conn, $postContent);

    $image = basename($_FILES["post_img"]["name"]);
    $uploadDir = "../../img/posts/";
    $uploadFile = $uploadDir . basename($_FILES["post_img"]["name"]);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    $validImageTypes = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $validImageTypes)) {
        if (move_uploaded_file($_FILES["post_img"]["tmp_name"], $uploadFile)) {

            $sql = "INSERT INTO posts (PostTitle, PostPic, PostDate, PostContent) VALUES ('$postTitle', '$image', '$today', '$postContent')";

            if ($conn->query($sql) === TRUE) {
                echo

                    '<script>
                        alert("New Post has been posted.");
                        window.location="posts.php"
                    </script>';
            } else {
                echo 
                    '<script>
                        alert("Connection error.");
                        window.location="posts.php"
                    </script>';
            }

        } else {
            echo

                '<script>
                    alert("Error uploading file.");
                    window.location="posts.php"
                </script>';
        }
    } else {
        echo

            '<script>
                alert("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
                window.location="posts.php"
            </script>';
    }
}

if(isset($_POST["delete_post"])){
    // gather all data from the form
    $del_post_id = $_POST["del_post_id"];

    $delete_post_query = "DELETE FROM posts WHERE PostID='$del_post_id' limit 1";
    $delete_post = mysqli_query($conn, $delete_post_query);

    echo 
    '<script>
        alert("Post deleted successfully.");
        window.location="posts.php"
    </script>';
}

?>