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


if ($_GET['u'] == $_SESSION['user']['username'])
{
  // I'm on my profile
  $title = $user2->getName();
  $userImagePath = $user2->getCredential('image');

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
				<span class="profile-picture" style="background-image: url('<?=$userImagePath?>'), url(<?=$webRoot?>/media/img/default.gif);"></span>
				<div class="profile-box-inner">
					<h2 class="h2 profile-name"><?=$user2->getName()?></h2>
					<div class="profile-links">
                        <a class='link-button edit-button' data-toggle='edit-profile' id='edit-profile' onclick="editProfile()">Edit Profile</a>
                        <a class='link-button' data-toggle='edit-profile' onclick="editProfile()">Cancel</a>
						<!-- <a class='link-button edit-button' data-show='answered' data-hide='unanswered' id='edit-profile' data-toggle='edit-profile' onclick="editProfile()">Edit Profile</a>
                        <a class='link-button' data-toggle='edit-profile' data-show='unanswered' data-hide='answered' onclick="editProfile()">Cancel</a> -->
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
					$questions = $user2->getQuestion();
					foreach($questions as $question)
					{
						if($question->getError())
						{
							echo $question->getError();
						}
						else
						{
							echo $question->toString();
						}
					}
					?>
                    <div class="change-questions">
                        <div class="profile-links" style="float:left;">
                            <a class="link-button" onclick="prevQuestions()">
                                Previous 
                            </a>
                        </div>
                        <div class="profile-links" style="float:right;">
                            <a class="link-button" onclick="nextQuestions()">
                                Next
                            </a>
                        </div>  
                    </div>
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
          				$details = $user2->getDetails();
          				// echo $details['country'];
          				$x = array_keys($details);
          				foreach ($x as $y) {
          					$z = ucwords($y);
          					if($z == "Uni_city")
          					{
          						$z = "University City";
          					}

          					echo "

          						<div class='details-wrapper'>
          							<div class='details-key'>
                                        $z      
          							</div>
          							<div class='details-value'>
                                        $details[$y]
          							</div>
                                    <div class='new-val'>   
                                        <select class='select-details' data-default='$details[$y]'>";

                            $thisKeyArr = array(
                                    '-'
                                );
                            $stmt = $con->prepare("SELECT map_$y FROM rfiltersmap");
                            $stmt->execute();
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC))
                            {
                                if (!$result['map_'.$y]) break;
                                array_push($thisKeyArr, ucwords($result['map_'.$y]));
                            }
                            for ($i=0;$i<count($thisKeyArr);$i ++)
                            {   
                                if($details[$y] == $thisKeyArr[$i])
                                    echo "<option value='$i' selected>$thisKeyArr[$i]</option>";
                                else
                                    echo "<option value='$i'>$thisKeyArr[$i]</option>";
                            }

                            echo       "</select>
                                    </div>
          						</div>";
                                

          					
          				} ?>

				    </div>

			</div>

		</div>
	</div>
<script type="text/javascript">
    var detailsVal = document.getElementsByClassName('details-value');
    var detailsNewVal = document.getElementsByClassName('new-val');
    var questionsUnanswered = document.getElementsByClassName('unanswered');
    var questionsAnswered = document.getElementsByClassName('answered');
    var answeredIndex = 0;
    var unansweredIndex = 0;

    for(var count = 0; count < questionsAnswered.length; count ++)
    {
        questionsAnswered[count].className = questionsAnswered[count].className + ' hidden';
    };

    // for(var count = 0; count < questionsUnanswered.length; count ++)
    // {
    //     questionsUnanswered[count].className = questionsUnanswered[count].className + ' hidden';
    // };

    // printQuestionsUnanswered();



    for(var count = 0; count < detailsNewVal.length; count ++)
        detailsNewVal[count].style.display = 'none';

    function editProfile() {
        for(var i = 0; i < detailsVal.length; i ++)
        {
            if(detailsVal[i].style.display == '')
            {
                detailsVal[i].style.display = 'none';
                detailsNewVal[i].style.display = '';
            }
            else
            {
                detailsVal[i].style.display = '';
                detailsNewVal[i].style.display = 'none';
            }                      
        };

        for (var i = 0; i < questionsAnswered.length; i++) {
            changeQuestionClass(questionsAnswered[i]);
        };

        for (var i = 0; i < questionsUnanswered.length; i++) {
            changeQuestionClass(questionsUnanswered[i]);
        };

    }

    function changeQuestionClass(el) {
        if(el.className == ' question answered hidden ')
            {
                el.className = ' question answered ';
            }
        else if(el.className == ' question unanswered hidden ')
            {
                el.className = ' question unanswered ';
            }
        else if(el.className == ' question answered ')
            {
                el.className = ' question answered hidden ';
            }
        else
            {
                el.className = ' question unanswered hidden ';
            }
    }

    function printQuestionsUnanswered() {
        window.alert("Hello" + answeredIndex);
        for(var count = answeredIndex; count < answeredIndex + 2; count ++)
        {
            window.alert(questionsUnanswered[count].className);
            changeQuestionClass(questionsUnanswered[count]);
        };
        answeredIndex += 2;
        window.alert(answeredIndex);
    }

    function printQuestionsAnswered() {

    }

    function nextQuestions() {
        answeredIndex += 2;
        window.alert(answeredIndex);
    }

    function prevQuestions() {
        answeredIndex -= 2;
        window.alert(answeredIndex);
    }
</script>
<?php
  require_once __ROOT__."/inc/html/footer.php";
  exit();
}
// I'm on another user's profile
// Get the user and heck if exists;
$otherUsername = $_GET['u'];

$otherUser = new OtherUser($con, $otherUsername);
$otherUserId = $otherUser->getCredential('id');
$otherUsername = $otherUser->getCredential('username');
$userId = $user2->getCredential('id');

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

$status = $user2->friendshipStatus($otherUser);
$addFriendHide       = $status == 0 ? '' : 'hidden';
$alreadyFriendsHide  = $status == 1 ? '' : 'hidden';
$requestSentHide     = $status == 2 ? '' : 'hidden';
$requestReceivedHide = $status == 3 ? '' : 'hidden';
$blockButtonHide     = $status != 4 ? '' : 'hidden';
$unblockButtonHide   = $status == 4 ? '' : 'hidden';

$nameOrUsername = $otherUser->getName($status);

$title = "$otherUsername";
$userImagePath = $otherUser->getCredential('image');

$errorMsg = '';
$errorMsg .= ($otherUser->getError())?$otherUser->getError() . "<br>":'';
$errorMsg .= ($user2->getError())?$user2->getError() . "<br>":'';




// Include head and header
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
  <!-- html for others' profile -->
  <div class="box">
		<div class="box-padding">
			<div class="profile-box">
				<span class="profile-picture" style="background-image: url('<?=$userImagePath?>'), url(<?=$webRoot?>/media/img/default.gif);"></span>
				<div class="profile-box-inner">
					<h2 class="h2 profile-name"><?=$nameOrUsername?></h2>
					<div class="profile-links">
						<a data-ajax-url='$webRoot/php/friends.process.php?a=1&id=<?=$otherUserId?>'
						   data-ajax-text='Sending...'
						   data-ajax-hide='friend-button requestSent'
						   class='link-button friend-button <?=$addFriendHide?>' id='addFriend'>Add Friend</a>

						<span class='minidrop-container friend-button <?=$alreadyFriendsHide?>' id='alreadyFriends'>
						<a data-ajax-url='$webRoot/php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Pending...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Unfriend</a>
						</span>

						<span class='minidrop-container friend-button <?=$requestSentHide?>' id='requestSent'>
						<a data-ajax-url='$webRoot/php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Canceling...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Cancel</a>
						</span>

						<span class='minidrop-container friend-button <?=$requestReceivedHide?>' id='requestReceived'>
						<a data-ajax-url='$webRoot/php/friends.process.php?a=3&id=<?=$otherUserId?>'
						   data-ajax-text='Accepting...'
						   data-ajax-hide='friend-button alreadyFriends'
						   class='link-button'>Accept</a>
						<a data-ajax-url='$webRoot/php/friends.process.php?a=0&id=<?=$otherUserId?>'
						   data-ajax-text='Ignoring...'
						   data-ajax-hide='friend-button addFriend'
						   class='link-button'>Ignore</a>
						</span>

						<a data-ajax-url='$webRoot/php/friends.process.php?a=4&id=<?=$otherUserId?>'
						   data-ajax-text='Blocking...'
						   data-ajax-hide='blockUnblock unblockButton'
						   class='link-button blockUnblock <?=$blockButtonHide?>' id='blockButton'>Block</a>
						<a data-ajax-url='$webRoot/php/friends.process.php?a=5&id=<?=$otherUserId?>'
						   data-ajax-text='Unblocking...'
						   data-ajax-hide='blockUnblock blockButton'
						   class='link-button blockUnblock <?=$unblockButtonHide?>' id='unblockButton'>Unblock</a>
					</div>
					<!--JUST DEBUGGING-->
					<?=$errorMsg?>
					<!--END JUST DEBUGGING-->
				</div>
			</div>
		</div>
	</div>

	<div id="error">
	</div>
	<input id="userId" type="hidden" value="<?=$userId?>"></input>
	<input id="otherUserId" type="hidden" value="<?=$otherUserId?>"></input>

<?php require_once __ROOT__."/inc/html/footer.php";?>
