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
	<!-- Profile Bar -->
	<div class="box">
		<div class="box-padding">
			<div class="profile-box">
					<div class="main-pic">
					</div>
					<div class="details-box">
						<div class="box-padding">
							<h2 class="h2"><?=$user->getName();?></h2>
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
					<?php
          // retrieve list from database
          $details = $user->getDetails();
					foreach ($details as $detail)
					{
						echo "$detail <br>";
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php
  require_once __ROOT__."/inc/html/footer.php";
  exit();
}
// I'm on another user's profile
// Get the user and heck if exists;
$otherUsername = $_GET['u'];

$otherUser = new User($con, $otherUsername);
$otherUserId = $otherUser->getIdentifier('id');
$otherUsername = $otherUser->getIdentifier('username');
$userId = $user->getIdentifier('id');

if(!isset($otherUserId) || !$otherUserId)
{
  $stmt = null;
  include __ROOT__."/inc/html/notfound.php";
  exit();
}
$stmt = null;

// Echo the appropiate possible buttons for friends
$addFriend = "<li class='float-left'>
	<a data-ajax-url='../php/friends.process.php?a=1&id=$otherUserId'
	   data-ajax-text='Sending...'
	   data-ajax-hide='friend-button requestSent'
	   class='link-button friend-button' id='addFriend'>Add friend</a></li>";
$alreadyFriends = "<li class='float-left'>
	<span class='minidrop-container friend-button' id='alreadyFriends'>
	<a data-ajax-url='../php/friends.process.php?a=0&id=$otherUserId'
	   data-ajax-text='Pending...'
	   data-ajax-hide='friend-button addFriend'
	   class='link-button'>Unfriend</a></span></li>";
$requestSent = "<li class='float-left'>
	<span class='minidrop-container friend-button' id='requestSent'>
	<a data-ajax-url='../php/friends.process.php?a=0&id=$otherUserId'
	   class='link-button'>Cancel request</a></span></li>";
$requestReceived = "<li class='float-left'>
	<a data-action='../php/friends.process.php?a=3&id=$otherUserId'
	   data-action-toggle='../php/friends.process.php?a=0&id=$otherUserId'
	   data-text-toggle='Friends!'
	   class='link-button friend-button'>Accept</a></li>";
$blockButton = "<li class='float-left'>
	<a data-action='../php/friends.process.php?a=4&id=$otherUserId'
	   data-action-toggle='../php/friends.process.php?a=5&id=&otherUserId'
	   data-text-toggle='Unblock' class='link-button ajax '>Block user</a></li>";
$unblockButton = "<li class='float-left'>
	<a data-action='../php/friends.process.php?a=5&id=$otherUserId'
	   data-action-toggle='../php/friends.process.php?a=1&id=&otherUserId'
	   data-text-toggle='Add Friend' class='link-button ajax '>Unblock user</a></li>";
/* Check friendship and set the button accordingly
0 -> not friends
1 -> friends
2 -> I sent the request
3 -> I received request
4 -> Blocked
*/
// for testing, blocked is definded false
$blocked = 0;

$status = $user->friendshipStatus($otherUser);
switch ($status)
{
	case 0:
		$friendsButton = $addFriend;
		break;
	case 1:
		$friendsButton = $alreadyFriends;
		break;
	case 2:
		$friendsButton = $requestSent;
		break;
	case 3:
		$friendsButton = $requestReceived;
		break;
	default:
		$friendsButton = $addFriend;
		break;
}
$nameOrUsername = ($status == 1)?$otherUser->getName():$otherUsername;

$title = "$otherUsername's profile";

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
  <!-- html for others' profile -->
  <div class="box">
		<div class="box-padding">
			<div class="profile-box">
					<div class="main-pic" style="background-image: url('<?=$userImagePath?>');">
					</div>
					<div style="float:left;">
						<h2 class="h2">
							<?=$nameOrUsername;?>
						</h2>
						<div class="links-wrapper">
				    	<ul class="ul">
				    		<?=$friendsButton?>
				    		<?=$blocked?$unblockButton:$blockButton?>
				    	</ul>
			    	</div>
		    	</div>
		    </div>
		</div>
	</div>
	<div id="error">
	</div>
	<input id="userId" type="hidden" value="<?=$userId?>"></input>
	<input id="otherUserId" type="hidden" value="<?=$otherUserId?>"></input>
<?php require_once __ROOT__."/inc/html/footer.php";?>