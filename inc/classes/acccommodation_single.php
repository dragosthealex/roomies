<?php
/**
* Class Accommodation
*
* Represents an accommodation instance
*
*/
class Accommodation
{
  // The id
  private $id;
  // The name
  private $name;
  // The id of the author
  private $author;
  // The date of the post
  private $date;
  // The array of photo ids
  private $photos;
  // The id of main photo
  private $mainPhoto;
  // The rating, in points out of 5. The star rating is calculated based on this, rounding to .5
  private $rating;
  // The reviews, as an array of Review objects
  private $reviews;
  // The db connection handler
  private $con;

  /**
  * Constructor
  *
  * Constructs an Accommodation object. If $action is 'get', its $params['id'] should be an id, and it will get an acc. from db
  * If $action is 'insert', it will have $params['name'], ['author'], ['mainPhoto'], ['photos']
  *
  */
  public function __construct($con, $action, $params)
  {
    switch ($action) {
      case 'insert':
        // Set the properties
        $name = isset($params['name'])?htmlentities($params['name']):'';
        $author = isset($params['author'])?htmlentities($params['author']):'';
        $mainPhoto = isset($params['mainPhoto'])?htmlentities($params['mainPhoto']):'';
        $phtos = isset($params['phtos'])?htmlentities($params['photos']):'';
        $date = date('d-m-Y');

        try
        {
          if (!$name || !$author || !$mainPhoto)
          {
            throw new Exception("Values for name, author and mainPhoto must not be null", 1);
          }
          $stmt = $con->prepare("INSERT INTO raccommodations (accommodation_name, accommodation_author, accommodation_date,
                                  accommodation_main_photo, accommodation_photos)
                                 VALUES ('$name', '$author', '$date', '$mainPhoto', '$photos')");
          if(!$stmt->execute())
          {
            throw new Exception("Error Inserting into database", 1);            
          }

          // Set the instance variables
          $this->name = $name;
          $this->author = $author;
          $this->mainPhoto = $mainPhoto;
          $this->photos = $photos;
          $this->date = $date;
          $this->con = $con;
        }
        catch (Exception $e)
        {
          $this->errorMsg = $e->getMessage();
        }
        break;
      case 'get':

      default:
        # code...
        break;
    }
  }
}



?>