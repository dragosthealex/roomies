<?php
/*
This script gets the first 5 matches with this user from the db
*/

// Localise id
$thisUserId = $user->getIdentifier('id');
$city = $user->getIdentifier('city');
// Get the ids of the users
$stmt = $con->prepare("SELECT percentage_user_id1, percentage_user_id2, percentage
                        FROM rpercentages
                        WHERE (percentage_user_id1=$thisUserId OR percentage_user_id2=$thisUserId)
                          AND percentage_city=$city
                        ORDER BY percentage DESC
                        LIMIT 5");
$stmt->execute();
$stmt->bindColumn(1, $id1);
$stmt->bindColumn(2, $id2);
$stmt->bindColumn(3, $percentage);
while($stmt->fetch())
{
  // We are interested in the other user (our match)
  if($id1 == $thisUserId)
  {
    // Means that $id2 is other user
    $otherUser = new User($con, $id2);
  }
  else
  {
    // Means that $id1 is other user
    $otherUser = new User($con, $id1);
  }
  

  $status = $user->friendshipStatus($otherUser);
  $addFriendHide       = $status == 0 ? '' : 'style="display: none"';
  $alreadyFriendsHide  = $status == 1 ? '' : 'style="display: none"';
  $requestSentHide     = $status == 2 ? '' : 'style="display: none"';
  $requestReceivedHide = $status == 3 ? '' : 'style="display: none"';
  $blockButtonHide     = $status != 4 ? '' : 'style="display: none"';
  $unblockButtonHide   = $status == 4 ? '' : 'style="display: none"';

  $otherUserId = $otherUser->getIdentifier('id');
  $otherUsername = $otherUser->getIdentifier('username');
  $nameOrUsername = ($status == 1)?$otherUser->getName():$otherUsername;

  $userImagePath = $otherUser->getIdentifier('image');
  $userImagePath = ($userImagePath == '/media/img/default.gif')?$webRoot.$userImagePath:$userImagePath;

  // $friendsButtons = "<button class='input-button button2'>
  //                       Add
  //                     </button>
  //                   ";

  // $status = $user->friendshipStatus($otherUser);
  // if($status == 1)
  // {
  //   $otherUserName = $otherUser->getName();
  //   $friendsButtons = "<button class='input-button button2'>
  //                       Friends!
  //                     </button>
  //                     ";
  // }
  // else if($status == 2)
  // {
  //   $friendsButtons = "<button class='input-button button2'>
  //                       Request sent
  //                     </button>
  //                     ";
  // }
  // else if($status == 3)
  // {
  //   $friendsButtons = "<button class='input-button button2'>
  //                       Accept
  //                     </button>
  //                     <button class='input-button button2'>
  //                       Reject
  //                     </button>
  //                     ";
  // }
  // // Echo the li element
  // echo 
  // "
  //   <li class='box'>
  //     <div class='box-padding'>
  //       <p class='text'>
  //         <a href='profile/$otherUsername' class='link'>
  //           <img src='media/img/anonymous.jpg' class='profile-picture' alt=''>
  //           $otherUserName
  //         </a>
  //         $friendsButtons
  //       </p>
  //       <p class='text'>
  //         <span style='font-size:1.5em;line-height:0;color:rgba(0,160,0,1)'>
  //           $percentage%
  //         </span>
  //     </div>
  //   </li>
  // ";
  echo "<li class='profile-box-item'>
      <div class='profile-box'>
        <a href='profile/$otherUsername' style='background-image: url(\"$userImagePath\")' class='profile-picture'></a><!--style='background-image: url(\"$userImagePath\");'-->
        <div class='profile-box-inner'>
          <a href='profile/$otherUsername' class='h2 profile-name'>$nameOrUsername</a>
          <div class='profile-percent' style='color:rgba(".(160-160*$percentage/100).",".(160*$percentage/100).",0,1);'>$percentage%</div>
          <div class='profile-links'>

            <a data-ajax-url='../php/friends.process.php?a=1&amp;id=$otherUserId'
               data-ajax-text='Sending...'
               data-ajax-hide='frequest-rm-$otherUserId requestSent-rm-$otherUserId' $addFriendHide
               class='link-button frequest-rm-$otherUserId' id='addFriend-rm-$otherUserId'>Add Friend</a>

            <span class='minidrop-container frequest-rm-$otherUserId' id='alreadyFriends-rm-$otherUserId' $alreadyFriendsHide>
            <a data-ajax-url='../php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Pending...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Unfriend</a>
            </span>

            <span class='minidrop-container frequest-rm-$otherUserId' id='requestSent-rm-$otherUserId' $requestSentHide>
            <a data-ajax-url='../php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Canceling...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Cancel</a>
            </span>

            <span class='minidrop-container frequest-rm-$otherUserId' id='requestReceived-rm-$otherUserId' $requestReceivedHide>
            <a data-ajax-url='../php/friends.process.php?a=3&amp;id=$otherUserId'
               data-ajax-text='Accepting...'
               data-ajax-hide='frequest-rm-$otherUserId alreadyFriends-rm-$otherUserId'
               class='link-button'>Accept</a>
            <a data-ajax-url='../php/friends.process.php?a=0&amp;id=$otherUserId'
               data-ajax-text='Ignoring...'
               data-ajax-hide='frequest-rm-$otherUserId addFriend-rm-$otherUserId'
               class='link-button'>Ignore</a>
            </span>

            <a data-ajax-url='../php/friends.process.php?a=4&amp;id=$otherUserId'
               data-ajax-text='Blocking...'
               data-ajax-hide='blockUnblock-rm-$otherUserId unblockButton-rm-$otherUserId' $blockButtonHide
               class='link-button blockUnblock-rm-$otherUserId' id='blockButton-rm-$otherUserId'>Block</a>
            <a data-ajax-url='../php/friends.process.php?a=5&amp;id=$otherUserId'
               data-ajax-text='Unblocking...'
               data-ajax-hide='blockUnblock-rm-$otherUserId blockButton-rm-$otherUserId' $unblockButtonHide
               class='link-button blockUnblock-rm-$otherUserId' id='unblockButton-rm-$otherUserId'>Unblock</a>
          </div>
        </div>
      </div>
    </li>";
}


?>