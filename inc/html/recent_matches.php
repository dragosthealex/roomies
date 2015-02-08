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
  

  $otherUsername = $otherUser->getIdentifier('username');
  $friendsButtons = "<button class='input-button button2'>
                        Add
                      </button>
                    ";

  $status = $user->friendshipStatus($otherUser);
  if($status == 1)
  {
    $otherUsername = $otherUser->getName;
    $friendsButtons = "<button class='input-button button2'>
                        Friends!
                      </button>
                      ";
  }
  else if($status == 2)
  {
    $friendsButtons = "<button class='input-button button2'>
                        Request sent
                      </button>
                      ";
  }
  else if($status == 3)
  {
    $friendsButtons = "<button class='input-button button2'>
                        Accept
                      </button>
                      <button class='input-button button2'>
                        Reject
                      </button>
                      ";
  }
  // Echo the li element
  echo 
  "
    <li class='box'>
      <div class='box-padding'>
        <p class='text'>
          <a href='profile?id=5436' class='link'>
            <img src='media/img/anonymous.jpg' class='profile-picture' alt=''>
            $otherUsername
          </a>
          $friendsButtons
        </p>
        <p class='text'>
          <span style='font-size:1.5em;line-height:0;color:rgba(0,160,0,1)'>
            $percentage
          </span>
      </div>
    </li>
  ";
}


?>