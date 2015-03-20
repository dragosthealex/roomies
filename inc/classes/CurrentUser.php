<?php
/**
* Class CurrentUser
*
* Represents the current user that is logged in and uses the website
*/
require_once __ROOT__.'/inc/classes/GeneralUser.php';
require_once __ROOT__.'/inc/classes/Accommodation.php';
class CurrentUser extends GeneralUser
{
  /**
  * Constructor
  *
  * Constructs the logged in user, given the $con
  *
  */
  public function __construct($con)
  {
    // Get the id from the session
    $id = isset($_SESSION['user']['id'])?$_SESSION['user']['id']:'';
    $username = isset($_SESSION['user']['username'])?$_SESSION['user']['username']:'';
    $email = isset($_SESSION['user']['email'])?$_SESSION['user']['email']:'';
    $lastOnline = isset($_SESSION['user']['lastOnline'])?$_SESSION['user']['lastOnline']:'0000-00-00';

    try
    {
      // Problem if we don't have an id
      if(!$id || !$username || !$email)
      {
        throw new Exception("Id, username or email not found in session", 1);
      }

      // Get the image url and the groups
      $stmt = $con->prepare("SELECT image_url FROM rusers WHERE user_id = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting user details from database. Ya dun goof", 1);
      }
      $stmt->bindColumn(1, $imageUrl);
      $stmt->bindColumn(2, $groupsThisUserIsIn);
      $stmt->fetch();

      // Get the user
      $stmt = $con->prepare("SELECT * FROM rdetails WHERE profile_filter_id =$id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting details from database", 1);
      }
      $details = $stmt->fetch(PDO::FETCH_ASSOC);
      if(!isset($details['first_name'], $details['last_name'], $details['birthday']))
      {
        $details['first_name'] = '';
        $details['last_name'] = '';
        $details['birthday'] = '';
      }

      // Get the groups
      $groups = array();
      $stmt = $con->prepare("SELECT group_group_id FROM ruser_groups WHERE group_user_id = $id");
      if(!$stmt->execute())
      {
        throw new Exception("Error getting group details from database", 1);
      }
      $stmt->bindColumn(1, $groupId);
      while($stmt->fetch())
      {
        array_push($groups, $groupId);
      }

      // Assign the unmapped STRING details
      $this->name = $details['first_name'] . " " . $details['last_name'];
      $this->birthday = $details['birthday'];

      // Assingn the details
      $this->details = $details;

      // Assign the rest of credentials
      $this->id = $id;
      $this->username = $username;
      $this->email = $email;
      $this->con = $con;
      $this->image = $imageUrl;
      $this->groups = $groups;
      $this->lastOnline = $lastOnline;
    }// try
    catch (Exception $e)
    {
      $this->setError($e->getMessage());
    }
  }// construct

  /**
  * Function getName()
  *
  * Overrides the function in parent. Gets this current user's name
  *
  * @return - $name(String), the first+last name
  */
  public function getName($friendshipStatus = 0)
  {
    return $this->name;
  }



  /**
  * Function addFriend($otherUser)
  *
  * Modifies the friendship status between this user and $other user, depending on $action parameter
  * Action can be:
  * 0 -> remove friend
  * 1 -> add friend
  * 2 -> cancel request
  * 3 -> accept request
  * 4 -> block user
  * 5 -> unblock user
  *
  * @param - $otherUser, the other user object
  * @param - $action, the action that determines the processing
  */
  public function addFriend($otherUser, $action)
  {
    // Localise stuff
    $otherUserId = $otherUser->getCredential('id');
    $con = $this->con;
    $thisUserId = $this->id;
    $status = $this->friendshipStatus($otherUser);

    switch ($action)
    {
      case 1:
        try
        {
          if($status)
          {
            throw new Exception("There Is already a connection in database", 1);
          }

          $stmt = $con->prepare("INSERT INTO rconexions (conexion_user_id1, conexion_user_id2, conexion_status)
                                  VALUES ($thisUserId, $otherUserId, 2)");
          $stmt->execute();
          $stmt = null;
        }
        catch (Exception $e)
        {
          $this->setError($e->getMessage());
        }
        break;
      case 0:
      case 2:
          $stmt = $con->prepare("DELETE FROM rconexions
                                  WHERE (conexion_user_id2 = $thisUserId AND conexion_user_id1 = $otherUserId)
                                  OR (conexion_user_id1 = $thisUserId AND conexion_user_id2 = $otherUserId)");
          $stmt-> execute();
          $stmt = null;
        break;
      case 3:
        try
        {
          if($status != 3)
          {
            throw new Exception("Error. Weird status in database", 1);
          }


          $stmt = $con->prepare("UPDATE rconexions SET conexion_status=1
                                  WHERE conexion_user_id2 = $thisUserId AND conexion_user_id1 = $otherUserId");
          $stmt->execute();
          $stmt = null;
        }
        catch (Exception $e)
        {
          $this->setError($e->getMessage());
        }
        break;
      default:
        # code...
        break;
    }// switch
  }//function addFriend

  /**
  * Function friendshipStatus($otherUser)
  *
  * Returns 0 if not friends, 1 if friends, 2 if request pending, 3 if request received
  *
  * @param - $otherUser, the user that we check the friendship with
  * @return - $status, the status of the connexion
  */
  public function friendshipStatus($otherUser)
  {
    $otherUserId = $otherUser->getCredential('id');
    $con = $this->con;
    $thisUserId = $this->id;

    $stmt = $con->prepare("SELECT conexion_status, conexion_user_id1 FROM rconexions
      WHERE (conexion_user_id1 = $thisUserId AND conexion_user_id2 = $otherUserId)
      OR (conexion_user_id1 = $otherUserId AND conexion_user_id2 = $thisUserId)");

    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting the status from database", 1);
      }
      // If no row is found, means no friends
      if(!$stmt->rowCount())
      {
        return 0;
      }
      $stmt->bindColumn(1, $status);
      $stmt->bindColumn(2, $id1);
      $stmt->fetch();

      // Check if they are friends
      if($status == 1)
      {
        return 1;
      }

      // Check if this user already sent request
      if($id1 == $thisUserId)
      {
        return 2;
      }

      return 3;
    }
    catch (Exception $e)
    {
      $this->setError($e->getMessage());
    }
  }// function getFriendshipStatus

  /**
  * Function getPercentageWith($otherUser)
  *
  * Returns the percetange match between this user and $otherUser
  *
  * @param - $otherUser(User), the other user for percentage
  * @return - $percentage(int), the percentage rounded
  */
  public function getPercentageWith($otherUser)
  {
    $con = $this->con;

    // Localise stuff
    $thisUserId = $this->id;
    $otherUserId = $otherUser->getCredential('id');
    $city = $this->getCredential('city');

    // Get the percentage from db
    $stmt = $con->prepare("SELECT percentage FROM rpercentages
                            WHERE (percentage_user_id1=$thisUserId AND percentage_user_id2=$otherUserId)
                              OR  (percentage_user_id1=$otherUserId AND percentage_user_id2=$thisUserId)
                              AND percentage_city=$city");
    try
    {
      if(!$stmt-> execute())
      {
        throw new Exception("Error getting percentage from database", 1);
      }
      $stmt->bindColumn(1, $percentage);
      $stmt->fetch();

      if(!$stmt->rowCount())
      {
        throw new Exception("Error. No percentage in database", 1);
      }
      return $percentage;
    }
    catch (Exception $e)
    {
      $this->setError($this->getMessage());
    }
  }

  // Helper method to get the notif messages template
  private function getNotifMessagesTemplate()
  {
    return array(
      "<li class='drop-item'><a href='/messages/",
      "' class='drop-item-link ",
      "'><span class='drop-item-pic' style='background-image: url(",
      ")'></span><h3 class='drop-item-header'>",
      " ",
      "</h3><p class='drop-item-text ",
      "'>",
      "</p><p class='drop-item-footer' title='",
      "'>",
      "</p></a></li>"
    );
  }

  // Helper method to get notif messages content
  private function getNotifMessagesContent($offset)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Get the arrays for the messages
    $return = $this->getConv($offset);
    $apparitionArray = isset($return['apparitionArray'])?$return['apparitionArray']:array();
    $messagePartners = (isset($return['messagePartners']))?$return['messagePartners']:array();
    $unreadArray = isset($return['unreadArray'])?$return['unreadArray']:array();

    // $messages = '';
    $messages = array();

    $todayDateTime = new DateTime();

    $regexTime = '/^(([0-9]{4})\-([0-9]{2})\-([0-9]{2})) ([0-9]{2}:[0-9]{2}):[0-9]{2}$/';
    preg_match($regexTime, date('Y-m-d H:i:s'), $todaysDateMatches);
    preg_match($regexTime, date('Y-m-d H:i:s', time() - 3600*24), $yesterdaysDateMatches);

    // For every message partner (each conv)
    for($index=0; $index<count($messagePartners) && $index<10; $index++)
    {
      $id2 = $messagePartners[$index];
      $stmt = $con->prepare("SELECT message_user_id1, message_text, message_timestamp
                                    message_group FROM rmessages
                              WHERE (message_user_id1 = $id2 AND message_user_id2 = $userId)
                                OR (message_user_id1 = $userId AND message_user_id2 = $id2)
                              ORDER BY message_id DESC
                              LIMIT 1");
      $stmt->execute();
      $stmt->bindColumn(1, $senderId);
      $stmt->bindColumn(2, $text);
      $stmt->bindColumn(3, $timestamp);
      $stmt->bindColumn(4, $group);
      $stmt->fetch();

      if(!$stmt->rowCount())
      {
        //$messages .= "";
        $message = array();
        continue;
      }
      // Get the number of unread messages from this user
      $noNewMessages = (isset($unreadArray[$id2]) && $unreadArray[$id2])?"({$unreadArray[$id2]})":"";
      $addReadClass = ($noNewMessages)?"read":"";

      // If the message was sent, add "sent" to the message class
      $sentClass = $userId == $senderId ? ' drop-item-text-sent ' : '';

      // Get name
      $otherUser = new OtherUser($con, $group ? $senderId : $id2);
      $otherUserName = $otherUser->getName($this->friendshipStatus($otherUser));
      $otherUserUsername = $otherUser->getCredential('username');
      $otherUserImagePath = $otherUser->getCredential('image');

      $firstLine = explode("<br>", $text)[0];

      $msgDateTime = date_create_from_format('Y-m-d H:i:s', $timestamp);
      $diff = $todayDateTime->diff($msgDateTime);
      $diff = (int) $diff->format('%a');
      // If today, output the time and "Today"
      if ($diff == 0)
      {
        $msgDateTimeTitle = 'Today';
        $msgDateTimeText = $msgDateTime->format('H:i');
      }
      // Else, if yesterday, output "yesterday"
      else if ($diff == 1)
      {
        $msgDateTimeTitle = 'Yesterday';
        $msgDateTimeText = 'Yesterday';
      }
      // Else, if within the last 6 days, output the day name
      else if ($diff < 6)
      {
        $msgDateTimeTitle = $msgDateTime->format('l');
        $msgDateTimeText = $msgDateTime->format('D');
      }
      // Else, if the year is still the same, output the date (e.g. 12 February)
      else if ($msgDateTime->format('Y') == $todayDateTime->format('Y'))
      {
        $msgDateTimeTitle = $msgDateTime->format('j F');
        $msgDateTimeText = $msgDateTimeTitle;
      }
      // Else output the date as DD/MM/YYYY
      else
      {
        $msgDateTimeTitle = $msgDateTime->format('d/m/Y');
        $msgDateTimeText = $msgDateTimeTitle;
      }

      /*
      $messages .=
      "
      <li class='drop-item'>
        <a href='/messages/$otherUserUsername' class='drop-item-link $addReadClass'>
          <span class='drop-item-pic' style='background-image: url(/media/img/anonymous.jpg)'></span>
          <h3 class='drop-item-header'>$otherUserName $noNewMessages</h3>
          <p class='drop-item-text $sentClass'>$firstLine</p>
          <p class='drop-item-footer' title='$msgDateTimeTitle'>$msgDateTimeText</p>
        </a>
      </li>
      ";
      */
      $message = array($otherUserUsername,
                       $addReadClass,
                       $otherUserImagePath,
                       $otherUserName,
                       $noNewMessages,
                       $sentClass,
                       $msgDateTimeTitle
                      );
      array_push($messages, $message);
    }

    return $messages;
  }// function getNotifMessages

// Helper function to get the conversations
private function getConv($offset)
{
      // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // The limit used for getting the conversations
    $limit = $offset + 10;

    // The users that this user has a conversation with
    $messagePartners = array();
    // The array that remembers how many messages we have from a user
    // aka the number of apparitions of a certain user id in our select
    $apparitionArray = array();
    // The array that remembers how many unread messages we have from a user
    $unreadArray = array();


    $stmt = $con->prepare("SELECT message_user_id1, message_user_id2, messages_read FROM rmessages
                            WHERE message_user_id2 = $userId
                              OR message_user_id1 = $userId
                            ORDER BY message_id DESC");
    $stmt->execute();
    $stmt->bindColumn(1, $id1);
    $stmt->bindColumn(2, $id2);
    $stmt->bindColumn(3, $read);

    // Check if we have any message at all
    if(!$stmt->rowCount())
    {
      return "";
    }

    while ($stmt->fetch())
    {
      if($id1 == $userId)
      {
        if(!in_array($id2, $messagePartners))
        {
          array_push($messagePartners, $id2);
          $apparitionArray[$id2] = 1;        }
        else
        {
          $apparitionArray[$id2] ++;
        }
        // Wait 'till we reach 10 conversations
        if(count($messagePartners) == $limit)
        {
          break;
        }
      }
      else
      {
        if(!in_array($id1, $messagePartners))
        {
          array_push($messagePartners, $id1);
          $apparitionArray[$id1] = 1;
          $unreadArray[$id1] = 0;
        }
        else
        {
          $apparitionArray[$id1] ++;
        }
        // Count if the message is unread
        ($read)?:$unreadArray[$id1]++;
        // Wait 'till we reach 10 conversations
        if(count($messagePartners) == $limit)
        {
          break;
        }
      }
    }

    $return['messagePartners'] = $messagePartners;
    $return['unreadArray'] = $unreadArray;
    $return['apparitionArray'] = $apparitionArray;

    return $return;
  } // getConv method

  // Helper method to get friend requests
  private function getNotifRequestsContent($offset)
  {
    // Localise id
    $id = $this->id;
    $con = $this->con;

    // Get the friends requests
    $stmt = $con->prepare("SELECT conexion_user_id1 FROM rconexions WHERE conexion_user_id2 = '$id' AND conexion_status = '2'
                          LIMIT 10 OFFSET $offset");
    $stmt->execute();
    $stmt->bindColumn(1, $otherUserId);

    // Initialise array
    $friendRequests = array();

    while($stmt->fetch())
    {
      $otherUser = new OtherUser($con, $otherUserId);
      $otherUserName = $otherUser->getName();
      $otherUsername = $otherUser->getCredential('username');
      $percentage = $this->getPercentageWith($otherUser);

      $request = array($otherUserId,
                       $otherUserUsername,
                       $otherUserImagePath,
                       $otherUserId,
                       $otherUserId,
                       $otherUserId,
                       $otherUserId,
                       $otherUserUsername,
                       $otherUserName,
                       160-$percentage*160,
                       160*$percentage
                      );

      // Push in the array
      array_push($friendRequests, $request);
      /*
      echo
      "
      <li class='drop-item' id='drop-item-fr-$otherUserId'>
        <div class='drop-item-box'>
          <a class='drop-item-pic' href='/profile/?u=$otherUsername' style='background-image: url(/media/img/anonymous.jpg)'></a>
          <h3 class='drop-item-header'>
            <div class='drop-item-header-right'>
              <a data-ajax-url='$webRoot/php/friends.process.php?a=3&amp;id=$otherUserId'
                 data-ajax-text='Accepting...'
                 data-ajax-callback='deleteById drop-item-fr-$otherUserId'
                 class='link-button button2'>Accept</a>
              <a data-ajax-url='$webRoot/php/friends.process.php?a=0&amp;id=$otherUserId'
                 data-ajax-text='Ignoring...'
                 data-ajax-callback='deleteById drop-item-fr-$otherUserId'
                 class='link-button button2'>Ignore</a>
            </div>
            <a href='/profile/$otherUsername' class='link'>$otherUsername</a>
          </h3>
          <p class='drop-item-footer'></p>
          <p class='drop-item-text' style='color:rgba(".(255-255*$percentage/100).",".(255*$percentage/100).",0,1)'>$percentage%</p>
        </div>
      </li>
      ";
      */
    }// while
    return $friendRequests;
  }// function getNotifRequestsContent()

  // Helper function to get notif requests template
  private function getNotifRequestsTemplate()
  {
    $requestsTemplate = array("<li class='drop-item' id='drop-item-fr-",
                              "'><div class='drop-item-box'><a class='drop-item-pic' href='/profile/?u=",
                              "' style='background-image: url(",
                              ")'></a><h3 class='drop-item-header'><div class='drop-item-header-right'><a data-ajax-url='../php/friends.process.php?a=3&amp;id=",
                              "' data-ajax-text='Accepting...' data-ajax-callback-1='deleteById drop-item-fr-",
                              "' class='link-button button2'>Accept</a><a data-ajax-url='../php/friends.process.php?a=0&amp;id=",
                              "' data-ajax-text='Ignoring...' data-ajax-callback-1='deleteById drop-item-fr-",
                              "' class='link-button button2'>Ignore</a></div><a href='/profile/",
                              "' class='link'>",
                              "</a></h3><p class='drop-item-footer'></p><p class='drop-item-text' style='color:rgba(",
                              ",",
                              ",0,1)'>",
                              "%</p></div></li>"
                              );
  }


  /**
  * Function getNotifications()
  *
  * Returns all notifications as json
  *
  * @param - $offset(int), the offset, used for ajax loading multiple
  * @return - $notifications(JSON), the json with notifications
  */
  public function getNotifications($offset=0)
  {
    return json_encode(array(
      'messages' => array(
        'template' => $this->getNotifMessagesTemplate(),
        'content'  => $this->getNotifMessagesContent($offset)
      ),
      'friend_requests' => array(
        'template' => $this->getNotifRequestsTemplate(),
        'content'  => $this->getNotifRequestsContent($offset)
      )
    ));
  }

  /**
  * Function getAllConversationsJSON()
  *
  * Returns a JSON of conversations
  *
  * @return - $conversations(string), contain conversations
  */
  public function getAllConversationsJSON($offset)
  {
    // Localise con
    $con = $this->con;

    $return = $this->getConv($offset);
    $apparitionArray = isset($return['apparitionArray'])?$return['apparitionArray']:array();
    $messagePartners = (isset($return['messagePartners']))?$return['messagePartners']:array();
    $unreadArray = isset($return['unreadArray'])?$return['unreadArray']:array();

    $noOfMessagePartners = count($messagePartners);

    $conversations = "{\"template\": [\"<li class='li'><p><a href='/messages/\",
                                  \"'>\",
                                  \"</a></p></li>\"
                                 ],
                       \"length\": $noOfMessagePartners";

    foreach ($messagePartners as $key => $otherUserId)
    {
      $otherUser = new OtherUser($con, $otherUserId);
      $otherUserName = $otherUser->getName();
      $noNewMessages = (isset($unreadArray[$otherUserId]) && $unreadArray[$otherUserId])?"({$unreadArray[$otherUserId]})":"";

      $conversations .=", \"$key\": [\"$otherUserId\", \"$otherUserName $noNewMessages\"]";
    }
    $conversations .= "}";

    return $conversations;
  }


  /**
  * Function getAllConversations()
  *
  * Returns an string of conversations
  *
  * @param - $conversations(string), contain conversations
  */
  public function getAllConversations($offset)
  {
    // Localise con
    $con = $this->con;

    $return = $this->getConv($offset);
    $apparitionArray = isset($return['apparitionArray'])?$return['apparitionArray']:array();
    $messagePartners = (isset($return['messagePartners']))?$return['messagePartners']:array();
    $unreadArray = isset($return['unreadArray'])?$return['unreadArray']:array();

    $conversations = "";
    foreach ($messagePartners as $otherUserId)
    {
      $otherUser = new OtherUser($con, $otherUserId);
      $otherUserName = $otherUser->getName();
      $otherUserUsername = $otherUser->getCredential('username');
      $noNewMessages = (isset($unreadArray[$otherUserId]) && $unreadArray[$otherUserId])?"({$unreadArray[$otherUserId]})":"";

      $conversations .=
      "
      <li data-id='$otherUserId' class='li'>
        <p>
          <a href='/messages/$otherUserUsername'>$otherUserName $noNewMessages</a>
        </p>
      </li>
      ";
    }
    return $conversations;
  }

  /**
  * Function inGroup($groupId)
  *
  * Returns true if this user is in group $groupId
  *
  * @param - $groupId(int), the id of the group
  * @return - $inGroup(boolean), true if user in group, false otherwise
  */
  public function inGroup($groupId)
  {
    // Localise shit
    $con = $this->con;
    $id = $this->id;

    // Check if user in group
    $stmt = $con->prepare("SELECT * FROM ruser_groups WHERE group_group_id = $groupId AND group_user_id = $id");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting checking if user in group", 1);
      }
      return (boolean)$stmt->rowCount();
    }
    catch (Exception $e)
    {
      $this->$errorMsg = $e->getMessage();
    }
  }

  /**
  * Function sendReview($accId, $reviewText)
  *
  * Sends a review to the accommodation with given id
  *
  * @param - $accId(int), the Id of the accommodation
  *
  */
  public function sendReview($accId, $reviewText)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Prepare for the review;
    $params['author'] = $userId;
    $params['text'] = $reviewText;
    $params['accId'] = $accId;

    try
    {
      if($this->hasReviewed($accId))
      {
        throw new Exception("You already gave a review", 1);
      }
      $review = new Review($con, 'insert', $params);
      if($review->getError())
      {
        throw new Exception("Error in submitting the review: " . $review->getError(), 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg .= $e->getMessage();
    }
  }

  /**
  * Function hasReviewed($accId)
  *
  * Returns true if user has reviewed this accommodation, false else
  *
  * @param - $accId(int), the id of the accom
  * @return - $value, true if this user has reviewed
  */
  public function hasReviewed($accId)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Check if user has reviewed
    $stmt = $con->prepare("SELECT post_id FROM rposts WHERE post_parent_id = $accId AND post_author = $userId AND post_type = " . Review::TYPE);
    if(!$stmt->execute())
    {
      throw new Exception("Error in database query when trying to get reviews for $accId", 1);
    }
    return ($stmt->rowCount()) ? true : false;
  }

  /**
  * Function sendReview($accId, $reviewText)
  *
  * Sends a review to the accommodation with given id
  *
  * @param - $accId(int), the Id of the accommodation
  *
  */
  public function sendReply($reviewId, $replyText)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Prepare for the review;
    $params['author'] = $userId;
    $params['text'] = $replyText;
    $params['reviewId'] = $reviewId;

    try
    {
      $reply= new Reply($con, 'insert', $params);
      if($review->getError())
      {
        throw new Exception("Error in submitting the reply: " . $reply->getError(), 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg .= $e->getMessage();
    }
  }

  /**
  * Function like($postId, $postType, $likeValue)
  *
  * Likes or dislikes a post, depending on the like value
  *
  * @param - $postId(int), the post to be liked/disliked
  * @param - $postType(String), the post type (currently review/reply)
  * @param - $likeValue(Boolean), if true like, if false dislike
  */
  public function like($postId, $postType, $likeValue)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    try
    {
      switch ($postType) {
        case Review::TYPE:
          $params['id'] = $postId;
          $review = new Review($con, 'get', $params);
          if($review->getError())
          {
            throw new Exception("Chthulu is coming. Error getting the review with id $postId when trying to like: " . $review->getError(), 1);
          }
          $review->like($userId, $likeValue);
          if($review->getError())
          {
            throw new Exception("OMFG. Error liking post $postId: " . $review->getError(), 1);
          }
          break;
        case Reply::TYPE:
          $params['reply_id'] = $postId;
          $reply = new Reply($con, 'get', $params);
          if($review->getError())
          {
            throw new Exception("Mneeah. Error getting the reply with id $postId when trying to like: " . $review->getError(), 1);
          }
          $reply->like($userId, $likeValue);
          if($review->getError())
          {
            throw new Exception("meh. Error liking post $postId: " . $review->getError(), 1);
          }
        default:
          throw new Exception("You dun sumting wrung", 1);
          break;
      }// switch
    }// try
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function like

  /**
  * Function deletePost($postId)
  *
  * Deletes the given post, if is the author of this post
  *
  * @param - $postId(int), the id of post to be deleted
  */
  public function deletePost($postId)
  {
    // Localise stuff
    $userId = $this->id;
    $con = $this->con;

    try
    {
      if(!$this->isAuthorOf($postId))
      {
        throw new Exception("Sneaky. Trying to delete other users' posts and shit", 1);
      }
      $stmt = $con->prepare("DELETE FROM rposts WHERE post_id = $postId");
      if(!$stmt->execute())
      {
        throw new Exception("Weirid sheet happening in the db. Meh", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }

  /**
  * Function isAuthorOf($postId)
  *
  * Returns true if this user is the author of post with $postId
  *
  * @param - $postId(int), the id of the post to be checekd
  * @return - $value(boolean), true if this user is author of the post
  */
  public function isAuthorOf($postId)
  {
    // Localise shit
    $userId = $this->id;
    $con = $this->con;

    try
    {
      // Check the posts table
      $stmt = $con->prepare("SELECT post_id FROM rposts WHERE post_author = $userId AND post_id = $postId");
      if(!$stmt->execute())
      {
        throw new Exception("Your query is messed up while trying to get check if user $userId is author of $postId", 1);
      }
      return $stmt->rowCount() ? true : false;
    }
    catch (Exception $e)
    {
      $this->errorMsg = "Error with checking if this user is author of $postId: " . $e->getMessage();
    }
  }
  /**
  * Function updatePost($postId, $text)
  *
  * Updates the given post
  *
  * @param - $postId(int), the post to be updated
  * @param - $text(String), the text to update with
  */
  public function updatePost($postId, $text)
  {
    // Localise shit
    $userId = $this->id;
    $con = $this->con;

    try
    {
      // Check if user owns post
      if(!isAuthorOf($postId))
      {
        throw new Exception("Stop trying to update others' posts", 1);
      }
      // Update table
      $stmt = $con->prepare("UPDATE rposts SET post_text = $text WHERE post_id = $postId");
      if(!$stmt->execute())
      {
        throw new Exception("Something wron with updating your post", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function updatePost

  /**
  * Function hasConnected($key)
  *
  * Returns true if this user has connected their account with $key
  * 
  * @param - $key(String), the website to check if user has connected with
  * @return - $value(boolean), true if user is connected with the website
  */
  public function hasConnected($key)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Check if $key_id in table
    $stmt = $con->prepare("SELECT $key" . '_id' . " FROM rusers WHERE user_id = $userId");

    try
    {

      if(!$stmt->execute())
      {
        throw new Exception("Error getting the $key id", 1);
      }
      $stmt->bindColumn(1, $fbId);
      $stmt->fetch();
      return $fbId ? true : false;
    }
    catch(Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function hasConnected

  /**
  * Function disconnect($key)
  *
  * Disconnects user from a likned site
  *
  * @param - $key(String), the site to disconnect from
  * @return - $response(boolean), true if succeeded
  */
  public function disconnect($key)
  {
    // Localise shit
    $con = $this->con;
    $userId = $this->id;

    // Delete thingie from table
    $stmt = $con->prepare("UPDATE rusers SET $key" . '_id' . "='' WHERE user_id = $userId");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error updating $key id in users table", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// fnction disonnect
  /**
  * @param -$accomId 'This represents the accommodation the user is rating'
  * @param -$starRating 'This is the value (1-5) that the user is giving to this accommodation'
  */
  public function rateAccommodation($accId, $starRating)
  {
    // Localise stuff
    $con = $this->con;
    $userId = $this->id;

    // Create the accom
    $accom = new Accommodation($con, 'get', array('id'=>$accId));
    try
    {
      // Get and update ratings array
      $ratingsArray = $accom->getRatingsArray();
      if($accom->getError())
      {
        throw new Exception("Sumthing wrung wid accommodation $accId: " . $accom->getError(), 1);
      }
      // Calculate the rating of this user
      $rating = ((double)$starRating * 100.0) / 5.0;
      if(!in_array($userId, $ratingsArray[0]))
      {
        array_push($ratingsArray[0], $userId);
        array_push($ratingsArray[1], $rating);
      }
      else
      {
        foreach ($ratingsArray[0] as $key => $value)
        {
          if($ratingsArray[0][$key] == $userId)
          {
            $ratingsArray[1][$key] = $rating;
            break;
          }
        }
      }// else

      // Calculate the new average rating
      $sum = 0.0;
      foreach ($ratingsArray[1] as $rat)
      {
        $sum += $rat;
      }
      $newRating = $sum ? $sum / ((double)count($ratingsArray[1])-1) : 0;
      // Update ratings
      $accom->setRatings($ratingsArray, $newRating);
      // Check for errors
      if($accom->getError())
      {
        throw new Exception("Error updating array of ratings: " . $accom->getError(), 1);
      }
    }// try
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }// function rateAccommodation

  /**
   * Function to return an array of the user's friends (or return an array of credentials)
   */
  public function getFriends($credential = FALSE)
  {
    $con = $this->con;
    $userId = $this->id;

    $stmt = $con->prepare("SELECT conexion_user_id1, conexion_user_id2
                                   FROM rconexions
                                  WHERE (   conexion_user_id1 = '$userId'
                                         OR conexion_user_id2 = '$userId')
                                    AND conexion_status = 1");
    $stmt->execute();
    $stmt->bindColumn(1, $id1);
    $stmt->bindColumn(2, $id2);

    $friends = array();
    while ($conexion = $stmt->fetch())
    {
      $friend = new OtherUser($con, $id1 == $userId ? $id2 : $id1);
      if (!$friend->getError())
        if ($credential && $friend->getCredential($credential) !== 'Wrong key')
          array_push($friends, $friend->getCredential($credential));
        else
          array_push($friends, $friend);
    }

    return $friends;
  }


  /**
  * Function getUnreadCount()
  *
  * Gets the number of unread conversations
  *
  * @return - $count(int), the number of unread conversations
  */
  public function getUnreadCount()
  {
    $count = 0;

    $conv = $this->getConv(0);
    if ($conv != ''  && isset($conv['unreadArray']))
    {
      $unreadArray = $this->getConv(0)['unreadArray'];
      foreach ($unreadArray as $unread)
      {
        if ($unread > 0)
        {
          $count++;
        }
      }
    }

    return $count;
  }

}// class CurrentUser


?>