<?php
// If not get submit, use default search
// DISCLAIEMER : DONE AT 4 AM
$errorMsg = '';
try
{
  if(!count($_GET))
  {
    $id = $user2->getCredential('id');
    $userGender = $user2->getCredential('gender');
    $userLanguage = $user2->getCredential('language');
    $birthday = date_create($user2->getCredential('birthday'));
    $city = $user2->getCredential('uni_city');
    $year = date_format($birthday, 'Y');
    $lowerYear = $year + 2;
    $upperYear = $year - 2;
    $eighteenYears = date('Y') - 18;
    if ($eighteenYears < $lowerYear)
    {
      $lowerYear = $eighteenYears;
    }
    $month = date_format($birthday, 'm');
    $day = date_format($birthday, 'd');
    $lowerBday = $lowerYear . '-' . $month . '-' . $day;
    $upperBday = $upperYear . '-' . $month . '-' . $day;

    $condition = "gender='$userGender' AND language='$userLanguage' AND birthday>='$upperBday' AND birthday<='$lowerBday' AND uni_city='$city'";
  }
  else
  {
    // Dynamically construct the condition array
    $condition = '';
    foreach ($_GET as $key => $value)
    {
      if ($value == 0) continue;
      switch ($key) {
        case 'online_last':
          // TODO
          break;

        case 'upperAge':
          $bYear = date('Y') - $value;
          $month = date('m');
          $day = date('d');
          $upperBday = $bYear . "-$month-$day";
          $condition .= "birthday>='$upperBday' AND ";
          break;

        case 'lowerAge':
          $bYear = date('Y') - $value;
          $month = date('m');
          $day = date('d');
          $lowerBday = $bYear . "-$month-$day";
          $condition .= "birthday<='$lowerBday' AND ";
          break;

        default:
          $condition .= "$key='$value' AND ";
          break;
      }
    }
    $condition .= "1=1";
  }

  // Select the thingies form db
  $stmt = $con->prepare("SELECT percentage, percentage_user_id1, percentage_user_id2 FROM rpercentages
                          WHERE (percentage_user_id1 = ANY (SELECT profile_filter_id FROM rdetails 
                                                        WHERE $condition)
                                AND percentage_user_id2 = $id)
                            OR  (percentage_user_id2 = ANY (SELECT profile_filter_id FROM rdetails 
                                                        WHERE $condition)
                                AND percentage_user_id1 = $id)");

  if(!$stmt->execute())
  {
    throw new Exception("fuck you fabulous bitch! in the ass without lube", 1);
  }
  $stmt->bindColumn(1, $percentage);
  $stmt->bindColumn(2, $id1);
  $stmt->bindColumn(3, $id2);

  // Make matches array
  $matches = array();
  while($stmt->fetch())
  {
    // The other user id is the id that is not ours
    $leOtherUser = new OtherUser($con, ($id1 == $id) ? $id2 : $id1);
    if ($leOtherUser->getError()) continue;
    $match['percentage'] = $percentage;
    $otherUserId = $leOtherUser->getCredential('id');
    $profilePic = $leOtherUser->generateProfilePicture('profile-picture');
    $status = $user2->friendshipStatus($leOtherUser);
  $addFriendHide       = $status == 0 ? '' : 'hidden';
  $alreadyFriendsHide  = $status == 1 ? '' : 'hidden';
  $requestSentHide     = $status == 2 ? '' : 'hidden';
  $requestReceivedHide = $status == 3 ? '' : 'hidden';
  $blockButtonHide     = $status != 4 ? '' : 'hidden';
  $unblockButtonHide   = $status == 4 ? '' : 'hidden';

  $otherUserId = $leOtherUser->getCredential('id');
  $otherUsername = $leOtherUser->getCredential('username');
  $nameOrUsername = $leOtherUser->getName($user2->friendshipStatus($leOtherUser));

echo "<li class='profile-box-item'>
      <div class='profile-box'>
          $profilePic
        <div class='profile-box-inner'>
          <a href='profile/$otherUsername' class='h2 profile-name'>$nameOrUsername</a>
          <div class='profile-percent' style='color:rgba(".(160-160*$percentage/100).",".(160*$percentage/100).",0,1);'>$percentage%</div>
          <div class='profile-links'>

            <a data-ajax-url='$webRoot/php/friends.process.php?a=1&amp;id=$otherUserId'
               data-ajax-text='Sending...'
               data-ajax-hide='frequest-rm-$otherUserId requestSent-rm-$otherUserId'
               class='link-button frequest-rm-$otherUserId $addFriendHide' id='addFriend-rm-$otherUserId'>Add Friend</a>

            <span class='minidrop-container frequest-rm-$otherUserId $alreadyFriendsHide' id='alreadyFriends-rm-$otherUserId'>
            <a data-ajax-url='$webRoot/php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Pending...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Unfriend</a>
            </span>

            <span class='minidrop-container frequest-rm-$otherUserId $requestSentHide' id='requestSent-rm-$otherUserId'>
            <a data-ajax-url='$webRoot/php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Canceling...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Cancel</a>
            </span>

            <span class='minidrop-container frequest-rm-$otherUserId $requestReceivedHide' id='requestReceived-rm-$otherUserId'>
            <a data-ajax-url='$webRoot/php/friends.process.php?a=3&amp;id=$otherUserId'
               data-ajax-text='Accepting...'
               data-ajax-hide='frequest-rm-$otherUserId alreadyFriends-rm-$otherUserId'
               class='link-button'>Accept</a>
            <a data-ajax-url='$webRoot/php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Ignoring...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Ignore</a>
            </span>

            <a data-ajax-url='$webRoot/php/friends.process.php?a=4&amp;id=$otherUserId'
               data-ajax-text='Blocking...'
               data-ajax-hide='blockUnblock-rm-$otherUserId unblockButton-rm-$otherUserId'
               class='link-button blockUnblock-rm-$otherUserId $blockButtonHide' id='blockButton-rm-$otherUserId'>Block</a>
            <a data-ajax-url='$webRoot/php/friends.process.php?a=5&amp;id=$otherUserId'
               data-ajax-text='Unblocking...'
               data-ajax-hide='blockUnblock-rm-$otherUserId blockButton-rm-$otherUserId'
               class='link-button blockUnblock-rm-$otherUserId $unblockButtonHide' id='unblockButton-rm-$otherUserId'>Unblock</a>
          </div>
        </div>
      </div>
    </li>";

    array_push($matches, $match);
  }
}
catch (Exception $e)
{
  $errorMsg = $e->getMessage();
}
echo $errorMsg;
?>