<nav class="navbar navbar-expand-sm navbar-light bg-white fixed-top">
  <a class="navbar-brand" href="index.php">
    <img src="assets/logo.svg" width="250" alt="logo">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <?php
        if (!empty($_SESSION['userID'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php" style="color: #484141;"> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php" onclick="signOut();"style="color: #484141;"><img src="assets/profile.svg" height="19" width="19" style="margin-bottom: 5px; margin-right: 5px;" alt="logo"> Log Out</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="" data-toggle="modal" data-target="#signupModal" style="color: #484141;"><img src="assets/plus.svg" width="20" height="20" alt="logo"> Sign Up</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="" data-toggle="modal" data-target="#loginModal" style="color: #484141;"><img src="assets/profile.svg" width="20" height="20" alt="logo"> Log In</a>
          </li>
        <?php } ?>
    </ul>
  </div>
</nav>
