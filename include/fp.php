<!-- Hero logged in -->
<div class="hero">
	<div class="hero-title">
		<h1> Welcome back <?php echo $fn?>! </h1>
	</div>
	<div class="cta cta-create"> 
		<button class="cta-filled" data-toggle="modal" data-target="#wishlistModal">Create list</button>
	</div>	   
</div>

<!-- Wish list -->
<div class="list">
	<div class="list-header">
    	<h2> Your wish lists </h2>
    </div>
    <div class="container">
	 	<div class="row justify-content-around">
	 		<?php
			require_once('db_con.php');	
			$uid = $_SESSION['userID'];


			$sql = 'SELECT uwl.wishlists_wishlistID, wl.title, wl.image
                    FROM wishlists wl, users_wishlists uwl
                    WHERE uwl.wishlists_wishlistID = wl.wishlistID
                    AND uwl.users_userID = ?';
			$stmt = $con->prepare($sql);
			$stmt->bind_param('i', $uid);
			$stmt->execute();
			$stmt->bind_result($wlid, $title, $wlimage);

			while ($stmt->fetch()) { ?>
				<div class="col-xs">
					<a href="wishlist.php?wishlistid=<?=$wlid?>">
				  		<div class="card">
				  			<?php 
				  			if (!empty($wlimage)) { ?>
				  				<div class="image" style="background-image: url('uploads/<?=$wlimage?>'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
						  		</div>
				  			<?php } 
				  			else { ?>
							  	<div class="image" style="background-image: url('assets/list-<?=rand(1,2)?>.svg'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
							  	</div>
						  	<?php } ?>
						  	<div class="container">
						    	<h4><?=$title?></h4> 
						  	</div>
						</div>
					</a>
				</div>
		<?php } ?> 
			<div class="col-xs">
			  	<div class="card" data-toggle="modal" data-target="#wishlistModal">
				  	<div class="image" style="background-image: url('assets/purpleplus.svg'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
					</div>
				  	<div class="container">
				    	<h4>Add list...</h4>
				  	</div>
				</div>
		  	</div>
		</div>
	</div>
</div>
