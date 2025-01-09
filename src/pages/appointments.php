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
// function to show appointments by calendar
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
    $todayYmd = date("Y-m-d");

    $prev_month_m = date("m", mktime(0, 0, 0, $month-1, 1, $year));
    $prev_month_Y = date("Y", mktime(0, 0, 0, $month-1, 1, $year));

    $next_month_m = date("m", mktime(0, 0, 0, $month+1, 1, $year));
    $next_month_Y = date("Y", mktime(0, 0, 0, $month+1, 1, $year));

    // Creating the HTML table
    $calendar = "<table class='admin-table'>";
    $calendar .= "<center><h2>$monthName $year</h2>";

    $calendar .= "<br>";

    $calendar .= "<a class='calendar-btn admin-element' href='?month=".$prev_month_m."&year=".$prev_month_Y."'>< Prev Month</a>";
    $calendar .= "<a class='calendar-btn admin-element' href='?month=".date("m")."&year=".date("Y")."'>Current Month</a>";
    $calendar .= "<a class='calendar-btn admin-element' href='?month=".$next_month_m."&year=".$next_month_Y."'>Next Month ></a>";
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

        $selected_dateYmd = $todayYmd;

        if(isset($_GET["day"])){
            $selected_day = $_GET["day"];
            
            $selected_dateYmd = "$year-$month-$selected_day";
        }

        if($dateYmd == $selected_dateYmd){ // give class td-selected if the date is selected
            $calendar .= "<td class='td-admin td-selected' onclick='window.location.href=\"?day=$currentDay&month=$month&year=$year\"'><h3>$currentDay</h3>";
        }else{ // if not selected, no td-selected class
            $calendar .= "<td class='td-admin' onclick='window.location.href=\"?day=$currentDay&month=$month&year=$year\"'><h3>$currentDay</h3>";
        }

        $reserved = "SELECT * FROM appointment WHERE AptDate = '$dateYmd' ORDER BY AptTime ASC";
        $reserved_result = mysqli_query($conn, $reserved) or die("Error connecting to the database");

        while($row = $reserved_result->fetch_assoc()){
            $timeReserved = $row["AptTime"];
            $formattedTimeReserved = date("h:i A", strtotime($timeReserved));
            $calendar .= "<p style='color:black;'>$formattedTimeReserved</p>";
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
    <title>Appointments | AH Artistry</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <header>
        <nav class="navbar admin-element">
            <a href="appointments.php"><img class="logo-admin" src="../../img/newlogo.png" alt="AH Artistry"></a>
            <ul>
                <li><a class="active" href="appointments.php">APPOINTMENTS</a></li>
                <li><a href="posts.php" class="admin-element">POSTS</a></li>
                <li><a href="profile.php" class="admin-element"><i class="fa-solid fa-user profile-icon"></i>Aiman Hakim</a></li>
                <li><a href="../logout.php" class="logout">LOGOUT</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="appointments-section">
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
            <div class="appointments-schedule">
                <?php

                $appointment_day = date("d");
                $appointment_date = $today;

                if(isset($_GET["day"]) && isset($_GET["month"]) && isset($_GET["year"])){ // if yes selected date
                    // get the selected date
                    $appointment_day = $_GET["day"];
                    $appointment_month = $_GET["month"];
                    $appointment_year = $_GET["year"];

                    $appointment_date = "$appointment_year-$appointment_month-$appointment_day";

                    $appointment = "SELECT appointment.*, services.ServiceName
                    FROM appointment JOIN services ON appointment.ServiceID = services.ServiceID
                    WHERE appointment.AptDate = '$appointment_date'
                    ORDER BY appointment.AptTime ASC";
                    $appointments_result = mysqli_query($conn, $appointment) or die("Error connecting to the database");
                }else{ // if no then display today's appointment
                    $appointment = "SELECT appointment.*, services.ServiceName
                    FROM appointment JOIN services ON appointment.ServiceID = services.ServiceID
                    WHERE appointment.AptDate = '$today'
                    ORDER BY appointment.AptTime ASC";
                    $appointments_result = mysqli_query($conn, $appointment) or die("Error connecting to the database");
                }

                // Getting the name of this month
                $monthName = $dateComponents["month"];
                ?>
                <h2>Appointments on <?php echo "$appointment_day $monthName $year";
                    if($appointment_date == $today){
                        echo " (Today)";
                    }

                    if($appointment_date == $tomorrow){
                        echo " (Tomorrow)";
                    }
                ?></h2>
                <div class="appointments-list">
                    <?php if(mysqli_num_rows($appointments_result) > 0): ?>
                        <?php while($row = $appointments_result->fetch_assoc()): ?>
                    <div class="appointment-container admin-element">
                        <div class="appointment-who admin-element">
                            <h2><?php echo $row["ClientName"]; ?></h2>
                            <p><?php echo $row["ClientEmail"]; ?></p>
                        </div>
                        <div class="appointment-when admin-element">
                            <p>Service: <?php echo $row["ServiceName"]; ?></p>
                            <p>Date & Time: <?php echo date("d-m-Y", strtotime($row["AptDate"])); ?>, <?php echo date("h:i A", strtotime($row["AptTime"])); ?></p>
                            <p>Location: <?php echo $row["AptAddress"]; ?></p>
                        </div>
                        <div action="" method="post" class="schedule-button-box admin-element">
                            <button class="edit" onclick="showEditForm(<?php echo $row['AptID']; ?>)">EDIT APPOINTMENT</button>
                            <button class="delete" onclick="showDelForm(<?php echo $row['AptID']; ?>)">CANCEL APPOINTMENT</button>
                        </div>
                    </div>
                    <div class="background-popup" title="cancel and close form" id="background_popup" onclick="closePopup()"></div>
                    <form action="" method="post" class="booking-form admin-element popup-form edit-apt-form" id="edit_apt_form<?php echo $row['AptID']; ?>">
                        <button class="close-btn" title="cancel and close form" onclick="closePopup()">X</button>
                        <h3>Edit Appointment Form</h3>

                        <input type="hidden" name="apt_id" value="<?php echo $row["AptID"]; ?>">

                        <label for="Cust_Name"><b>Customer Name</b></label>
                        <input type="text" placeholder="<?php echo $row["ClientName"]; ?>" disabled>
                        <input type="hidden" name="name" value="<?php echo $row["ClientName"]; ?>">
                
                        <label for="Cust_Email"><b>Customer Email</b></label>
                        <input type="text" placeholder="<?php echo $row["ClientEmail"]; ?>" disabled>
                        <input type="hidden" name="email" value="<?php echo $row["ClientEmail"]; ?>">
                        
                        <div class="datetime-input-section">
                            <div class="left">
                                <label for="date"><b>Appointment Date</b></label>
                                <input type="date" name="apt_date" title="select a date" value="<?php echo $row["AptDate"];?>" required>
                                <input type="hidden" name="old_apt_date" value="<?php echo $row["AptDate"];?>">
                            </div>
                            
                            <div class="right">
                                <label for="time"><b>Appointment Time</b></label>
                                <input type="time" list="timelist" title="select a time" name="apt_time" value="<?php echo $row["AptTime"]; ?>" required>
                                <input type="hidden" name="old_apt_time" value="<?php echo $row["AptTime"];?>">
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
                        <textarea name="apt_address" rows="3" cols="50" placeholder="Enter make-up appointment address" required><?php echo $row["AptAddress"]; ?></textarea>
                        <input type="hidden" name="old_apt_address" value="<?php echo $row["AptAddress"];?>">

                        <label for="apt_service"><b>Appointment Service</b></label>
                        <select disabled>
                            <?php
                                $services_query = "SELECT * FROM services ORDER BY serviceID ASC";
                                $services = mysqli_query($conn, $services_query);
                            ?>
                            <option value="" disabled>Select service</option>
                            <?php while($row_service = $services->fetch_assoc()): ?>
                            <option <?php if($row["ServiceName"]==$row_service["ServiceName"]){ echo "selected" ;}else{ echo ""; } ?> value="<?php echo $row_service["ServiceName"]; ?>"><?php echo $row_service["ServiceName"]; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <input type="hidden" name="apt_service" value="<?php echo $row["ServiceName"]; ?>">
                        
                        <button type="submit" class="set-appointment-btn" name="edit_appointment">EDIT APPOINTMENT</button>
                    </form>
                    <form action="" method="post" class="booking-form admin-element popup-form delete-apt-form" id="delete_apt_form<?php echo $row['AptID']; ?>">
                        <h3>Cancel and Delete Appointment</h3>

                        <input type="hidden" name="apt_id" value="<?php echo $row["AptID"]; ?>">

                        <h4>Confirm cancellation of the following appointment?:</h4><br>
                        <div class="delete-info">
                            <label><b>Customer Name: </b></label>
                            <label><?php echo $row["ClientName"]; ?></label>
                            <input type="hidden" name="name" value="<?php echo $row["ClientName"]; ?>">
                            <br>
                        </div>
                        <div class="delete-info">
                            <label><b>Customer Email: </b></label>
                            <label><?php echo $row["ClientEmail"]; ?></label>
                            <input type="hidden" name="email" value="<?php echo $row["ClientEmail"]; ?>">
                            <br>
                        </div>
                        <div class="delete-info">
                            <label><b>Appointment Date: </b></label>
                            <label><?php echo date("d-m-Y", strtotime($row["AptDate"])); ?></label>
                            <input type="hidden" name="apt_date" value="<?php echo $row["AptDate"]; ?>">
                            <br>
                        </div>
                        <div class="delete-info">
                            <label><b>Appointment Time: </b></label>
                            <label><?php echo date("h:i A", strtotime($row["AptTime"])) ?></label>
                            <input type="hidden" name="apt_time" value="<?php echo $row["AptTime"]; ?>">
                            <br>
                        </div>
                        <div class="delete-info">
                            <label><b>Appointment Address: </b></label>
                            <label><?php echo $row["AptAddress"]; ?></label>
                            <input type="hidden" name="apt_address" value="<?php echo $row["AptAddress"]; ?>">
                            <br>
                        </div>
                        <div class="delete-info">
                            <label><b>Service: </b></label>
                            <label><?php echo $row["ServiceName"]; ?></label>
                            <input type="hidden" name="apt_service" value="<?php echo $row["ServiceName"]; ?>">
                            <br>
                        </div>
                            
                        <div style="display: flex;">
                            <button type="submit" class="set-appointment-btn" name="delete_appointment">YES</button>
                            <button onclick="closePopup()">NO</button>
                        </div>
                    </form>
                        <?php endwhile; ?>
                    <?php else: ?>
                    <p>The schedule is empty!</p>
                    <?php endif; ?>
                </div>
            </div>
            <button class="add_apt-btn" onclick="showAddAptForm()">+ ADD APPOINTMENT</button>
            <div class="background-popup" title="cancel and close" id="background_popup" onclick="closePopup()"></div>
            <form action="" method="post" class="booking-form popup-form admin-element" id="add_apt_form">
                <button class="close-btn" title="cancel and close" onclick="closePopup()">X</button>
                <h3>Add Appointment Form</h3>
                <label for="Cust_Name"><b>Customer Name</b></label>
                <input type="text" placeholder="Enter customer name" name="name" required>
        
                <label for="Cust_Email"><b>Customer Email</b></label>
                <input type="text" placeholder="Enter customer email" name="email" required>
                
                <div class="datetime-input-section">
                    <div class="left">
                        <label for="date"><b>Appointment Date</b></label>
                        <input type="date" name="apt_date" title="select a date" min="<?php echo $today; ?>" required>
                    </div>
                    
                    <div class="right">
                        <label for="time"><b>Appointment Time</b></label>
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
                <textarea name="apt_address" rows="3" cols="50" placeholder="Enter make-up appointment address" required></textarea>
    
                <label for="service"><b>Appointment Service</b></label>
                <select name="apt_service" required>
                    <?php
                        $services_query = "SELECT * FROM services ORDER BY ServiceID ASC";
                        $services = mysqli_query($conn, $services_query);
                    ?>
                    <option value="" disabled selected>Select service</option>
                    <?php while($row = $services->fetch_assoc()): ?>
                    <option value="<?php echo $row["ServiceID"]; ?>"><?php echo $row["ServiceName"]; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="price"><b>Price (RM)</b></label>
                <input type="number" step="0.01" placeholder="Enter Price in RM" name="price">
    
                <label for="message"><b>Message/Additional info</b></label>
                <textarea name="message" rows="4" cols="50" placeholder="Enter any additional details regarding your booking"></textarea>
                
                <button type="submit" class="set-appointment-btn" name="set_appointment">SET APPOINTMENT</button>
            </form>
        </div>
    </main>
    <footer class="admin-element">
        <div class="footer-content">
            <a href="appointments.php"><img src="../../img/footerlogo.png" alt="AH Artistry" class="logo-admin"></a>
        </div>
        <div class="footer-content">
            <ul>
                <li><a class="active" class="admin-element" href="appointments.php">APPOINTMENTS</a></li>
                <li><a href="posts.php" class="admin-element">POSTS</a></li>
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