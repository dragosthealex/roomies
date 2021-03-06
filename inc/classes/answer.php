<?php
/**
 * Answer class
 *
 * Represents an answer.
 */
include_once 'Base.php';
class Answer extends Base
{
  // The text of the answer
  private $text;

  /**
   * Constructor
   *
   * Constructs a new answer, given the id. Gets the text from the db.
   *
   * @param $con The database connection handler
   * @param $id  The id of the answer
   */
  public function __construct($con, $id)
  {
    // Query the database for the answer with the given $id
    $stmt = $con->prepare("SELECT answer_text FROM ranswers WHERE answer_id = '$id'");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Error getting answer text from db, answer id: $id", 1);
        
      }
      // If the answer does not exist, throw an exception and return
      if (!$stmt->rowCount())
      {
        throw new Exception("Error: Invalid Answer Id: $id");
        return;
      }

      // Get the text of the answer
      $stmt->bindColumn(1, $text);
      $stmt->fetch();

      // Set the private variables
      $this->id = $id;
      $this->text = $text;
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
    }
  }

  /**
   * Gets the id of the answer.
   *
   * @return $id The id of the answer.
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Gets the text of the answer.
   *
   * @return $text The text of the answer.
   */
  public function getText()
  {
    return $this->text;
  }
}
?>