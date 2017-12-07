<html>
    <body>
	robot_name: <?php echo $_GET["robot_name"]; ?><br>
	timeout: <?php echo $_GET["timeout"]; ?><br>

	<?php
		$servername = "localhost";
		$username = "webuser";
		$password = "i_am_a_web_user";

		$conn = new mysqli($servername, $username, $password);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$select_query =
			"SELECT robot_name, start_time, end_time " .
			"FROM ROBOTS.ROBOT_SESSIONS " .
			"WHERE " .
			"robot_name='" . $_GET["robot_name"] . "' AND " .
			"end_time > '" . (time() - $_GET["timeout"]) . "' AND " .
			"end_time < '" . (time() + $_GET["timeout"]) . "'"
			;

		$result = $conn->query($select_query);

		if ($result->num_rows > 0) {
			// output data of each row
			#die("GOT TO UPDATE");
			while($row = $result->fetch_assoc()) {
				$start_time = $row["start_time"];
				$end_time = $row["end_time"];
			}
			$update_query =
				"UPDATE ROBOTS.ROBOT_SESSIONS SET " .
				"end_time='" . ( $end_time + $_GET["timeout"] ) . "'" .
				"WHERE " .
				"start_time='" . $start_time . "' AND" .
				"robot_name='" . $_GET["robot_name"] . "'";
			$result = $conn->query($update_query);
		} else {
			#die("GOT TO INSERT");
			$insert_query =
				"INSERT INTO ROBOTS.ROBOT_SESSIONS" .
				"(robot_name, start_time, end_time) " .
				"VALUES(" .
				"'" . $_GET["robot_name"] . "', " . 
				"'" . time() . "', " . 
				"'" . (time() + $_GET["timeout"]) . "'" .
				")";
			$conn->query($insert_query);
		}

		$conn->close();

		/*

		
			if ($conn->query($insert_query) === TRUE) {
			} else {
				echo "Error: " . $conn->error . "<br>";
			}

		*/
	?>

    </body>
</html>
