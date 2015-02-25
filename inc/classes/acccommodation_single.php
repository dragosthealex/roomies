<?php
// Needs function photosUpload
require_once __ROOT__."/inc/functions/photosUpload.php";
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
  // The number of photos
  private $noPhotos;
  // The rating, in points out of 5. The star rating is calculated based on this, rounding to .5
  private $rating;
  // The reviews, as an array of Review objects
  private $reviews;
  // The description, text
  private $description;
  // The db connection handler
  private $con;
  // Errors
  private $errorMsg = '';

  /**
  * Constructor
  *
  * Constructs an Accommodation object. If $action is 'get', its $params['id'] should be an id, and it will get an acc. from db
  * If $action is 'insert', it will have $params['name'], ['author'], ['noPhotos'], ['description']
  *
  */
  public function __construct($con, $action, $params)
  {
    switch ($action) {
      case 'insert':
        // Set the properties
        $name = isset($params['name'])?htmlentities($params['name']):'';
        $author = isset($params['author'])?htmlentities($params['author']):'';
        $date = date('d-m-Y');
        $noPhotos = isset($params['noPhotos'])?htmlentities($params['noPhotos']):0;
        $description = isset($params['description'])?htmlentities($params['description']):'';

        // Get the arrays, for upload method
        $mainPhoto = array("$mainPhoto");
        $photos = explode(':', $photos);
        // Get the number of total photos

        try
        {
          if (!$name || !$author || !$mainPhoto)
          {
            throw new Exception("Values for name, author and mainPhoto must not be null", 1);
          }
          $stmt = $con->prepare("INSERT INTO raccommodations (accommodation_name, accommodation_author, accommodation_date,
                                  accommodation_no_photos, accommodation_description)
                                 VALUES ('$name', '$author', '$date', '$noPhotos', '$description')");
          if(!$stmt->execute())
          {
            throw new Exception("Error Inserting into database", 1);            
          }

          // Get the id
          $accId = $con->lastInsertId('accommodation_id');

          // Upload the main photo (0 = upload in /media/img/accommodation, 'ac...' = name of input, $accId = base name of file)
          if(!photoUpload(0, 'accommodation-main', $accId))
          {
            throw new Exception("Error Uploading the main photo", 1);
          }
          // Upload the rest of the photos
          if(!photoUpload(0, 'accommodation-sec', $accId))
          {
            throw new Exception("Error Uploading the rest of the photos", 1);
          }

          // Set the instance variables
          $this->name = $name;
          $this->author = $author;
          $this->noOfPhotos = $noPhotos;
          $this->date = $date;
          $this->con = $con;
          $this->description = $description;
        }
        catch (Exception $e)
        {
          $this->errorMsg = $e->getMessage();
        }
        break;
      case 'get':
        // Validate the id
        $accommodationId = isset($params['id'])?htmlentities($params['id']):'';

        // Get the details from db
        try
        {
          $stmt = $con->prepare("SELECT * FROM raccommodations WHERE accommodation_id = $accommodationId");
          $stmt->execute();

          // Something wrong if no accommodation with given id
          if(!$stmt->rowCount())
          {
            throw new Exception("No accommodation with this id", 1);
          }
          // Fetch the result
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          // Set the instance vars
          $this->id = $accommodationId;
          $this->noOfPhotos = $result['accommodation_no_photos'];
          $this->date = $result['accommodation_date'];
          $this->author = $result['accommodation_author'];
          $this->name = $result['accommodation_name'];
          $this->con = $con;
          $this->description = $result['accommodation_description'];
          $this->rating = $result['accommodation_rating'];
        }
        catch (Exception $e)
        {
          $this->errorMsg = $e->getMessage();
        }

      default:
        $this->errorMsg = "Weird input";
        break;
    }
  }

  /**
  * Returns an accommodation as json
  *
  * @return - $json, a JSON object containing the details of this accommodation
  *
  */
  public function toJson()
  {
    // Localise stuff
    $id = $this->id;
    $description = $this->description;
    $date = $this->date;
    $reviews = $this->reviews;
    $rating = $this->rating;
    $name = $this->name;
    $authorId = $this->author;
    $noOfPhotos = $this->noOfPhotos;

    // Get the name of the author
    $author = new User($authorId);
    $authorName = $author->getName();

    // Create the JSON
    $json = "{\"id\"          : \"$id\",
              \"author\"      : \"$authorName\",
              \"description\" : \"$description\",
              \"rating\"      : \"$rating\",
              \"noOfPhotos\"  : \"$noOfPhotos\",
              \"name\"        : \"$name\",
              \"reviews\"     : [
            ";
    foreach ($reviews as $key => $review)
    {
      $review = $review->toJson();
      $json .= "\"$review,\"";
    }

    // Include an empty review and close the array, because we have the comma afther the last included review
    $json .= "\"\"]";
    // Close json
    $json .= "}";

    return $json;
  }

  /**
  * Function getReviews
  *
  * Sets reviews for this accommodation, and sets them from the database
  *
  */
  public function setReviwes()
  {
    // Localise stuff
    $con = $this->con;
    $accId = $this->id;

    // Get the review
    $stmt = $con->prepare("SELECT review_id FROM rreviews WHERE review_acc_id = '$accId'");
    try
    {
      if(!$stmt->execute())
      {
        throw new Exception("Database execution failed", 1); 
      }

      // Initialise as empty array
      $reviews = array();

      // Loop through review ids, creating a new review object
      $stmt->bindColumn(1, $reviewId);
      while($stmt->fetch())
      {
        $params['id'] = $reviewId;
        $review = new Review($con, 'get', $params);
        // Skip if we have errors
        if($review->getError())
        {
          continue;
        }
        array_push($reviews, $review);
      }

      // Set the instance var
      $this->reviews = $reviews;
    }
    catch (Exception $e)
    {
      $this->errorMsg .= $e->getMessage();
    }
  }

  /**
  * Function getError()
  *
  * Returns $errorMsg, containing any errors
  *
  * @return - $errorMsg(String), the message of error
  */
  public function getError()
  {
    return $this->errorMsg();
  }
}



?>