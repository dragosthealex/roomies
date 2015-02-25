<?php
/**
* Reply class
*
* Represents a reply, to a user. Extends the comment, because it hase the same things, the parent being a review (possbile extension for this)
*/
require_once __ROOT__.'/inc/classes/comment.php';

class Reply extends Comment
{
  /**
  * Constructor
  *
  * Constructs a Reply object. If $action is 'get', its $params['id'] should be an id, and it will get a reply from db
  * If $action is 'insert', it will have $params['author'], ['accId'], ['text']
  *
  */

}// class Reply


?>