<?php 
	include 'include/db_functions.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Treat Yo Friends</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/fav.png" type="image/x-icon">
		<link rel="icon" href="assets/fav.png" type="image/x-icon">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel=stylesheet href="styling/style.css">
		<script>
	    	window.dataLayer = window.dataLayer || [];
	    	function gtag(){dataLayer.push(arguments);}
	    	gtag('js', new Date());
			gtag('config', 'UA-110950639-1');
		</script>
	</head>
	<body>
		<?php include 'include/nav.php';?>
		<div class="clearfix"></div>
		<?php
			if (!empty($_SESSION['userID'])) {
				$id = $_SESSION['userID'];
				require_once('include/db_con.php');
				$sql = 'SELECT username, firstname, lastname FROM users WHERE userID = ?';
				$stmt = $con->prepare($sql);
				$stmt->bind_param('i', $id);
				$stmt->execute();
				$stmt->bind_result($un, $fn, $ln);
				while ($stmt->fetch()) {}

				include 'include/fp.php';
			} else {
				include 'include/welcome.php';
			}
		?>

		<?php include 'include/footer.php';?>
		<?php include 'include/modals.php';?>
		<?php include 'include/scripts.php';?>
	</body>
</html>