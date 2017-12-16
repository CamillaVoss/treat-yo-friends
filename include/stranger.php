<?php
	
?>
<div class="hero">
	<div class="hero-title">
		<h1> <?=$wltitle?> </h1>
	</div>
	<div class="cta cta-create">
		<h4>Owner: <?=$fn?></h4>
	</div>
</div>
<div class="wish">
	<div class="wish-header">
		<h2> Wishes </h2>
	</div>
	<div class="container">
		<div class="row justify-content-around">
			<?php foreach ($wishes as $wish) { ?>
			<div class="col-xs">
				<div class="card">
					<div data-toggle="modal" data-target="#wishModalupdate<?=$wish["id"]?>">
						<?php
						if (!empty($wish["image"])) { ?>
						<div class="image" style="background-image: url('uploads/<?=$wish["image"]?>'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
						</div>
						<?php }
						else { ?>
						<div class="image" style="background-image: url('assets/list-<?=rand(1,2)?>.svg'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
						</div>
						<?php } ?>
						<div class="container">
							<div>
								<h4><?=$wish["brand"]?></h4>
								<p class="wish-title"><?=$wish["product"]?></p>
							</div>
							<div class="container-title">
								<p class="price"><?=$wish["price"]?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- View wish Modal -->
			<div class="modal fade" id="wishModalupdate<?=$wish["id"]?>" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<form action="wishlist.php?<?=$_SERVER['QUERY_STRING']?>" method="post">
								<div style="display: none;">
									<input type="text" name="id" value="<?=$wish["id"]?>">
								</div>
								<div class="images">
									<div class="uploaders">
										<?php
										if (!empty($wish["image"])) { ?>
										<div class="image" style="background-image: url('uploads/<?=$wish["image"]?>'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
										</div>
										<?php }
										else { ?>
										<div class="image" style="background-image: url('assets/list-<?=rand(1,2)?>.svg'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="forms">
									<div class="form-group">
										<h4>Brand</h4>
										<p><?=$wish["brand"]?></p>
									</div>
									<div class="form-group">
										<h4>Product</h4>
										<p><?=$wish["product"]?></p>
									</div>
									<div class="form-group">
										<h4>Comments</h4>
										<p><?=$wish["comment"]?></p>
									</div>
									<div class="form-group">
										<h4>Link</h4>
										<a href="<?=$wish["link"]?>" target="blank"><?=$wish["link"]?></a>
									</div>
									<div class="form-group">
										<h4>Price</h4>
										<p><?=$wish["price"]?></p>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>