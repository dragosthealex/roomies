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

// If GET[u] is not set, it means that we're on our own profile.
if (!isset($_GET['u']))
{
  header("Location: $webRoot/profile/".$_SESSION['user']['username']);
  exit();
}

$userImagePath = $user->getIdentifier('image');

if ($_GET['u'] == $_SESSION['user']['username'])
{
  // I'm on my profile
  $title = $user->getName();
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
				<span class="profile-picture" style="background-image: url('<?=$userImagePath?>');"></span>
				<div class="profile-box-inner">
					<h2 class="h2 profile-name"><?=$user->getName()?></h2>
					<div class="profile-links">
						<a class='link-button'>Edit Profile</a>
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
$addFriendHide       = $status == 0 ? '' : 'style="display: none"';
$alreadyFriendsHide  = $status == 1 ? '' : 'style="display: none"';
$requestSentHide     = $status == 2 ? '' : 'style="display: none"';
$requestReceivedHide = $status == 3 ? '' : 'style="display: none"';
$blockButtonHide     = $status != 4 ? '' : 'style="display: none"';
$unblockButtonHide   = $status == 4 ? '' : 'style="display: none"';

$nameOrUsername = ($status == 1)?$otherUser->getName():$otherUsername;

$title = "$otherUsername";

// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
  <!-- html for others' profile -->
  <div class="box">
		<div class="box-padding">
			<div class="profile-box">
				<span class="profile-picture" style="background-image: url('<?=$userImagePath?>');"></span>
				<div class="profile-box-inner">
					<h2 class="h2 profile-name"><?=$nameOrUsername?></h2>
					<div class="profile-links">
						<a data-ajax-url='../php/friends.process.php?a=1&id=<?=$otherUserId?>'
						   data-ajax-text='Sending...'
						   data-ajax-hide='friend-button requestSent' <?=$addFriendHide?>
						   class='link-button friend-button' id='addFriend'>Add Friend</a>

						<span class='minidrop-container friend-button' id='alreadyFriends' <?=$alreadyFriendsHide?>>
						<a data-ajax-url='../php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Pending...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Unfriend</a>
						</span>

						<span class='minidrop-container friend-button' id='requestSent' <?=$requestSentHide?>>
						<a data-ajax-url='../php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Canceling...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Cancel</a>
						</span>

						<span class='minidrop-container friend-button' id='requestReceived' <?=$requestReceivedHide?>>
						<a data-ajax-url='../php/friends.process.php?a=3&id=<?=$otherUserId?>'
						   data-ajax-text='Accepting...'
						   data-ajax-hide='friend-button alreadyFriends'
						   class='link-button'>Accept</a>
						<a data-ajax-url='../php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Ignoring...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Ignore</a>
						</span>

						<a data-ajax-url='../php/friends.process.php?a=4&id=<?=$otherUserId?>'
						   data-ajax-text='Blocking...'
						   data-ajax-hide='blockUnblock unblockButton' <?=$blockButtonHide?>
						   class='link-button blockUnblock' id='blockButton'>Block</a>
						<a data-ajax-url='../php/friends.process.php?a=5&id=<?=$otherUserId?>'
						   data-ajax-text='Unblocking...'
						   data-ajax-hide='blockUnblock blockButton' <?=$unblockButtonHide?>
						   class='link-button blockUnblock' id='unblockButton'>Unblock</a>
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