<!-- Hero -->
<?php 	
	if ($exists) { ?>
		<div class="alert alert-warning" role="alert">
		  Woops - the email already exists in our database. <strong> Try to log in instead </strong>
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
	<?php } elseif (array_key_exists('create_succes', $_SESSION) && $_SESSION['create_succes']) { ?>
		<div class="alert alert-success" role="alert">
		  You have succesfully signed up! <strong>Log in now</strong>
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
	<?php } elseif ($login_failure) { ?>
		<div class="alert alert-danger" role="alert">
		  The <strong> email and password combination was wrong.</strong> Try again
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php } else { 
	} 

	$_SESSION['create_succes'] = false;

	?>

<div class="hero">
	<div class="hero-title">
		<h1> What is on your wish list? </h1>
	</div>
	<div class="container cta">	
		<div class="row justify-content-center signup">
		    <div class="col-auto" style="margin-bottom: 20px;">
		    	<button class="cta-filled" data-toggle="modal" data-target="#signupModal">Sign up now!</button>
		    </div>
		    <div class="col-auto">
		      	<p> or <p>
		    </div>
		    <div class="col-auto">
		    	<button class="cta-outlined" data-toggle="modal" data-target="#loginModal">Log in</button>
		    </div>
		</div>
	</div>
</div>

<!-- About -->
<div class="about">
	<div class="about-header">
    	<h2> Why should you use Treat Yo Friends? </h2>
    </div>
    <div class="container">
	 	<div class="row justify-content-around">
		  	<div class="col-xs">
			  	<div class="card">
				  	<img class="card-img-top" src="assets/store.svg" alt="store">
				  	<div class="container">
				    	<h4>Store wishes</h4> 
				    	<p>Store as many wishes as you want!</p> 
				  	</div>
				</div>
		  	</div>
		 <div class="col-xs">
	  		<div class="card">
			  	<img class="card-img-top" src="assets/share.svg" alt="share">
			  	<div class="container">
			    	<h4>Share with friends</h4> 
			    	<p>Share your wishes with all of your friends and family, to make sure tha they will treat yo with a perfect gift!</p> 
			  	</div>
			</div>
		 </div>
		 <div class="col-xs">
		 	<div class="card">
			  	<img class="card-img-top" src="assets/reserve.svg" alt="reserve">
			  	<div class="container">
			    	<h4>Reserve gifts</h4> 
			    	<p>Visiting someone elses wish list? Reserve the gift you want to treat a friend with, to make sure that no one else will buy the same gift!</p> 
			  	</div>
			</div>
		 </div>
		</div>
	</div>
</div>