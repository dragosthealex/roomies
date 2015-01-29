<?php
/*
This script processes the data sent from the complete register page.
It updates creates a profile for the current user and inserts the values.
*/
require_once "../../inc/init.php";
if(!LOGGED_IN)
{
  require_once '../../inc/html/notfound.php';
  exit();
}

$id = $_SESSION['user']['id'];
$stmt = $con->prepare("SELECT profile_filter_id FROM rdetails WHERE profile_filter_id = $id");
$stmt->execute();
$stmt->bindColumn(1, $dbId);
$stmt->fetch();

// We check if all values are set. Most of these values are safe,
// because are coming from selects. We'll check the names.
if((isset($_POST['firstName'],$_POST['lastName'],$_POST['bYear'],
         $_POST['bMonth'],$_POST['bDay'],$_POST['country'],
         $_POST['language'],$_POST['gender'],$_POST['randomKey'],
         $_POST['id'])) 
  && ($_SESSION['randomKey'] == $_POST['randomKey']) && (!$stmt->rowCount()))
{
  // Get the values from POST
  $firstName = htmlentities($_POST['firstName']);
  $lastName = htmlentities($_POST['lastName']);
  $bYear = $_POST['bYear'];
  $bMonth = $_POST['bMonth'];
  $bDay = $_POST['bDay'];
  $country = $_POST['country'];
  $language = $_POST['language'];
  $gender = $_POST['gender'];
  $id = $_POST['id'];

  // Check if the ID exists. If not, it must be a problem
  $stmt = $con->prepare("SELECT user_id FROM rusers WHERE user_id = $id");
  $stmt->execute();
  $stmt->bindColumn(1, $dbId);
  $stmt->fetch();
  if(!$stmt->rowCount())
  {
    // There was a problem
    require_once __ROOT__."/inc/html/problem.php";
    exit();
  }

  // Format the birthday
  if($bDay < 10)
  {
    $bDay = "0".$bDay;
  }
  if($bMonth < 10)
  {
    $bMonth = "0".$bMonth;
  }
  $birthday = $bYear."-".$bMonth."-".$bDay;

  // Get the values in ints from mapping
  $stmt = $con->prepare("SELECT filter_value FROM rfiltersmap WHERE map_country = '$country'");
  $stmt->execute();
  $stmt->bindColumn(1, $mapCountry);
  $stmt->fetch();
  $stmt = $con->prepare("SELECT filter_value FROM rfiltersmap WHERE map_language = '$language'");
  $stmt->execute();
  $stmt->bindColumn(1, $mapLanguage);
  $stmt->fetch();
  $stmt = $con->prepare("SELECT filter_value FROM rfiltersmap WHERE map_gender = '$gender'");
  $stmt->execute();
  $stmt->bindColumn(1, $mapGender);
  $stmt->fetch();

  // Insert those values in rdetails
  $stmt = $con->prepare("INSERT INTO rdetails (profile_filter_id, first_name, last_name, birthday, country, language, gender)
                          VALUES ($id, '$firstName', '$lastName', '$birthday', '$mapCountry', '$mapLanguage', '$mapGender')");
  $stmt->execute();

  $stmt = null;
  unset($_SESSION['notComplete']);
  exit();
}
else
{
  echo "done";
  exit();
}