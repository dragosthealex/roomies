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
function printOption($array, $index, $checkAgainst)
{
  echo "<option value=$index";
  if ($index == $checkAgainst)
  {
    echo ' selected';
  }
  echo ">{$array[$index]}</option>";
}
?>
<div class="box">
  <form method="GET" class="box-padding" id="blobfish">
    <script>
function cF(element, targetStr) {
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
function rCF(element) {
  switch (element.name) {
    case "lowerAge":
    case "upperAge":
      element.value=element.value.replace(/[^0-9]/, "");
      !(element.value>=18)&&(element.value=18);
  }
  element.oninput();
}
    </script>
    <h2 class="h2">Show me</h2>
    <div class="input-wrapper filter-wrapper">
      <?php
      $genderChosen = isset($_GET['gender']) ? $_GET['gender'] : $user2->getCredential('gender');
      ?>
      <select class="select" name="gender" data-default="<?=$user2->getCredential('gender')?>">
        <?php
        $gender_options = array(
          'All genders'
        );
        $stmt = $con->prepare("SELECT map_gender FROM rfiltersmap");
        $stmt->execute();
        while ($genderBender = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          if (!$genderBender['map_gender']) break;
          array_push($gender_options, ucwords($genderBender['map_gender']));
        }
        for ($i=1;$i<count($gender_options);$i++)
        {
          printOption($gender_options, $i, $genderChosen);
        }
        printOption($gender_options, 0, $genderChosen);
        ?>
      </select>
      <span id="age-toggler" class="selector-toggler"></span>
      <?php
      $age = date_diff(date_create($user2->getCredential('birthday')), date_create('today'))->y;
      $lowerAge = $age - 2;
      if ($lowerAge < 18) $lowerAge = 18;
      $upperAge = $age + 2;
      ?>
      <div class="selector">
        <div class="selector-text" data-toggle="age-toggler">Ages
          <span data-toggle="age-toggler"><?=isset($_GET['lowerAge'])?$_GET['lowerAge']:$lowerAge?></span> to
          <span data-toggle="age-toggler"><?=isset($_GET['upperAge'])?$_GET['upperAge']:$upperAge?></span>
        </div>
        <div class="selector-content center"
          ><ul class="selector-padding">
            <li class="selector-item selector-inputs">
              <input class="input" style="width:50px" name="lowerAge" data-default="<?=$lowerAge?>" value="<?=isset($_GET['lowerAge'])?$_GET['lowerAge']:$lowerAge?>" oninput="cF(this,'^^^<1')" onblur="rCF(this)"> -
              <input class="input" style="width:50px" name="upperAge" data-default="<?=$upperAge?>" value="<?=isset($_GET['upperAge'])?$_GET['upperAge']:$upperAge?>" oninput="cF(this,'^^^<2')" onblur="rCF(this)">
            </li>
          </ul>
        </div>
      </div>
      <?php
      $selectedCity = isset($_GET['uni_city']) ? $_GET['uni_city'] : $user2->getCredential('uni_city');
      ?>
      <select class="select" name="uni_city" data-default="<?=$user2->getCredential('uni_city')?>">
        <?php
        $uni_cities = array(
          'All cities'
        );
        $stmt = $con->prepare("SELECT map_uni_city FROM rfiltersmap");
        $stmt->execute();
        while ($blobfish = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          if (!$blobfish['map_uni_city']) break;
          array_push($uni_cities, ucwords($blobfish['map_uni_city']));
        }
        for ($i=1;$i<count($uni_cities);$i++)
        {
          printOption($uni_cities, $i, $selectedCity);
        }
        ?>
      </select>
      <?php
      $online_last = isset($_GET['online_last']) ? $_GET['online_last'] : 3;
      ?>
      <select class="select" name="online_last" data-default="3">
        <?php
        $online_options = array(
          'Online now!',
          'Online in the last day',
          'Online in the last week',
          'Online in the last month',
          'Online in the last year'
        );
        for ($i=0;$i<count($online_options);$i++)
        {
          printOption($online_options, $i, $online_last);
        }
        ?>
      </select>
<?php
$selected = array();
function printHiddenSelect($name, &$number, &$selected, &$con)
{
  $selected[$name] = isset($_GET[$name]) ? $_GET[$name] : 0;
  $hiddenClass = $selected[$name] == 0 ? ' hidden' : '';
  echo "<label class='select-label _s _s$number $hiddenClass'>"
        ."<select class='select' name='$name'>";
  $names = array(
    ucwords($name)
  );
  $stmt = $con->prepare("SELECT map_$name FROM rfiltersmap");
  $stmt->execute();
  while ($blobfish = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    if (!$blobfish["map_$name"]) break;
    array_push($names, ucwords($blobfish["map_$name"]));
  }
  for ($i=0;$i<count($names);$i++)
  {
    printOption($names, $i, $selected[$name]);
  }
  echo "</select><div class='select-label-text' data-close data-hide='_s$number' data-show='_ss$number'
        onclick='previousSibling.value=0'></div></label> ";
  $number++;
} // printHiddenSelect

$leNumber = 1;
printHiddenSelect('country',     $leNumber, $selected, $con);
printHiddenSelect('language',    $leNumber, $selected, $con);
printHiddenSelect('degree',      $leNumber, $selected, $con);
printHiddenSelect('studies',     $leNumber, $selected, $con);
printHiddenSelect('smokes',      $leNumber, $selected, $con);
printHiddenSelect('drinks',      $leNumber, $selected, $con);
printHiddenSelect('parties',     $leNumber, $selected, $con);
printHiddenSelect('drugs',       $leNumber, $selected, $con);
printHiddenSelect('pets',        $leNumber, $selected, $con);
printHiddenSelect('orientation', $leNumber, $selected, $con);
printHiddenSelect('religion',    $leNumber, $selected, $con);
printHiddenSelect('offspring',   $leNumber, $selected, $con);
printHiddenSelect('sign',        $leNumber, $selected, $con);
printHiddenSelect('ethnicity',   $leNumber, $selected, $con);
?>
      <span id="advanced-toggler" class="selector-toggler"></span>
      <div class="selector">
        <div class="selector-text" data-toggle="advanced-toggler" data-title="Advanced"></div>
        <div class="selector-content"
          ><ul class="selector-padding">
          <?php
          $count = 1;
          foreach ($selected as $key => $value)
          {
            $hiddenClass = $value == 0 ? '' : 'hidden';
            echo "<li class='selector-item selector-button _ss _ss$count $hiddenClass' data-hide='_ss$count' data-show='_s$count'>"
                 .ucwords($key).'</li>';
            $count++;
          }
          ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="input-wrapper">
      <input class="input-button" type="submit" value="Search">
      <input class="input-button cancel-button" type="button" value="Clear" data-hide="_s" data-show="_ss"
             onclick="Array.prototype.forEach.call(document.getElementById('blobfish').elements, function (element){
             if (element.className.indexOf('input-button')+1)return;
             element.value=((element.parentNode||{}).className||'').indexOf('_s')>-1?0:element.getAttribute('data-default');
             element.oninput&&element.oninput();
           })">
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
