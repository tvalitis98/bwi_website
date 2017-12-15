<div>
    <?php
require_once __DIR__ . '/quickstart.php';

$servername = "localhost";
$username   = "webuser";
$password   = "i_am_a_web_user";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$day_of_week = date('w');
$back_days   = abs(1 - date('w'));
$hour        = date('H');
$minute      = date('i');
$second      = date('s');

// loop through the two weeks we want to report on
for ($num_week = 0; $num_week < 2; $num_week++, $back_days += 7) {
    // 86400 is number of seconds in a day
    // H is hours 0-23, i is minutes 0-59, s is seconds 0-59
    // find the difference in seconds between the last now and the last monday
    $seconds_difference = ($back_days * 86400) - ((9 - $hour) * 3600) + ($minute * 60) + $second;
    // use the difference in seconds to create a datetime (monday at 9am)
    $start_date         = new DateTime("-" . $seconds_difference . " seconds");
    $start_sec          = $start_date->getTimestamp();
    // 374400 is 4 days and 8 hours worth of seconds
    // use the first datetime to find the second (friday at 5pm)
    $end_sec            = $start_sec + 374400;
    $end_date           = new DateTime("@" . $end_sec);
    $end_date->setTimeZone($start_date->getTimeZone());
    
    // start printing the uptime report for this week
    echo "<h3>";
    // the most recent week
    if ($num_week == 0) {
        // if we are at the end of the week
        if (($day_of_week == 5 && $hour >= 17) || $day_of_week > 5) {
            echo "This week (" . $start_date->format('m/d') . ") the robots were up for ";
        }
        // if we are at the beginning of the week after
        else if ($day_of_week == 0 || ($day_of_week == 1 && $hour < 9)) {
            echo "Last week (" . $start_date->format('m/d') . ") the robots were up for ";
        }
        // if we are in the middle of the week
        else {
            echo "This week (" . $start_date->format('m/d') . ") the robots have been up for ";
        }
    }
    // the second most recent week with some extra week before the target time starts
    else if ($day_of_week == 0 || ($day_of_week == 1 && $hour < 9)) {
        echo "Two weeks ago (" . $start_date->format('m/d') . ") the robots were up for ";
    }
    // the second most recent week without the extra time before the target time starts
    else {
        echo "Last week (" . $start_date->format('m/d') . ") the robots were up for ";
    }
    
    // query for all uptime that could possibly apply for the current week
    $select_query = "SELECT robot_name, start_time, end_time " . 
                    "FROM ROBOTS.ROBOT_SESSIONS " . "WHERE " . 
                    "start_time < '" . $end_sec . "' AND " . 
                    "end_time > '" . $start_sec . 
                    "' ORDER BY start_time;";
    
    $result = $conn->query($select_query);
    
    if ($result->num_rows > 0) {
        $favorable_minutes = 0;
        // loop through each day of the week monday-friday
        for ($num_day = 0; $num_day < 5; $num_day++) {
            // 480 minutes in 8 hours
            // create an array to represent all the minutes in the target 8 hours
            $day_array = array_fill(0, 480, false);
            // loop through the query results 
            while ($row = $result->fetch_assoc()) {
                // adjuest start_time and end_time to be indexed into the array relative to the current day
                $start_time = round(($row["start_time"] - $start_sec - ($num_day * 86400)) / 60);
                $end_time   = round(($row["end_time"] - $start_sec - ($num_day * 86400)) / 60);
                for ($min = $start_time; $min < 480 && $min < $end_time; $min++) {
                    $day_array[$min] = true;
                }
            }
            $result->data_seek(0);
            // loop through the array we made in order to see what minutes we had robots active
            for ($x = 0; $x < 480; $x++) {
                if ($day_array[$x])
                    $favorable_minutes++;
            }
        }
        echo "$favorable_minutes minutes, or ";
        // mid week
        if ($num_week == 0 && (($day_of_week == 5 && $hour < 17)) ||
                                ($day_of_week == 1 && $hour >= 9) ||
                                ($day_of_week > 1 && $day_of_week < 5)) {
            // find the difference in hours and minutes between now and the start of today
            $hour_diff             = 0;
            $min_diff              = 0;
            // if past 5pm the difference is one target day
            if ($hour >= 17) {
                $hour_diff = 8;
            }
            // between 9am and 5pm
            else if ($hour > 9) {
                $hour_diff = $hour - 9;
                $min_diff  = $minute;
            }
            // calculate the total number of minutes the robots should have been up thus far
            $total_minutes = (($day_of_week - 1) * 480) + ($hour_diff * 60) + $min_diff;
            echo number_format(100 * ($favorable_minutes / $total_minutes), 2);
        }
        // not mid week (total minutes always 2400)
        else {
            echo number_format($favorable_minutes / 24.00, 2);
        }
    }
    // robots were never on!
    else {
        echo "0";
    }
    echo "% of the targetted time (Mon-Fri 9am-5pm).</h3>";
}
$conn->close();

?>
   
</div>
