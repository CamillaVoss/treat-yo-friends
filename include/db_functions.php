<?php
	session_start();
	$exists = false;
	$login_failure = false;

	// CREATE USER
	if (filter_input(INPUT_POST, 'submit')) {
		$em = filter_input(INPUT_POST,'email')
			or die('You must enter a valid email');

		$pw = filter_input(INPUT_POST,'password')
			or die('You must enter a valid password');

		$fn = filter_input(INPUT_POST,'firstname')
			or die('You must enter a valid first name');

		$ln = filter_input(INPUT_POST,'lastname')
			or die('You must enter a valid last name');

		require_once('db_con.php');
		$sql = 'SELECT userID FROM users WHERE username = ?';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('s', $em);
		$stmt->execute();
		$stmt->bind_result($id);
		while ($stmt->fetch()) {}

		if (!empty($id)) {
			$exists = true;
		} else {
			$pw = password_hash($pw, PASSWORD_DEFAULT);

			// Inserts username and hashed password in db table
			$sql = 'INSERT INTO users (username, pwhashed, firstname, lastname) VALUES (?, ?, ?, ?)';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('ssss', $em, $pw, $fn, $ln);
			$stmt->execute();

			$_SESSION['create_succes'] = true;
			header("Location: index.php");
			die();
		}
	}

	// LOGIN
	if (filter_input(INPUT_POST, 'submitlog')) {
		$em = filter_input(INPUT_POST,'email')
			or die('You must enter a valid username');

		$pw = filter_input(INPUT_POST,'password')
			or die('You must enter a valid password');

		require_once('db_con.php');	
		$sql = 'SELECT userID, pwhashed FROM users WHERE username = ?';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('s', $em);
		$stmt->execute();
		$stmt->bind_result($id, $pwhash);

		while ($stmt->fetch()) {
			
		}

		if (password_verify($pw, $pwhash)) {
			$_SESSION['userID'] = $id;
			header("Location: index.php");
			die();
		} else {
			$login_failure = true;
	 	}				
	}

	// CREATE WISH LIST
	if ($cmd = filter_input(INPUT_POST, 'cmd_list')) {
		if ($cmd == 'create_list') {

			$listimage = basename($_FILES["listimage"]["name"]);

			$uploadOk = false;
            if (!empty($listimage)) {
				$uploadOk = true;
				$tmp_name = $_FILES["listimage"]["tmp_name"];
				$name = $_FILES["listimage"]["name"];
				$imageFileType = pathinfo($name, PATHINFO_EXTENSION);
	            $check = getimagesize($tmp_name);
			    if($check !== false) {
			        $uploadOk = true;
			    } else {
			        $uploadOk = false;
			    }

			    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
					    $uploadOk = false;
				}
            }

            $listtitle = filter_input(INPUT_POST, 'listtitle')
				or die('You must give it a title');

			require_once('db_con.php');

            $con->autocommit(FALSE);
            $con->begin_transaction();

            $sql = 'INSERT INTO wishlists(title)
                    VALUES (?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('s', $listtitle);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $wlid = $con->insert_id;
            $uid = $_SESSION['userID'];

            $sql = 'INSERT INTO users_wishlists(users_userID, wishlists_wishlistID) 
                    VALUES (?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $uid, $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $con->commit();
            $con->autocommit(TRUE);
            $stmt->close();

			// if everything is ok, try to upload file
			if ($uploadOk) {
            	$target_dir = "uploads/";
            	$target_file = "wl{$wlid}.{$imageFileType}";
			    if (move_uploaded_file($tmp_name, "{$target_dir}{$target_file}")) {
					$sql = 'UPDATE wishlists
							SET image = ?
		                    WHERE wishlistID = ?';
		            $stmt = $con->prepare($sql);
		            $stmt->bind_param('si', $target_file, $wlid);
		            $stmt->execute();
		            $stmt->close();
			    } else {
			    	$uploadOk = false;
			    }
			}
			header("Location: wishlist.php?wishlistid=$wlid");


		// DELETE WISH LIST
		} elseif ($cmd == 'delete_list') {
			$wlid = filter_input(INPUT_POST,'id');

			require_once('db_con.php');

			$images = array();
            $sql = 'SELECT image
            		FROM wishes 
            		WHERE wishlists_wishlistID = ?';
			$stmtimg = $con->prepare($sql);
			$stmtimg->	bind_param('i', $wlid);
			$stmtimg->execute();
            $stmtimg->bind_result($image);
            while ($stmtimg->fetch()) {
            	array_push($images, $image);
			};
			$stmtimg->close();


            $con->autocommit(FALSE);
            $con->begin_transaction();

            $sql = 'SELECT image
            		FROM wishlists 
            		WHERE wishlistID = ?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die("select image");
            };

            $stmt->bind_result($image);
			while ($stmt->fetch()) {};


            $sql = 'DELETE FROM users_wishlists 
                    WHERE wishlists_wishlistID = ?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die('users_wishlists fail');
            };


            $sql = 'DELETE r.* FROM reservations r
					INNER JOIN wishes w
					ON r.wishes_wishID = w.wishID
					WHERE w.wishlists_wishlistID = ?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die("delete reservations");
            };


            $sql = 'DELETE FROM wishes 
                    WHERE wishlists_wishlistID = ?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die("delete wishes");
            };


            $sql = 'DELETE FROM wishlists 
            		WHERE wishlistID = ?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die("delete wishlist");
            };

            $con->commit();


			for ($i = 0; $i < count($images); $i++) {
            	unlink('uploads/'.$images[$i]);				
			};

			if (!empty($image)) {
            	unlink('uploads/'.$image);
			}
			header("Location: index.php");

		// last resort, if parameter is unkown
			} else {
			die('Unknown cmd parameter ' . $cmd);
		}
	}

	// CREATE AND DELETE WISH
	if ($cmd = filter_input(INPUT_POST, 'cmd_wish')) {

		if ($cmd == 'create_wish') {

			$brand = filter_input(INPUT_POST, 'brand')
				or die('brand');

			$product = filter_input(INPUT_POST, 'product')
				or die('product');

			$price = filter_input(INPUT_POST, 'price')
				or die('price');

			$comment = filter_input(INPUT_POST, 'comment');

			$image = basename($_FILES["wishimage"]["name"]);	

			$wlid = filter_input(INPUT_GET, 'wishlistid', FILTER_VALIDATE_INT)
                    or die('could not get wish list id');

			$uploadOk = false;
            if (!empty($image)) {
				$uploadOk = true;
				$tmp_name = $_FILES["wishimage"]["tmp_name"];
				$name = $_FILES["wishimage"]["name"];
				$imageFileType = pathinfo($name, PATHINFO_EXTENSION);
	            $check = getimagesize($tmp_name);
			    if($check !== false) {
			        $uploadOk = true;
			    } else {
			        $uploadOk = false;
			    }

			    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
					    $uploadOk = false;
				}
            }
    
			require_once('db_con.php');
            $sql = 'INSERT INTO wishes(brand, product, price, comments, wishlists_wishlistID)
                    VALUES (?, ?, ?, ?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssssi', $brand, $product, $price, $comment, $wlid);
            $stmt->execute();
            $wid = $stmt->insert_id;
            $stmt->close();

			// if everything is ok, try to upload file
			if ($uploadOk) {
            	$target_dir = "uploads/";
            	$target_file = "{$wid}.{$imageFileType}";
			    if (move_uploaded_file($tmp_name, "{$target_dir}{$target_file}")) {
					$sql = 'UPDATE wishes
							SET image = ?
		                    WHERE wishID = ?';
		            $stmt = $con->prepare($sql);
		            $stmt->bind_param('si', $target_file, $wid);
		            $stmt->execute();
		            $stmt->close();
			    } else {
			    	$uploadOk = false;
			    }
			}

            header("Location: {$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}");

            
        // Delete wish
		} elseif ($cmd == 'delete_wish') {
			$wid = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)
				or die('nope');

			require_once('db_con.php');
			$con->autocommit(FALSE);
            $con->begin_transaction();

            $sql = 'SELECT image
            		FROM wishes 
            		WHERE wishID = ?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $wid);

            if (!$stmt->execute()) {
                $con->rollback();
            };

            $stmt->bind_result($image);
			while ($stmt->fetch()) {};

            $sql = 'DELETE FROM reservations 
            		WHERE wishes_wishID = ?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $wid);

            if (!$stmt->execute()) {
                $con->rollback();
            };
            
			$sql = 'DELETE FROM wishes 
            WHERE wishID = ?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $wid);
			$stmt->execute();

            if (!$stmt->execute()) {
                $con->rollback();
            };

            $con->commit();
            unlink('uploads/'.$image);




		// last resort, if parameter is unkown
			} else {
			die('Unknown cmd parameter ' . $cmd);
		}
	}


	// UPDATE WISH
	if ($cmd = filter_input(INPUT_POST, 'cmd_wish_update')) {
		if ($cmd == 'create_wish_update') {
			$brand = filter_input(INPUT_POST, 'brand')
				or die('brand');

			$product = filter_input(INPUT_POST, 'product')
				or die('product');

			$price = filter_input(INPUT_POST, 'price')
				or die('price');

			$comment = filter_input(INPUT_POST, 'comment');

			$wid = filter_input(INPUT_POST, 'id')
                    or die('could not get wish list id');

            $image = basename($_FILES["updateimage"]["name"]);

        	$uploadOk = false;
            if (!empty($image)) {
				$uploadOk = true;
				$tmp_name = $_FILES["updateimage"]["tmp_name"];
				$name = $_FILES["updateimage"]["name"];
				$imageFileType = pathinfo($name, PATHINFO_EXTENSION);
	            $check = getimagesize($tmp_name);
			    if($check !== false) {
			        $uploadOk = true;
			    } else {
			        $uploadOk = false;
			    }

			    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
					    $uploadOk = false;
				}
            }

			require_once('db_con.php');
            $sql = 'UPDATE wishes
                    SET brand = ?, product = ?, price = ?, comments = ?
                    WHERE wishID = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssssi', $brand, $product, $price, $comment, $wid);
            $stmt->execute();

            if ($uploadOk) {
            	$target_dir = "uploads/";
            	$target_file = "{$wid}.{$imageFileType}";

	            $sql = 'SELECT image
	            		FROM wishes 
	            		WHERE wishID = ?';
				$stmt = $con->prepare($sql);
				$stmt->	bind_param('i', $wid);
				$stmt->execute();
				$stmt->bind_result($oldimage);
				while ($stmt->fetch()) {};
				unlink('uploads/'.$oldimage);

			    if (move_uploaded_file($tmp_name, "{$target_dir}{$target_file}")) {
					$sql = 'UPDATE wishes
							SET image = ?
		                    WHERE wishID = ?';
		            $stmt = $con->prepare($sql);
		            $stmt->bind_param('si', $target_file, $wid);
		            $stmt->execute();
		            $stmt->close();

			    } else {
			    	$uploadOk = false;
			    }

			}

            header("Location: {$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}");

		} else {
			die('Unknown cmd parameter ' . $cmd);
		}


	}

	// RESERVE WISH
	if ($cmd = filter_input(INPUT_POST, 'cmd_reserve')) {
		
		$wid = $cmd;
		$ruid = $_SESSION['userID'];

		require_once('db_con.php');
        $sql = 'INSERT INTO reservations(wishes_wishID, users_userID)
                VALUES (?, ?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $wid, $ruid);
        $stmt->execute();
	}

	// REMOVE RESERVATION
	if ($cmd = filter_input(INPUT_POST, 'cmd_unreserve')) {
		
		$wid = $cmd;
		$ruid = $_SESSION['userID'];

		require_once('db_con.php');
        $sql = 'DELETE FROM reservations
                WHERE users_userID = ?
                AND wishes_wishID = ?';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $ruid, $wid);
        $stmt->execute();
	}

?>