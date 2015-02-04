<?php
/*
You are going to make the html for the profile of the current 
logged in user.
The header would already be included.
Please read the gantt chart note for this task (task 10)

<!DOCTYPE>, <html> and <body> already started
*/

// Initialise the session (do not modify this)
//define("REQUIRE_SESSION", true);
include '../../inc/init.php';
include __ROOT__.'/inc/classes/user.php';
// Controls the relative path of the stylesheets in "head.php"
$dots = "../";
// The title of the page

// If GET[u] is not set, it means that we're on our own profile. Else, we are on another user's profile
if(!isset($_GET['u']) || (isset($_GET['u']) && $_GET['u'] == $_SESSION['user']['username']))
{
  // I'm on my profile
  $title = "My profile";
  // Include head and header
  require_once __ROOT__."/inc/html/head.php";
  require_once __ROOT__."/inc/html/header.$ioStatus.php";
  // Page begins here, html and body tags are opened in head, closed in footer. Also, main div is closed in footer

?>
<!-- html for my profile -->
<!-- Main content -->
<div class="main">
	<!-- Profile Bar -->
	<div class="box">
		<div class="box-padding">
			<div class="profile-box">
					<div class="main-pic">
					</div>
					<div class="details-box">
						<div class="box-padding">
							<h2 class="h2"><?php echo $user->getName();?></h2>
							<!-- php to retrieve information from the database should replace this -->
							<p class="text">Main details e.g Gender and Stuff</p>
						</div>
					</div>	
			</div>
		</div>
	</div>
	<div class="column-wrapper">
		<!--About me-->
		<div class="column-2">
			<div class="column-box">	
				<div class="box-padding">
					<h2 class="h2">Questionnaire</h2>
					<!-- php to retrieve 'Questionnaire' from database should be here' -->
					<?php
					$questions = $user->getQuestion();
					foreach($questions as $question)
					{
						echo $question->toString();
					}

					?>
					<p class="text">
					
					</p>
				</div>
			</div>
		</div>
		<!--filters-->
		<div class="column-2">
			<div class="column-box">
				<div class="box-padding">
					<h2 class="h2">My Details</h2>
					<?php $details = $user->getDetails();
					foreach ($details as $detail)
					{
						echo "$detail <br>";
					}
					?>
					<!-- php to retrieve list from database -->
				</div>
			</div>
		</div>
	</div>
<?php require_once __ROOT__."/inc/html/footer.php";?>

<?php
exit();
}
// I'm on another user's profile
$user = htmlentities($_GET['u']);
$title = "$user's profile";
$stmt = $con->prepare("SELECT user_id FROM rusers WHERE username = '$user'");
$stmt->execute();
$stmt->bindColumn(1, $id);
$stmt->fetch();

if(!$stmt->rowCount())
{
  $stmt = null;
  include __ROOT__."/inc/html/notfound.php";
  exit();
}

$stmt = null;
// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
<!-- html for others' profile -->
<!-- Main content -->
<div class="main">
  <h1 class="h1">You are on <?php echo $user;?>'s profile</h1>
<?php require_once __ROOT__."/inc/html/footer.php";?>