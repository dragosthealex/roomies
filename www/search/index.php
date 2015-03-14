<?php
// Initialise the page, requiring the user to be logged in
// define('REQUIRE_SESSION', true);
require_once '../../inc/init.php';
if (!LOGGED_IN) die();
// Temporary user array (supposed to get from /inc/init.php)
// Gonna just fill the values in as myself
$testUser = array(
  'name' =>    'Daniel Hodgson',
  'picture' => 'anonymous.jpg',
  'filters' => array(
    0 => 'English',
    1 => 'Atheism',
    2 => 'Unaffiliated',
    3 => 'Coffee',
    4 => false,
    // 6 => This person doesn't like or dislike trees.
  )
);

// Include the head of the page
$title = 'Search';
include __ROOT__.'/inc/html/head.php';

// Include the header of the page
// We don't need to use $ioStatus here, since it MUST be "in"
include __ROOT__.'/inc/html/header.in.php';

// TODO: Output the top of the search page, with the ignore form
?>
<div class="box">
  <form method="GET" class="box-padding">
    <div class="input-wrapper" style="z-index:999">
      <select class="select" name="gender">
        <option value="2">Female</option>
        <option value="1">Male</option>
        <option value="3">Other</option>
        <option value="0">All genders</option>
      </select>
      <div class="input">
        <span id="age-toggler" class="height-toggler"></span>
        <div class="height23">
          <div data-toggle="age-toggler">Ages 18 to 30</div>
          <div class="input-wrapper">
            <input class="input" style="width:50px" name="age1"> -
            <input class="input" style="width:50px" name="age2">
          </div>
        </div>
      </div>
    </div>
    <div class="input-wrapper">
      <input class="input-button" type="submit" value="Search" data-hide="Options">
      <input class="input-button cancel-button" type="button" value="Clear">
    </div>
  </form>
</div>
<?php

// // If the user is ignoring some filters, put them into $ignore
// if (isset($_POST['ignore']))
//     $ignore = $_POST['ignore'];
// else
//     $ignore = array();

// TODO: The search algorithm, using filters array from $_POST and putting the
// results for each user into an array, $results, with:
// $results[$i]['id']          (their user id)
// $results[$i]['name']          (their name or "anonymous")
// $results[$i]['picture']       (their picture or "anonymous.jpg")
// $results[$i]['compatibility'] (the percent compatibility from 0.0 to 100.0)
// $results[$i]['filters']       (an array of the filters)
// $results[$i]['filters'][$j]['id']    (the id of the filter, to know what question to put with it)
// $results[$i]['filters'][$j]['value'] (true/false: whether filter $j matched)s

// Output the result list
echo '<div class="box"><div class="box-padding"><ul class="ul">';
include_once __ROOT__.'/inc/html/search_results.php';
echo '</ul></div></div>';

// Include the footer of the page
include '../../inc/html/footer.php';
?>
