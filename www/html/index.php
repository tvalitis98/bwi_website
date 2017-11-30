<html lang="en">
  <head>
    <!-- <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> -->
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  <?php
	require 'head.php';
	require 'config.php';
	?>

  <h1>Welcome to the Building Wide Intelligence Project!</h1>

  <p>Insert information about the BWI lab here.</p>

	<?php
		$lab_login_button_a =
			"<button onClick=\"javascript:window.location.href='";
		$lab_login_button_b =
			"/login.php'\">Lab Login</button>";

		echo $lab_login_button_a . config('docroot') . $lab_login_button_b;
	?>

  <!--<button onClick="javascript:window.location.href='/login.php'">Lab Login</button>-->
  
  </body>
</html>
