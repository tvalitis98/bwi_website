<div>
    <?php require_once __DIR__ . '/quickstart.php';

    $servername = "localhost";
    $username = "webuser";
    $password = "i_am_a_web_user";

    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $day_of_week = date('w');
    $back_days = abs(1 - date('w'));
    /*if(($day_of_week == 5 && date('H') >= 17) || $day_of_week > 5 || $day_of_week == 0) {
        echo "GO BACK TO MOST RECENT";
    }
    else {
        echo "GO BACK TWICE";
        $back_days += 7;
    }*/
    for($num_week = 0; $num_week < 2; $num_week++, $back_days += 7) {
	    // 86400 is number of seconds in a day
	    // H is hours 0-23, i is minutes 0-59, s is seconds 0-59
	    $seconds_difference = ($back_days * 86400) - ((9 - date('H')) * 3600) + (date('i') * 60) + date('s');
	    $start_date = new DateTime("-" . $seconds_difference . " seconds");
	    $start_sec = $start_date->getTimestamp();
	    // 374400 is 4 days and 8 hours worth of seconds
	    $end_sec = $start_sec + 374400;
	    $end_date = new DateTime("@" . $end_sec);
	    $end_date->setTimeZone($start_date->getTimeZone());

            echo "<h3>";
	    if($num_week == 0) {
		    if(($day_of_week == 5 && date('H') >= 17) || $day_of_week > 5) {
			echo "This week (" . $start_date->format('m/d') . ") the robots were up for ";
	    	    }
                    else if($day_of_week == 0 || ($day_of_week == 1 && date('H') < 9)) {
                        echo "Last week (" . $start_date->format('m/d') . ") the robots were up for ";
                    }
	    	    else {
			echo "This week (" . $start_date->format('m/d') . ") the robots have been up for ";
	    	    }
	    }
	    else {
		echo "Last week (" . $start_date->format('m/d') . ") the robots were up for ";
	    }

	    // echo $start_date->format("D, d M Y H:i:s O");
	    // echo $end_date->format("D, d M Y H:i:s O");

	    $select_query =
		"SELECT robot_name, start_time, end_time " .
		"FROM ROBOTS.ROBOT_SESSIONS " .
		"WHERE " .
		"start_time < '" . $end_sec . "' AND " .
		"end_time > '" . $start_sec .
		"' ORDER BY start_time;";

	    $result = $conn->query($select_query);

	    if ($result->num_rows > 0) {
		$favorable_minutes = 0;
		// loop through each day of the week monday-friday
		for($num_day = 0; $num_day < 5; $num_day++) {
		    // 480 minutes in 8 hours
		    $day_array = array_fill(0, 480, false);
		    while($row = $result->fetch_assoc()) {
		        $start_time = round(($row["start_time"] - $start_sec - ($num_day * 86400)) / 60);
		        $end_time = round(($row["end_time"] - $start_sec - ($num_day * 86400)) / 60);
		        for($min = $start_time; $min < 480 && $min < $end_time; $min++) {
		            $day_array[$min] = true;
		        }
		        // echo "<p>$start_time - $end_time</p>";
		    }
		    $result->data_seek(0);
		    for($x = 0; $x < 480; $x++) {
		        if($day_array[$x])
		            $favorable_minutes++;
		    }
		}
                echo "$favorable_minutes minutes, or ";
                if($num_week == 0 && (($day_of_week == 5 && date('H') < 17)) || ($day_of_week == 1 && date('H') >= 9) || ($day_of_week > 1 && $day_of_week < 5)) {
                    $effective_day_of_week = $day_of_week - 1;
                    $hour_diff = 0;
                    $min_diff = 0;
                    if(date('H') >= 17) {
			$effective_day_of_week++;
                    }
                    else if (date('H') > 9){
                        $hour_diff = date('H') - 9;
                        $min_diff = date('i');
                    }
                    $total_minutes = ($effective_day_of_week * 480) + ($hour_diff * 60) + $min_diff;
		    echo number_format(100 * ($favorable_minutes / $total_minutes), 2);
                }
                else {
                    echo number_format($favorable_minutes / 24.00, 2);
                }
	    }
            else {
		echo "0";
	    }
	    echo "% of the targetted time (Mon-Fri 9am-5pm).</h3>";
    }
    $conn->close();
    
    ?>
    
</div>
