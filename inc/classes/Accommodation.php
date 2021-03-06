<?php
// Needs function photosUpload
require_once __ROOT__."/inc/functions/photosUpload.php";
/**
* Class Accommodation
*
* Represents an accommodation instance
*
*/
require_once __ROOT__.'/inc/classes/Base.php';
class Accommodation extends Base
{
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
  // Array containing ratings, of the form [[$id1, $id2, $id3], [$rat1, $rat2, $rat3]]. String version $id1,$id2,$id3:$rat1,$rat2,$rat3
  private $ratingsArray;
  // The price (per week)
  private $price;
  // The address
  private $address;
  // The city
  private $city;
  // The db thingies
  const TABLE_NAME = 'raccommodations';
  const ID_COLUMN = 'accommodation_id';
  const FOREIGN_KEY_COLUMN = 'accommodation_author';
  const TYPE = 3;
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
        $date = date('Y-m-d');
        // $noPhotos = isset($params['noPhotos'])?htmlentities($params['noPhotos']):0;
        $noPhotos = 0;
        $description = isset($params['description'])?htmlentities($params['description']):'';
        $address = isset($params['address'])?htmlentities($params['address']):'';
        $price = isset($params['price'])?htmlentities($params['price']):'';
        $city = isset($params['city'])?htmlentities($params['city']):'';
        // Get the arrays, for upload method
        $mainPhotoInput = isset($params['main_photo'])?htmlentities($params['main_photo']):'';
        $secPhotoInput = isset($params['sec_photos'])?htmlentities($params['sec_photos']):'';
        $photoUploadLocation = '../media/img/acc/';
        // Get the number of total photos

        try
        {
          if (!$name || !$author || !$mainPhotoInput)
          {
            //throw new Exception("Values for name, author and main photo must not be null", 1);
            throw new Exception($name . " - " . $author . " - " . $mainPhotoInput, 1);
            
          }
          $stmt = $con->prepare("INSERT INTO raccommodations (accommodation_name, accommodation_author, accommodation_date,
                                  accommodation_no_photos, accommodation_description, accommodation_address, accommodation_price, accommodation_city)
                                 VALUES ('$name', '$author', '$date', '$noPhotos', '$description', '$address', '$price', '$city')");
          if(!$stmt->execute())
          {
            throw new Exception("Error Inserting into database", 1);
          }

          // Get the id
          $accId = $con->lastInsertId('accommodation_id');

          // Upload the main photo (first param = location, second param = input, third param = base name, fourth param = secondary/primary)
          $uploadError = photoUpload($photoUploadLocation, $mainPhotoInput, $accId, true);
          if($uploadError != 'ok')
          {
            throw new Exception("Error Uploading the main photo: $uploadError", 1);
          }
          // // Upload the rest of the photos
          // if($secPhotoInput && $_FILES[$secPhotoInput]["name"][0])
          // {
          //   $uploadError = photoUpload($photoUploadLocation, $secPhotoInput, $accId, false);
          // }
          // if($secPhotoInput && $uploadError != 'ok' && $_FILES[$secPhotoInput]["name"][0])
          // {
          //   throw new Exception("Error Uploading the rest of the photos: $uploadError", 1);
          // }

          // Set the instance variables
          $this->name = $name;
          $this->author = $author;
          $this->noOfPhotos = $noPhotos;
          $this->date = $date;
          $this->con = $con;
          $this->description = $description;
          $this->id = $accId;
          $this->address = $address;
          $this->price = $price;
          $this->city = $city;
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
          if(!$stmt->execute())
          {
            throw new Exception("Error getting the accommodation from db", 1);
            
          }

          // Something wrong if no accommodation with given id
          if(!$stmt->rowCount())
          {
            throw new Exception("No accommodation with this id (id = $accommodationId)", 1);
          }
          // Fetch the result
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          // Set the instance vars
          $this->id = $accommodationId;
          $this->noOfPhotos = isset($result['accommodation_no_photos'])?$result['accommodation_no_photos']:'';
          $this->date = isset($result['accommodation_date'])?$result['accommodation_date']:'';
          $this->author = isset($result['accommodation_author'])?$result['accommodation_author']:'';
          $this->name = isset($result['accommodation_name'])?$result['accommodation_name']:'';
          $this->address = isset($result['accommodation_address'])?$result['accommodation_address']:'';
          $this->price = isset($result['accommodation_price'])?$result['accommodation_price']:'';
          $this->city = isset($result['accommodation_city'])?$result['accommodation_city']:'';
          $this->con = $con;
          $this->description = isset($result['accommodation_description'])?preg_replace('/\r\n|\r|\n/', '<br>',$result['accommodation_description']):'';
          $this->rating = isset($result['accommodation_rating'])?$result['accommodation_rating']:'0';
          $this->ratingsArray = isset($result['accommodation_rating_array'])?$result['accommodation_rating_array']:'';
          $this->ratingsArray = $this->ratingsArray ? explode(':', $this->ratingsArray) : array("", "");
          $this->ratingsArray[0] = $this->ratingsArray ? explode(',', $this->ratingsArray[0]) : array();
          $this->ratingsArray[1] = $this->ratingsArray ? explode(',', $this->ratingsArray[1]) : array();
        }
        catch (Exception $e)
        {
          $this->errorMsg = $e->getMessage();
        }
        break;
      default:
        $this->errorMsg = "Weird input";
        break;
    }
  }

  /**
  * Function getRatingsArray()
  *
  * Returns this accommodation's ratings array
  *
  * @return - $ratingsArray(mixed array), the ratingsArray
  */
  public function getRatingsArray()
  {
    return $this->ratingsArray;
  }

  /**
  * Function setRatings($ratingsArray, $rating)
  *
  * Set new ratings array, and update the current rating
  *
  * @param - $ratingsArray(mixed array), the new ratings array
  * @param - $rating(double), the rating used to update
  */
  public function setRatings($ratingsArray, $rating)
  {
    // Localise stuff
    $con = $this->con;
    $accId = $this->id;

    // Update class vars
    $this->ratingsArray = $ratingsArray;
    $this->rating = $rating;

    // Make ratings string array
    $ratingsArray[1] = implode(',', $ratingsArray[1]);
    $ratingsArray[0] = implode(',', $ratingsArray[0]);
    $ratingsArray = implode(':',  $ratingsArray);

    try
    {
      // Update table
      $stmt = $con->prepare("UPDATE raccommodations SET accommodation_rating_array='$ratingsArray', accommodation_rating='$rating' WHERE accommodation_id = $accId");
      if(!$stmt->execute())
      {
        throw new Exception("Error updating rating in database", 1);
      }
    }
    catch (Exception $e)
    {
      $this->errorMsg = $e->getMessage();
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
    // Set the reviews if not already
    if (!isset($this->reviews[0]))
    {
      $this->setReviews();
    }
    if($this->getError())
    {
      throw new Exception("Problem setting reviews for accom " . $this->id . ": " . $this->getError(), 1);
    }

    // Localise stuff
    $id = $this->id;
    $description = $this->description;
    $date = $this->date;
    $reviews = ($this->reviews)?$this->reviews:array();
    $rating = $this->rating;
    $name = $this->name;
    $authorId = $this->author;
    $noOfPhotos = $this->noOfPhotos;
    $con = $this->con;
    $ratingsArray = $this->ratingsArray;

    try
    {
      // Get the name of the author
      $author = new Owner($con, 'get', $authorId);
      $authorName = $author->getName();

      // get the reviews
      $reviewsJson = array();
      foreach ($reviews as $review)
      {
        $reviewArray = json_decode($review->toJson(), 1);
        if($review->getError())
        {
          $this->errorMsg .= "Erorr with reveiws: " . $review->getError();
          echo $review->getError();
          continue;
        }
        array_push($reviewsJson, $reviewArray);
      }

      $jsonArray = array(
                "id"          => "$id",
                "authorName"  => "$authorName",
                "authorId"    => "$authorId",
                "description" => "$description",
                "rating"      => "$rating",
                "ratingsArray"=> $ratingsArray,
                "noOfPhotos"  => "$noOfPhotos",
                "name"        => "$name",
                "reviews"     => $reviewsJson);
      return json_encode($jsonArray);
    }
    catch (Exception $e)
    {
      $this->errorMsg = "Error with the accommodation $id: " . $e->getMessage();
    }
  }

  /**
  * Function setReviews
  *
  * Sets reviews for this accommodation, and sets them from the database
  *
  */
  public function setReviews()
  {
    // Localise stuff
    $con = $this->con;
    $accId = $this->id;

    // Get the review
    $stmt = $con->prepare("SELECT post_id FROM rposts WHERE post_parent_id = '$accId' AND post_type = " . Review::TYPE . " ORDER BY post_likes_no DESC");
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
}



?>