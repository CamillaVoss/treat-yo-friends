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
		$sql = 'SELECT userID, pwhashed 
				FROM users 
				WHERE username = ?';
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

	// LOGIN WITH GOOGLE
	require_once 'google-api-php-client/vendor/autoload.php';
	if (filter_input(INPUT_POST, 'submitid')) {
		$id_token = filter_input(INPUT_POST, 'id_token');
		require_once('db_con.php');	
		$sql = 'SELECT userID 
				FROM users 
				WHERE id_token = ?';
		$stmt = $con->prepare($sql);
		$stmt->bind_param('s', $id_token);
		$stmt->execute();
		$stmt->bind_result($uid);
		while ($stmt->fetch()) {}

		if (!empty($uid)) {
			$_SESSION['userID'] = $uid;
			header("Location: index.php");
			die();
		} else {
			$em = filter_input(INPUT_POST,'email')
			or die('You must enter a valid email');

			$fn = filter_input(INPUT_POST,'firstname')
			or die('You must enter a valid name');

			$ln = filter_input(INPUT_POST,'lastname')
			or die('You must enter a valid name');

			$client = new Google_Client(['client_id' => '990532978279-c8pvu81b7jl7gil79n6nvctd4r86lfc4.apps.googleusercontent.com']);
			$payload = $client->verifyIdToken($id_token)
			or die('Invalid ID token');

			$sql = 'INSERT INTO users (username, firstname, lastname, id_token) VALUES (?, ?, ?, ?)';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('ssss', $em, $fn, $ln, $id_token);
			$stmt->execute();
			header("Location: index.php");
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
				$maxDim = 460;
		        $file_name = $_FILES['listimage']['tmp_name'];
		        list($width, $height, $type, $attr) = getimagesize( $file_name );
		        if ( $width > $maxDim || $height > $maxDim ) {
		            $ratio = $width/$height;
		            if( $ratio > 1) {
		                $new_width = $maxDim;
		                $new_height = $maxDim/$ratio;
		            } else {
		                $new_width = $maxDim*$ratio;
		                $new_height = $maxDim;
		            }
		            $src = imagecreatefromstring( file_get_contents( $file_name ) );
		            $dst = imagecreatetruecolor( $new_width, $new_height );
		            imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		            imagedestroy( $src );
		            imagepng( $dst, $file_name );
		            imagedestroy( $dst );
		        }
            	$target_dir = "uploads/";
            	$target_file = "wl{$wlid}.{$imageFileType}";
			    if (move_uploaded_file($file_name, "{$target_dir}{$target_file}")) {
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

            $sql = 'DELETE FROM savedlists 
                    WHERE savedlist_wishlistID = ?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $wlid);

            if (!$stmt->execute()) {
                $con->rollback();
                die("delete savedlists");
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
				if (!empty($images[$i]))
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

			$link = filter_input(INPUT_POST, 'link');

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
            $sql = 'INSERT INTO wishes(brand, product, price, href, comments, wishlists_wishlistID)
                    VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sssssi', $brand, $product, $price, $link, $comment, $wlid);
            $stmt->execute();
            $wid = $stmt->insert_id;
            $stmt->close();

			// if everything is ok, try to upload file
			if ($uploadOk) {
				$maxDim = 460;
		        $file_name = $_FILES['wishimage']['tmp_name'];
		        list($width, $height, $type, $attr) = getimagesize( $file_name );
		        if ( $width > $maxDim || $height > $maxDim ) {
		            $ratio = $width/$height;
		            if( $ratio > 1) {
		                $new_width = $maxDim;
		                $new_height = $maxDim/$ratio;
		            } else {
		                $new_width = $maxDim*$ratio;
		                $new_height = $maxDim;
		            }
		            $src = imagecreatefromstring( file_get_contents( $file_name ) );
		            $dst = imagecreatetruecolor( $new_width, $new_height );
		            imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		            imagedestroy( $src );
		            imagepng( $dst, $file_name );
		            imagedestroy( $dst );
		        }
            	$target_dir = "uploads/";
            	$target_file = "{$wid}.{$imageFileType}";
			    if (move_uploaded_file($file_name, "{$target_dir}{$target_file}")) {
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
            if(!empty($image)) {
            	unlink('uploads/'.$image);
            }
            




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

			$link = filter_input(INPUT_POST, 'link');

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
                    SET brand = ?, product = ?, price = ?, href = ?, comments = ?
                    WHERE wishID = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sssssi', $brand, $product, $price, $link, $comment, $wid);
            $stmt->execute();

            if ($uploadOk) {
            	$maxDim = 460;
		        $file_name = $_FILES['updateimage']['tmp_name'];
		        list($width, $height, $type, $attr) = getimagesize( $file_name );
		        if ( $width > $maxDim || $height > $maxDim ) {
		            $ratio = $width/$height;
		            if( $ratio > 1) {
		                $new_width = $maxDim;
		                $new_height = $maxDim/$ratio;
		            } else {
		                $new_width = $maxDim*$ratio;
		                $new_height = $maxDim;
		            }
		            $src = imagecreatefromstring( file_get_contents( $file_name ) );
		            $dst = imagecreatetruecolor( $new_width, $new_height );
		            imagecopyresampled( $dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		            imagedestroy( $src );
		            imagepng( $dst, $file_name );
		            imagedestroy( $dst );
		        }
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
				if (!empty($oldimage))
					unlink('uploads/'.$oldimage);

			    if (move_uploaded_file($file_name, "{$target_dir}{$target_file}")) {
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
	
	// SAVE LISTS
	if ($cmd = filter_input(INPUT_POST, 'cmd_star')) {
		$wlid = filter_input(INPUT_POST, 'id');
		$uid = $_SESSION['userID'];

		require_once('db_con.php');
        $sql = 'INSERT INTO savedlists(savedlist_wishlistID, savedlist_userID)
                VALUES (?, ?)';
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $wlid, $uid);
        $stmt->execute();
	}

	// REMOVE LISTS
	if ($cmd = filter_input(INPUT_POST, 'cmd_unstar')) {
		$wlid = filter_input(INPUT_POST, 'id');
		$uid = $_SESSION['userID'];

		require_once('db_con.php');
        $sql = 'DELETE FROM savedlists
                WHERE savedlist_userID = ?
                AND savedlist_wishlistID = ?';           
        $stmt = $con->prepare($sql);
        $stmt->bind_param('ii', $uid, $wlid);
        $stmt->execute(); 
	}	

?>