<?php
/*
Do not initialise REQUIRE_SESSION. We do not need to check that. Initialise. Output
homepage content.
*/

// Includes the init file
require_once '../inc/init.php';
// If logged out, show homepage, then exit the script.

/* You may have the following php vars:

-If error during register/login:
>>>> $_GET('err') can be
-> 'confpass', if confirm password does not match with password
-> 'emailexists' if email already used
-> 'invalid' if the user inserted invalid characters
-> 'locked' if the user got the pass wrong >= 5 times in last 2 hrs (acc locked)
---> 't', the time in seconds until acc wil unlock
-> 'incorrect' if the pass/email are incorrect OR if the email does not exist

>>>> $ioStatus can be
-> 'in', if the user is logged in
-> 'out', if the user is not logged in
*/

// Include the head
require_once __ROOT__."/inc/html/head.php";?>
?>

	<div class="header">
		<a href="/" class="logo-link" title="Home">
			<img src="media/img/logo.svg" alt="Roomies" class="logo-img">
		</a>
<?php
if($ioStatus == "in")
{
	// Output header in
}
?>
	</div>
<div class="header-space"></div>

<?php
if(!LOGGED_IN)
{
?>
	<!-- Main content -->
	<div class="main">
		<!-- Hidden title -->
		<div class="not-mobile banner">
			<header>
				<h1 class="h1">Welcome to Roomies</h1>
				<p class="text">Find the perfect room-mate.</p>
			</header>
		</div>
		<!-- Sign in / Register -->
		<div class="column-wrapper">
			<!-- Sign in -->
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
						<h2 class="h2" id="Sign_in">Sign in</h2>
						<form method="POST" name="signin" action="./login/index.php" onsubmit="return this.email.value?this.password.value?true:(this.password.focus(),false):(this.email.focus(),false)">
							<input type="text" name="login" placeholder="Email/Username" class="input block" required>
							<input type="password" name="password" placeholder="Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
							<input type="submit" value="Sign in" class="input-button block">
						</form>
					</div>
				</div>
			</div>
			<!-- Register -->
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
						<h2 class="h2" id="Register">Register</h2>
						<form method="POST" name="register" action="./confirm/" onsubmit="return this.registerEmail.value?this.registerPassword.value?this.registerPassword.value===this.registerConfirmPassword.value?true:(this.registerConfirmPassword.focus(),false):(this.registerPassword.focus(),false):(this.registerEmail.focus(),false)">
							<input type="email" name="registerEmail" placeholder="Email" class="input block" required>
							<input type="password" name="registerPassword" placeholder="Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
							<input type="password" name="registerConfirmPassword" placeholder="Confirm Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
              <input type="text" name="registerUsername" placeholder="Username" class="input block" required pattern=".{4,25}" title="4 to 25 characters">
							<p class="small-text">By registering, you agree to our
								<a href="#terms" class="link">Terms</a> and
								<a href="#privacy" class="link">Privacy Policy</a>, including our
								<a href="#cookies" class="link">Cookie Use</a>.
							</p>
							<input type="submit" value="Register" class="input-button block">
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Accommodation Reviews -->
		<div class="box">
			<div class="box-padding">
				<h2 class="h2" id="Accommodation_reviews">Accommodation reviews</h2>
				<form method="GET">
					<select name="filter" class="select has-submit" required>
						<option class="option" value="" selected>Choose a University</option>
						<option class="option" value="1">University of Manchester</option>
					</select
					><input type="submit" value="Filter" class="input-button select-submit">
					<a href="#" class="link-button float-right">View All</a>
				</form>
			</div>
		</div>
		<?php require_once __ROOT__."/inc/html/footer.php";?>
	</div>
</body>
</html>

<?php
exit();
}// if(!LOGGED_IN)


// Else, we show the homepage for logged in users
?>
<!--html code for logged in homepage-->

<?php 
if(isset($_GET['logout']))
{
  session_destroy();
  header("Location: .");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to Roomies</title>
  <link rel="stylesheet" type="text/css" href="media/css/style.css">
</head>
<body class="body">
  <?php require_once __ROOT__."/inc/html/header.".$ioStatus.".php";?>
  <!--Main content-->
  <div class="main">
    THIS IS THE LOGGED IN PAGE
    <a href="./?logout=yes">logout</a>
    <?php require_once __ROOT__."/inc/html/footer.php";?>
    
  </div>
</body>
</html>