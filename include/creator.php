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
<!-- Are you sure - wishlist -->
<div class="modal fade" id="wishlistsure" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Are you sure you want to delete the list?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="index.php" method="post">
					<div style="display: none;">
						<input type="text" name="id" value="<?=$wlid?>">
					</div>
					<div class="cta cta-create">
						<button class="btn cta-btn-yellow" type="submit" name="cmd_list" value="delete_list">Delete wish list</button>
						<button class="btn cta-delete-wish" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Update wish Modal -->
<div class="modal fade" id="wishModalupdate" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update wish</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="wishlist.php?<?=$_SERVER['QUERY_STRING']?>" method="post" enctype="multipart/form-data">
					<input type="hidden" name="id" id="wishUpdateId"/>
					<div class="images">
						<div class="uploaders" id="wishUpdateUploader" onclick="$('#wishUpdatePhoto').click()">
							<img alt="Uplad image" src="assets/image.svg"/>
						</div>
						<input type="file" name="updateimage" class="hidephoto" id="wishUpdatePhoto" />
					</div>
					<div class="forms">
						<div class="form-group">
							<label>Brand</label>
							<input type="text" name="brand" class="form-control" id="wishUpdateBrand" required>
						</div>
						<div class="form-group">
							<label>Product</label>
							<input type="text" name="product" class="form-control" id="wishUpdateProduct" required>
						</div>
						<div class="form-group">
							<label>Comments</label>
							<textarea class="form-control" name="comment" rows="3" id="wishUpdateComment"></textarea>
						</div>
						<div class="form-group">
							<label>Price</label>
							<input type="text" name="price" class="form-control" id="wishUpdatePrice" required>
						</div>
						<div>
							<button type="submit" name="cmd_wish_update" value="create_wish_update" class="btn cta-update-wish">Update</button>
						</div>
						<div>
							<button type="submit" name="cmd_wish" value="delete_wish" class="btn cta-delete-wish">Delete wish</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	var imageLoaderUpdate = document.getElementById('wishUpdatePhoto');
	imageLoaderUpdate.addEventListener('change', handleImageUpdate('#wishUpdateUploader img'), false);
	function handleImageUpdate(selector) {
return function(e) {
var reader = new FileReader();
reader.onload = function (event) {
console.log(event);
$(selector).attr('src',event.target.result);
}
reader.readAsDataURL(e.target.files[0]);
}
}
function showWishUpdateModal(wish) {
	$('#wishUpdateId').val(wish.id);
	$('#wishUpdateUploader img').attr('src', 'uploads/'+wish.image);
	$('#wishUpdateBrand').val(wish.brand);
	$('#wishUpdateProduct').val(wish.product);
	$('#wishUpdateComment').val(wish.comment);
	$('#wishUpdatePrice').val(wish.price);
	$('#wishModalupdate').modal('show');
}
</script>
<div class="wish">
<div class="wish-header">
	<h2> Wishes </h2>
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