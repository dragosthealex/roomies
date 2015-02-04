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
$title = "Welcome Roomies";
$dots = "";
$home = 1;
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";

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

//Check if the user completed their profile
if(isset($_SESSION['notComplete']) && ($_SESSION['notComplete'] == true))
{
	header("Location: complete-register/");
  exit();
}

// Check if user has completed their details in $comp boolean
$id = $_SESSION['user']['id'];
$stmt = $con->prepare("SELECT completed FROM rdetails WHERE profile_filter_id = $id");
$stmt->execute();
$stmt->bindColumn(1, $comp);
$stmt->fetch();

// For mock
$userImagePath = 'media/img/profile-picture-placeholder.gif';
?>
  <!--Main content-->
  <div class="main">
    <div class="box">
    	<div class="box-padding">
				<p class="greeting"> 
					Hello, <?php echo $_SESSION['user']['username'];?>
				</p>
				<div class="profile-box">
					<div class="main-pic" style="background-image: url('<?php echo $userImagePath;?>');">
					</div>
					<div class="links-wrapper">
			    	<ul class="ul">
			    		<li class="link-button"> 
			    			<a href="#friends" class="links">
			    				Friend Requests
			    			</a>
			    		</li>
			    		<li class="link-button"> 
			    			<a href="#inbox" class="links">
			    				Messages
			    			</a>
			    		</li>
			    		<li class="link-button"> 
			    			<a href="#reviews" class="links">
			    				Review
			    			</a>
			    		</li>
			    		<li class="link-button"> 
			    			<a href="search/" class="links">
			    				Search
			    			</a>
			    		</li>   	
			    	</ul>
		    	</div>
		    </div>
		  </div>
		</div>
		<?php if(!$comp){include "./complete-register/optionalDetails.php";}?>
		<div class="column-wrapper">
			<div class="column-2">
				<div class="column-box">
					<div class="box-padding">
			    	<p class="h2">
			    		Recent Matches
			    	</p> 
				    <ul class="ul">
				    	<li class="box">
					    	<div class="box-padding">
					    		<p class="text">
					    			<a href="profile?id=5436" class="link">
					    				<img src="media/img/anonymous.jpg" class="profile-picture" alt="">
					    				testJohn21
					    			</a>
					    			<button class="input-button button2">
					    				Add
					    			</button>
					    		</p>
					    		<p class="text">
					    			<span style="font-size:1.5em;line-height:0;color:rgba(0,160,0,1)">
					    				100%
					    			</span>
					    	</div>
				    	</li>
				    	<li class="box"> 
				    		<div class="box-padding">
					    		<p class="text">
					    			<a href="profile?id=5436" class="link">
					    				<img src="media/img/anonymous.jpg" class="profile-picture" alt="">
					    				testJohn22
					    			</a>
					    			<button class="input-button button2">
					    				Add
					    			</button>
					    		</p>
					    		<p class="text">
					    			<span style="font-size:1.5em;line-height:0;color:rgba(0,160,0,1)">
					    				99%
					    			</span>
					    	</div>
				    	</li>
						</ul>
					</div>
				</div>
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
	<?php require_once __ROOT__."/inc/html/footer.php";?>
  </div>
</body>
</html>