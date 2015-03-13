<?php
/*
Do not initialise REQUIRE_SESSION. We do not need to check that. Initialise. Output
homepage content.
*/
// Includes the init file
$rootDirectory = 1;
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
$title = "Roomies";
if (!LOGGED_IN)
{
	// If the user is not logged in, then welcome them
	$title = "Welcome to " . $title;
}

if(!LOGGED_IN)
{
	require_once __ROOT__."/inc/html/head.php";
	echo "<script src='$webRoot/media/js/facebook_login.js'></script>";
	require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
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
						<form method="POST" name="signin" action="./login/index.php" onsubmit="return this.login.value?this.password.value?true:(this.password.focus(),false):(this.login.focus(),false)">
							<input type="text" name="login" placeholder="Email/Username" class="input block" required>
							<input type="password" name="password" placeholder="Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
							<label for="remember_me" class="cr-label">
								<input type="checkbox" id="remember_me" name="rememberMe" class="cr">
								<span class="cr-button"></span>
								<span class="cr-text cr-text-faded">Remember me</span>
							</label>
							<input type="submit" value="Sign in" class="input-button block">

							<!--Facebook Login-->
							<fb:login-button scope="public_profile,email,user_birthday" onlogin="checkLoginState();">
							</fb:login-button>
							<div id="status">
							</div>

						</form>
					</div>
				</div>
			</div>
			<!-- Register -->
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
						<h2 class="h2" id="Register">Register</h2>
						<form method="POST" name="register" action="./confirm/" onsubmit="return this.registerEmail.value?this.registerPassword.value?this.registerPassword.value===this.registerConfirmPassword.value?true:(this.registerConfirmPassword.focus(),newError('Passwords must match!'),false):(this.registerPassword.focus(),newError('A password is required.'),false):(this.registerEmail.focus(),newError('An email is required.'),false)">
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
					><input type="submit" value="Filter" class="input-button">
					<a href="#" class="link-button float-right">View All</a>
				</form>
			</div>
		</div>
<?php
} // if (!LOGGED_IN)
// Else, we show the homepage for logged in users
else
{

	//Check if the user completed their profile
	if(isset($_SESSION['notComplete']) && ($_SESSION['notComplete'] == true))
	{
		header("Location: complete-register/");
	  exit();
	}

	// Check if user has completed their details in $comp boolean
	$id = $_SESSION['user']['id'];
	$userImagePath = $user->getIdentifier('image');
	$userImagePath = ($userImagePath == '/media/img/default.gif')?$webRoot.$userImagePath:$userImagePath;
	//$user = new User($con, $id);
	$stmt = $con->prepare("SELECT completed FROM rdetails WHERE profile_filter_id = $id");
	$stmt->execute();
	$stmt->bindColumn(1, $comp);
	$stmt->fetch();

	require_once __ROOT__."/inc/html/head.php";
	require_once __ROOT__."/inc/html/header.$ioStatus.php";
	
	if (!$comp)
	{
		if (JUST_LOGGED_IN)
		{
			include __ROOT__."/inc/html/optionalDetails.php";
		}
		include __ROOT__."/inc/html/notifications.php";
	}
?>
		<div class="box">
			<div class="box-padding">
				<div class="profile-box">
					<span class="profile-picture" style="background-image: url('<?=$userImagePath?>'), url(<?=$webRoot?>/media/img/default.gif);"></span>
					<h2 class="h2 profile-name"><?=$user->getName()?></h2>
					<div class="profile-links">
						<a href="#reviews" class="link-button">Review</a>
						<a href="<?=$webRoot?>/search" class="link-button">Search</a>
						<a href="<?=$webRoot?>/messages" class="link-button">Messages</a>
					</div>
				</div>
			</div>
		</div>
		<div class="column-wrapper">
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
						<p class="h2">
							Recent Matches
						</p>
						<ul class="ul">
							<?php require_once __ROOT__.'/inc/html/recent_matches.php';?>
						</ul>
					</div>
				</div>
			</div>
			<div
  class="fb-like"
  data-share="true"
  data-width="450"
  data-show-faces="true">
</div>
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
						<p class="h2"> Popular Accommodation </p>
						<ul class="ul">
							<li class="box accommodation">
								<div class="box-padding">
									<p>
										Whitworth Park
									</p>
									<img class="housepic" src="media/img/banana.jpeg">
									<div class="house-description">
										<p>
											Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce nec nibh lectus. Nam convallis a eros in finibus. Suspendisse varius, turpis eget mollis consectetur, neque erat tincidunt diam, sed porttitor lorem nunc sed eros.
										</p>
									</div>
								</div>
							</li>
							<li class="box accommodation">
								<div class="box-padding">
									<p>
										Dalton-Ellis
									</p>
									<img class="housepic" src="media/img/banana.jpeg">
									<div class="house-description">
										<p>
											Nulla facilisi. Donec eu ante auctor ipsum tempus consequat ut ut ante. Integer a laoreet tortor, at volutpat nibh. Aliquam erat volutpat. Vestibulum eget commodo justo. Quisque ipsum ipsum, ph
										</p>
									</div>
								</div>
							</li>
							<li class="box accommodation">
								<div class="box-padding">
									<p>
										Rusholme
									</p>
									<img class="housepic" src="media/img/banana.jpeg">
									<div class="house-description">
										<p>
											Nunc cursus enim a vulputate
										</p>
									</div>
								</div>
							</li>
						</ul>
				</div>
			</div>
		</div>
	</div>
<?php
} // else
require_once __ROOT__."/inc/html/footer.php";
?>