<!-- Disclaimer Modal -->
<div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Disclaimer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            This is a school project.
         </div>
      </div>
   </div>
</div>
<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Create account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
               <div class="form-row">
                  <div class="form-group col-md-6">
                     <label>First name</label>
                     <input type="text" name="firstname" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                     <label>Last name</label>
                     <input type="text" name="lastname" class="form-control" required>
                  </div>
               </div>
               <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
               </div>
               <div class="modal-footer">
                  <button type="submit" name="submit" value="submit" class="btn cta-btn-yellow">Create account</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Log in</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
               <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
               </div>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
               </div>
               <div class="modal-footer">
                  <button type="submit" name="submitlog" value="submitlog" class="btn cta-btn-yellow">Log in</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Create wish list Modal -->
<div class="modal fade" id="wishlistModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
               <div class="images">
                  <div class="uploaders" id="uploader2" onclick="$('#filePhoto').click()">
                     <img src="assets/image.svg" alt="upload image"/>
                  </div>
                  <input type="file" name="listimage" class="hidephoto" id="filePhoto" />
               </div>
               <div class="forms">
                  <div class="form-group">
                     <h5 class="modal-title">Create wish list</h5>
                     <br/>
                     <label>Title</label>
                     <input type="text" name="listtitle" class="form-control" required>
                  </div>
                  <div>
                     <button type="submit" name="cmd_list" value="create_list" class="btn cta-btn-yellow">Create</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Create wish Modal -->
<div class="modal fade" id="wishModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Create wish</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form action="wishlist.php?<?=$_SERVER['QUERY_STRING']?>" method="post" enctype="multipart/form-data">
               <div class="images">
                  <div id="uploader" class="uploaders" onclick="$('#wishPhoto').click()">
                     <img src="assets/image.svg" alt="Upload image" />
                  </div>
                  <input type="file" name="wishimage" class="hidephoto" id="wishPhoto" />
               </div>
               <div class="forms">
                  <div class="form-group">
                     <label>Brand</label>
                     <input type="text" name="brand" class="form-control" required>
                  </div>
                  <div class="form-group">
                     <label>Product</label>
                     <input type="text" name="product" class="form-control" required>
                  </div>
                  <div class="form-group">
                     <label>Comments</label>
                     <textarea class="form-control" name="comment" rows="3"></textarea>
                  </div>
                  <div class="form-group">
                     <label>Price</label>
                     <input type="text" name="price" class="form-control" required>
                  </div>
                  <div>
                     <button type="submit" name="cmd_wish" value="create_wish" class="btn cta-btn-yellow">Create</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
