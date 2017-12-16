<div class="hero">
	<div class="hero-title">
		<h1> <?=$wltitle?> </h1>
	</div>
	<div class="cta cta-create">
		<button class="cta-filled" data-toggle="modal" data-target="#wishModal">Create wish</button>
	</div>
	<div class="cta cta-create">
		<button class="cta-outlined" data-toggle="modal" data-target="#wishlistsure">Delete wish list</button>
	</div>
</div>
<div class="wish">
	<div class="wish-header">
		<h2> Wishes </h2>
		<div class="addthis_inline_share_toolbox" id="toolbox"></div>
	</div>
	<div class="container">
		<div class="row justify-content-around">
			<?php $i = 0; foreach ($wishes as $wish) { ?>
			<div class="col-xs">
				<div class="card" onclick="showWishUpdateModal(wishes[<?= $i ?>])">
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
							<p class="wish-title"><?=$wish["product"]?> </p>
						</div>
						<div class="container-title">
							<p class="price"><?=$wish["price"]?></p>
						</div>
					</div>
				</div>
			</div>
			<?php $i++; } ?>
			<div class="col-xs">
				<div class="card" data-toggle="modal" data-target="#wishModal">
					<div class="image" style="background-image: url('assets/purpleplus.svg'); background-repeat: no-repeat;	background-size: cover;	background-position: center;">
					</div>
					<div class="container">
						<h4>Add wish...</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>