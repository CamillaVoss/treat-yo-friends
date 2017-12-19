<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<script src="https://apis.google.com/js/platform.js" async defer></script>

<script>
    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
          console.log('User signed out.');
        });
      }

    var signInClicked = false;
    function onSignIn(googleUser) {
        if (signInClicked)
            return;
        signInClicked = true;
        var signedIn = <?= !empty($_SESSION['userID']) ? "true" : "false" ?>;
        if (!signedIn) {
            var id_token = googleUser.getAuthResponse().id_token;
            $(document).ready(function () {
                $("#id_token").val(id_token);
                $("#id_button").click();
            });
        }
    } 
</script>

<script>
    $(document).ready(function () {
        var imageLoader = document.getElementById('wishPhoto');
        imageLoader.addEventListener('change', handleImage('#uploader img'), false);

        var imageLoader2 = document.getElementById('filePhoto');
        imageLoader2.addEventListener('change', handleImage('#uploader2 img'), false);

        function handleImage(selector) {
            return function(e) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    console.log(event);
                    $(selector).attr('src',event.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);   
            }
        }
    });
</script>

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
    if (wish.image) {
        $('#wishUpdateUploader img').attr('src', 'uploads/'+wish.image);
    } else {
        $('#wishUpdateUploader img').attr('src', 'assets/image.svg');
    }
    $('#wishUpdateBrand').val(wish.brand);
    $('#wishUpdateProduct').val(wish.product);
    $('#wishUpdateComment').val(wish.comment);
    $('#wishUpdatePrice').val(wish.price);
    $('#wishUpdateLink').val(wish.link);
    $('#wishModalupdate').modal('show');
}

function showWishModal(wish) {
    $('#wishUpdateId').val(wish.id);
    if (wish.image) {
        $('#wishUpdateUploader img').attr('src', 'uploads/'+wish.image);
    } else {
        $('#wishUpdateUploader img').attr('src', 'assets/image.svg');
    }
    $('#wishUpdateBrand').val(wish.brand);
    $('#wishUpdateProduct').val(wish.product);
    $('#wishUpdateComment').val(wish.comment);
    $('#wishUpdatePrice').val(wish.price);
    $('#wishModalupdate').modal('show');
}
</script>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5a34f0b58a4cacbb"></script>
