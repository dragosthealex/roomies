<?php
/**
* Class Owner
*
* Represents an owner of one or more accommodations (properties)
*
*/

class Owner extends GenericUser
{
  // The array of accommodations owned
  protected $accommodations;
  
  /**
  * Constructor 
  *
  * Constructs an owner. If $action is 'get', $params should contain id/username/email, and the respective owner will be created.
  * If $action is 'insert', a new owner will be inserted into table
  *
  * @param - $con(PDO), the db connection handler
  * @param - $action(String), can be 'get' or 'insert', depending on which the owner is got or inserted
  * @param - $params(mixed array), if $action is 'get', must have either ['id'], ['email'] or ['username']
  *                                if $action is 'insert', must have:
  *                                 ['username']
  *                                 ['email']
  *                                 ['password']
  *                                 ['first_name']
  *                                 ['last_name']
  *                                 ['']
  *
  *
  */
  function __construct($con, $action, $params)
  {
    
  }
}
?>