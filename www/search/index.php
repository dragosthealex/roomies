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
    <script>
function checkForm(element, targetStr) {
  var target = element;
  Array.prototype.forEach.call(targetStr, function (chr) {
    if (target) {
      if (chr==="^") {
        target=target.parentNode;
      } else if (chr==="<") {
        while ((target=target.previousSibling).nodeType!==1);
      } else if (chr===">") {
        while ((target=target.previousSibling).nodeType!==1);
      } else if (!isNaN(+chr)) {
        target=target.children[chr-1];
      }
    };
  });
  target && (target.innerHTML=element.value);
}
function reCheckForm(element, targetStr) {
  switch (element.name) {
    case "age1":
    case "age2":
      element.value=element.value.replace(/[^0-9]/, "");
      !(element.value>=18)&&(element.value=18);
  }
  checkForm(element, targetStr);
}
    </script>
    <h2 class="h2">Show me</h2>
    <div class="input-wrapper" style="z-index:2">
      <select class="select" name="gender">
        <option value="1">Men</option>
        <option value="2">Women</option>
        <option value="3">Trans</option>
        <option value="0">All genders</option>
      </select>
      <span id="age-toggler" class="selector-toggler"></span>
      <div class="selector">
        <div class="selector-text" data-toggle="age-toggler">Ages
          <span data-toggle="age-toggler">18</span> to
          <span data-toggle="age-toggler">30</span>
        </div>
        <div class="selector-content center">
          <input class="input" style="width:50px" name="age1" value="18" oninput="checkForm(this, '^<1')" onblur="reCheckForm(this, '^<1')"> -
          <input class="input" style="width:50px" name="age2" value="30" oninput="checkForm(this, '^<2')" onblur="reCheckForm(this, '^<2')">
        </div>
      </div>
      <select class="select" name="uni_city">
        <option value="1">In Manchester</option>
      </select>
      <select class="select" name="online_last">
        <option value="1">Online now!</option>
        <option value="2">Online in the last day</option>
        <option value="3">Online in the last week</option>
      </select>
      <div class="select hidden" name="country" id="filter_country">Cactus</div>
      <span id="advanced-toggler" class="selector-toggler"></span>
      <div class="selector">
        <div class="selector-text" data-toggle="advanced-toggler">Advanced</div>
        <div class="selector-content">
          <a href="#" class="link text block">Country</a>
          <a href="#" class="link text block">Language</a>
          <a href="#" class="link text block">Degree</a>
          <a href="#" class="link text block">Parties</a>
          <a href="#" class="link text block">Smokes</a>
          <a href="#" class="link text block">Drinks</a>
          <a href="#" class="link text block">Drugs</a>
          <a href="#" class="link text block">and</a>
          <a href="#" class="link text block">Shit</a>
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
