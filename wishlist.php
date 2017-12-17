<?php
	include 'include/db_functions.php';
	// DISPLAY WISHES
	$wlid = filter_input(INPUT_GET, 'wishlistid', FILTER_VALIDATE_INT)
		or die('could not get wish list id');
		
	require_once('include/db_con.php');
	
	$sql2 = 'SELECT title
			 FROM wishlists
			 WHERE wishlistID = ?';
	$stmt2 = $con->prepare($sql2);
	$stmt2->bind_param('s', $wlid);
	$stmt2->execute();
	$stmt2->bind_result($wltitle);
	while ($stmt2->fetch()) {
		$wltitle = $wltitle;
	}
		
	$stmt2->close();

	$sql3 = 'SELECT users.userID, users.firstname
			 FROM users, users_wishlists
			 WHERE users.userID = users_wishlists.users_userID
			 AND users_wishlists.wishlists_wishlistID = ?';
	$stmt3 = $con->prepare($sql3);
	$stmt3->bind_param('i', $wlid);
	$stmt3->execute();
	$stmt3->bind_result($uid, $fn);
	while ($stmt3->fetch()) {
		$uid = $uid;
		$fn = $fn;
	}
		
	$stmt3->close();
	
	$sql = 'SELECT w.wishID, w.brand, w.product, w.price, w.href, w.comments, w.image
			FROM wishlists wl, wishes w
			WHERE w.wishlists_wishlistID = wl.wishlistID
			AND w.wishlists_wishlistID = ?
			ORDER BY w.wishID DESC';
	$stmt = $con->prepare($sql);
	$stmt->bind_param('i', $wlid);
	$stmt->execute();
	$stmt->bind_result($wid, $wbrand, $wproduct, $wprice, $wlink, $wcomment, $wimage);
	$wishes = array();
	$ids = array();
	while ($stmt->fetch()) {
		array_push($ids, $wid);
		array_push($wishes, array(
			"id" => $wid,
			"brand" => $wbrand,
			"product" => $wproduct,
			"price" => $wprice,
			"link" => $wlink,
			"comment" => $wcomment,
			"image" => $wimage,
		));
	}

	$jsonwishes = json_encode($wishes);

	$stmt->close();
	for ($i = 0; $i < count($wishes); $i++) {
		$wish = $wishes[$i];
		$sql4 = "SELECT reservations.users_userID, users.firstname
				 FROM reservations, users
				 WHERE reservations.users_userID = users.userID
				 AND wishes_wishID = ?";
		$stmt4 = $con->prepare($sql4);
		$stmt4->bind_param('i', $wish['id']);
		$stmt4->execute();
		$stmt4->bind_result($ruid, $rufn);
		$stmt4->fetch();
		$wishes[$i]['ruid'] = $ruid;
		$wishes[$i]['rufn'] = $rufn;
		$stmt4->close();
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Treat Yo Friends | Your digital wish list</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Store your wishes in the cloud | Share them with all of your friends | Reserve gifts from others wish list">
		<meta name="google-signin-client_id" content="990532978279-c8pvu81b7jl7gil79n6nvctd4r86lfc4.apps.googleusercontent.com">
		<link rel="shortcut icon" href="assets/fav.png" type="image/x-icon">
		<link rel="icon" href="assets/fav.png" type="image/x-icon">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel=stylesheet href="styling/style.css">
		<script>var wishes = <?= $jsonwishes ?>;</script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110950639-1"></script>
		<script>
	    	window.dataLayer = window.dataLayer || [];
	    	function gtag(){dataLayer.push(arguments);}
	    	gtag('js', new Date());
			gtag('config', 'UA-110950639-1');
		</script>
	</head>
	<body>
		<?php include 'include/scripts.php';?>
		<?php include 'include/nav.php';?>
		<div class="clearfix"></div>
		<?php 
			if (array_key_exists('userID', $_SESSION) && $_SESSION['userID'] == $uid) {
				include 'include/creator.php';
			} elseif (!empty($_SESSION['userID'])) {
				include 'include/visitor.php';
			} else {
				include 'include/stranger.php';
			}

		?>
		<?php include 'include/footer.php';?>
		<?php include 'include/modals.php';?>
		<?php include 'include/scripts.php';?>
	</body>
</html>