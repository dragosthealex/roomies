<?php
/**
* Base class
*
* Abstract class containing base methods and variables
*
*/
abstract class Base
{
  // The error message
  protected $errorMsg = false;
  // The id
  protected $id;
  // The connection handler
  protected $con;
  
  // Sets the error to a $message
  protected function setError($message)
  {
    $this->errorMsg = $message;
  }

  /**
  * function getError()
  *
  * Returns the either false, if there is no error, or the error message
  *
  * @return - $errorMsg(mixed), the error msg or "false"
  */
  public function getError()
  {
    return $this->errorMsg;
  }
}// class Base
?>