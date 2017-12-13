<?php
	// starts a session
	session_start();

	// Resets the session's variables 
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}
	// Ends the session
	session_destroy();
	header("Location: index.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta charset="utf-8">
	</head>
	<body style="margin: 50px auto; text-align: center;">

	</body>
</html>