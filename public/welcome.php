<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";
?>

<?php include "templates/header.php"; ?>
<p>
	<a href="reset-password.php" class="btn">Reset Your Password</a>
	<a href="logout.php" class="btn">Sign Out of Your Account</a>
</p>
<?php include "templates/footer.php"; ?>