<?php
session_start();
include("../../config/connection.php");
include("../server.php");
?>

<?php
// function to show appointments by using calendar
function build_calendar($conn, $month, $year){
    // create an array containing name of all days in a week
    $daysOfWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

    // get the first day of the month that is in the argument of this function
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

    // Getting the number of days this month contains
    $numberDays = date("t", $firstDayOfMonth);

    // Getting some info abt the first day of this month
    $dateComponents = getdate($firstDayOfMonth);

    // Getting the name of this month
    $monthName = $dateComponents["month"];

    // Getting the index value  0-6 of the first day of this month
    $dayOfWeek = $dateComponents["wday"];

    // Getting the current date
    $today = date("d-m-Y");

    $prev_month_m = date("m", mktime(0, 0, 0, $month-1, 1, $year));
    $prev_month_Y = date("Y", mktime(0, 0, 0, $month-1, 1, $year));

    $next_month_m = date("m", mktime(0, 0, 0, $month+1, 1, $year));
    $next_month_Y = date("Y", mktime(0, 0, 0, $month+1, 1, $year));

    // Creating the HTML table
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";

    $calendar .= "<br>";

    $calendar .= "<a class='calendar-btn' href='?month=".$prev_month_m."&year=".$prev_month_Y."'>< Prev Month</a>";
    $calendar .= "<a class='calendar-btn' href='?month=".date("m")."&year=".date("Y")."'>Current Month</a>";
    $calendar .= "<a class='calendar-btn' href='?month=".$next_month_m."&year=".$next_month_Y."'>Next Month ></a>";
    $calendar .= "</center>";

    $calendar .= "<tr class='header-row'>";

    // creating the calendar header
    foreach($daysOfWeek as $day){
        $calendar .= "<th class='table-header'>$day</th>";
    }

    $calendar .= "</tr><tr>";

    // variable $dayOfWeek will make sure that there must be only 7 columns on our table
    if($dayOfWeek > 0){
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar .= "<td class='td-other-month'></td>";
        }
    }

    // initiating the day counter
    $currentDay = 1;

    // Getting the month number
    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while($currentDay <= $numberDays){

        // if seventh column (saturday) reached, start a new one
        if($dayOfWeek == 7){
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $date = "$currentDay-$month-$year";

        $dateYmd = "$year-$month-$currentDay";

        $calendar .= "<td><h3>$currentDay</h3>";

        $reserved = "SELECT * FROM appointment WHERE AptDate = '$dateYmd' ORDER BY AptTime ASC";
        $reserved_result = mysqli_query($conn, $reserved) or die("Error connecting to the database");

        while($row = $reserved_result->fetch_assoc()){
            $timeReserved = $row["AptTime"];
            $formattedTimeReserved = date("h:i A", strtotime($timeReserved));
            $calendar .= "<p>$formattedTimeReserved Reserved</p>";
        }

        $calendar .= "</td>";

        // Incrementing the counters
        $currentDay++;
        $dayOfWeek++;
    }

    // completing the row of the last week in month, if necessary
    if($dayOfWeek != 7){
        $remainingDays = 7-$dayOfWeek;
        for($i=0;$i<$remainingDays;$i++){
            $calendar .= "<td class='td-other-month'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";

    echo $calendar; 
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
    <title>Book Now | AH Artistry</title>

    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="home.php"><img class="logo" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a class="active" href="book.php">BOOK NOW</a></li>
                <li><a href="about.php">ABOUT US</a></li>
                <li><a onclick="ToFooter()"  style="cursor: pointer;">CONTACT US</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="calendar-container">
            <div class="row">
                <div class="col-md-12">
                <?php
                    $dateComponents = getdate();
                    if(isset($_GET["month"]) && isset($_GET["year"])){
                        $month = $_GET["month"];
                        $year = $_GET["year"];
                    }else{
                        $month = $dateComponents["mon"];
                        $year = $dateComponents["year"];
                    }
                    echo build_calendar($conn, $month, $year);
                ?>
                </div>
            </div>
        </div>
        <button class="booking-btn" onclick="showBookingForm()">BOOK</button>
        <div class="background-popup" title="cancel and close" id="background_popup" onclick="closePopup()"></div>
        <form action="" method="post" class="booking-form popup-form" id="booking_form">
            <button class="close-btn" title="cancel and close" onclick="closePopup()">X</button>
            <h3>Booking Form</h3>
            <div>
                <label for="Name"><b>Name</b></label>
                <input type="text" placeholder="Enter your name" name="name" required>
        
                <label for="Email"><b>Email</b></label>
                <input type="text" placeholder="Enter your email" name="email" required>
        
                <label for="contact_no"><b>Contact No.</b></label>
                <input type="text" placeholder="Enter your contact no." name="ctc_no" required>
            </div>
            <div>
                <div class="datetime-input-section">
                    <div class="left">
                        <label for="date"><b>Date</b></label>
                        <input type="date" name="apt_date" title="select a date" min="<?php echo $tomorrow; ?>" required>
                    </div>
                    
                    <div class="right">
                        <label for="time"><b>Time</b></label>
                        <input type="time" list="timelist" title="select a time" name="apt_time" required>
                    </div>
                </div>
                <datalist id="timelist">
                    <option value="04:00:00">
                    <option value="04:30:00">
                    <option value="05:00:00">
                    <option value="05:30:00">
                    <option value="06:00:00">
                    <option value="06:30:00">
                    <option value="07:00:00">
                    <option value="07:30:00">
                    <option value="08:00:00">
                    <option value="08:30:00">
                    <option value="09:00:00">
                    <option value="09:30:00">
                    <option value="10:00:00">
                    <option value="10:30:00">
                    <option value="11:00:00">
                    <option value="11:30:00">
                    <option value="12:00:00">
                    <option value="12:30:00">
                    <option value="13:00:00">
                    <option value="13:30:00">
                    <option value="14:00:00">
                    <option value="14:30:00">
                    <option value="15:00:00">
                    <option value="15:30:00">
                    <option value="16:00:00">
                    <option value="16:30:00">
                    <option value="17:00:00">
                    <option value="17:30:00">
                    <option value="18:00:00">
                    <option value="18:30:00">
                    <option value="19:00:00">
                    <option value="19:30:00">
                    <option value="20:00:00">
                    <option value="20:30:00">
                    <option value="21:00:00">
                    <option value="21:30:00">
                    <option value="22:00:00">
                    <option value="22:30:00">
                    <option value="23:00:00">
                    <option value="23:30:00">
                </datalist>
        
                <label for="appointment_address"><b>Appointment Address</b></label>
                <textarea name="apt_address" rows="3" cols="50" placeholder="Enter make-up appointment address/location" required></textarea>

                <label for="service"><b>Select service</b></label>
                <select name="apt_service" required>
                    <?php
                        $services_query = "SELECT * FROM services ORDER BY serviceID ASC";
                        $services = mysqli_query($conn, $services_query);
                    ?>
                    <option value="" disabled selected>Select service - *Note that the price is not fixed</option>
                    <?php while($row = $services->fetch_assoc()): ?>
                    <option value="<?php echo $row["ServiceName"]; ?>"><?php echo $row["ServiceName"]; ?> - <?php echo $row["ServicePrice"]; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="message"><b>Message/Additional info</b></label>
                <textarea name="message" rows="4" cols="50" placeholder="Enter any additional details regarding your booking"></textarea>
            </div>
            <button type="submit" name="book_now">BOOK NOW</button>
        </form>
    </main>

    <footer id="footer">
        <div class="footer-content">
            <a href="home.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a href="home.php">HOME</a></li>
                <li><a class="active" href="book.php">BOOK NOW</a></li>
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