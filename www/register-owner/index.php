<?php
// The register page for owners
require_once '../../inc/init.php';
if(LOGGED_IN || OWNER_LOGGED_IN)
{
  header('Location: ../');
  exit();
}
$errMsg = '';
$title = "Register owner";
require_once __ROOT__.'/inc/html/head.php';
require_once __ROOT__.'/inc/html/header.out.php';
require_once __ROOT__.'/inc/classes/TempOwner.php';

function listCountryOptions()
{
  // We have the array by courtesy of user DHS(David Haywood Smith) from GitHub
  $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
  foreach($countries as $country)
  {
    echo "<option class='option' value='$country'>$country</option>";
  }
}

if(isset($_POST['submit']))
{
  try
  {
    // Validate input
    if(!isset($_POST['registerUsername']) || !isset($_POST['registerPassword']) || !isset($_POST['registerConfirmPassword']) || !isset($_POST['registerEmail']) || !isset($_POST['b_year']) || !isset($_POST['b_day']) || !isset($_POST['bmonth']) || !isset($_POST['gender']) || !isset($_POST['city']) || !isset($_POST['country']) || !isset($_POST['phone']) || !$_POST['registerUsername'] || !$_POST['registerPassword'] || !$_POST['registerConfirmPassword'] || !$_POST['registerEmail'] || !$_POST['b_year'] || !$_POST['b_day'] || !$_POST['bmonth'] || !$_POST['gender'] || !$_POST['city'] || !$_POST['country'] || !$_POST['phone'])
    {
      //header('Location: ./?error=incomplete');
      //exit();
    }
    // Check if conf pass == pass
    if($_POST['registerConfirmPassword'] != $_POST['registerConfirmPassword'])
    {
      throw new Exception("Your confirm password does not match your password", 1);
    }
    // Make the details array
    $details = array();
    $detailsString = array(array(), array());
    foreach ($_POST as $key => $value)
    {
      if($key != 'submit' && $key != 'registerConfirmPassword' && $key != 'randomKey')
      {
        $value = htmlentities($value);
        $details[$key] = $value;
        array_push($detailsString[0], $key);
        array_push($detailsString[1], $value);
      }
    }
    // Sepparate details array, to store it into temp users
    $detailsString[0] = implode(',', $detailsString[0]);
    $detailsString[1] = implode(',', $detailsString[1]);
    $detailsString = implode(':', $detailsString);

    $params['username'] = htmlentities($_POST['registerUsername']);
    $params['salt'] = mt_rand();
    $params['password'] = hash('sha256', htmlentities($_POST['registerPassword']) . $params['salt']);
    $params['details'] = $detailsString;
    $params['email'] = htmlentities($_POST['registerEmail']);

    $tempOwner = new TempOwner($con, 'insert', $params);
    if($tempOwner->getError())
    {
      throw new Exception("Error with creating temp owner: " . $tempOwner->getError, 1);
    }
    header('Location: ./?conf=0');
    exit();
  }// try
  catch (Exception $e)
  {
    $errMsg .= $e->getMessage();
  }
}

if(!isset($_GET['conf']) && !isset($_SESSION['tempOwner']))
{
?>
<div class="box">
  <div class="error">
    <?=$errMsg;?>
    <?=isset($_SESSION['tempOwner']);?>
  </div>
  <div class="box-padding">
    <h2 id="Complete_registration" class="h2">
      Register as owner
    </h2>
    <p>
      The following details are mandatory for your registration.
    </p>
    <div id="error" <?php echo (isset($_GET['error']))?"":"style='display:none;'"?>>
      <p style="color: red;">
        You must complete all fields before continuing.
      </p>
    </div>
    <form action="" name="details" method="POST">
      <div>
        <span>
          <p>
            Account details:
          </p>
        </span>
        <input type="email" name="registerEmail" placeholder="Email" class="input block" required>
        <input type="password" name="registerPassword" placeholder="Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
        <input type="password" name="registerConfirmPassword" placeholder="Confirm Password" class="input block" required pattern=".{6,25}" title="6 to 25 characters">
        <input type="text" name="registerUsername" placeholder="Username" class="input block" required pattern=".{4,25}" title="4 to 25 characters">
      </div>
      <div>
        <input class="input" type="text" required="" title="2 to 20 characters" placeholder="First/Given Name" name="first_name"></input>
        <input class="input input" type="text" required="" title="2 to 20 characters" placeholder="Last/Family Name" name="last_name"></input>
      </div>
      <div>
        <span>
          <p>
            Birthday:
          </p>
        </span>
        <select class="select has-submit" id="byear" name="b_year">
          <option class="option" value="" selected="">Select year</option>
        </select>
        <select class="select has-submit" id="bmonth" name="b_month">
          <option class="option" value="" selected="">Select month</option>
        </select>
        <select class="select has-submit" id="bday" name="b_day">
          <option class="option" value="" selected="">Select day</option>
        </select>
      </div>
      <div>
        <span>
          <p>
            I identify my gender as:
          <p>
        </span>
        <select class="select has-submit" name="gender">
          <option class="option" value="Select">Select gender</option>
          <option class="option" value="man">Man</option>
          <option class="option" value="woman">Woman</option>
          <option class="option" value="trans">Trans*</option>
        </select>
      </div>
      <div>
        <span>
          <p>
            My Address:
          <p>
        </span>
        <input class="input block" style="max-width:50%;" type="text" required="" title="2 to 20 characters" placeholder="Post Code" name="post_code"></input>
        <select class="select has-submit" name="city">
          <option class="option" value="">Select city</option>
          <option class="option" value="Manchester">Manchester</option>
        </select>
        <select class="select has-submit" name="country">
          <option class="option" value="" selected="">Select country</option>
          <?php listCountryOptions();?>
        </select>
      </div>
      <div>
        <span>
          <p>
            My contact details:
          </p>
        </span>
        <input class="input block" style="max-width:50%;" type="text" required="" title="2 to 20 characters" placeholder="Telephone" name="phone"></input>
      </div>
      <input type="hidden" name="randomKey" value="<?php echo $_SESSION['randomKey'];?>">
        <p class="small-text">By registering, you agree to our
          <a href="#terms" class="link">Terms</a> and
          <a href="#privacy" class="link">Privacy Policy</a>, including our
          <a href="#cookies" class="link">Cookie Use</a>.
        </p>
      <input class="input-button block" name="submit" type="submit" value="Register">
    </form>
  </div>
</div>
<script type="text/javascript" src="../media/js/jquery.min.js"></script>
<script type="text/javascript" src="../media/js/birthday.js"></script>
<?php require_once __ROOT__.'/inc/html/footer.php';?>

<?php
  function listLanguageOptions()
  {
    $language_codes = array('en' => 'English' , 'aa' => 'Afar' , 'ab' => 'Abkhazian' , 'af' => 'Afrikaans' , 'am' => 'Amharic' , 'ar' => 'Arabic' , 'as' => 'Assamese' , 'ay' => 'Aymara' , 'az' => 'Azerbaijani' , 'ba' => 'Bashkir' , 'be' => 'Byelorussian' , 'bg' => 'Bulgarian' , 'bh' => 'Bihari' , 'bi' => 'Bislama' , 'bn' => 'Bengali/Bangla' , 'bo' => 'Tibetan' , 'br' => 'Breton' , 'ca' => 'Catalan' , 'co' => 'Corsican' , 'cs' => 'Czech' , 'cy' => 'Welsh' , 'da' => 'Danish' , 'de' => 'German' , 'dz' => 'Bhutani' , 'el' => 'Greek' , 'eo' => 'Esperanto' , 'es' => 'Spanish' , 'et' => 'Estonian' , 'eu' => 'Basque' , 'fa' => 'Persian' , 'fi' => 'Finnish' , 'fj' => 'Fiji' , 'fo' => 'Faeroese' , 'fr' => 'French' , 'fy' => 'Frisian' , 'ga' => 'Irish' , 'gd' => 'Scots/Gaelic' , 'gl' => 'Galician' , 'gn' => 'Guarani' , 'gu' => 'Gujarati' , 'ha' => 'Hausa' , 'hi' => 'Hindi' , 'hr' => 'Croatian' , 'hu' => 'Hungarian' , 'hy' => 'Armenian' , 'ia' => 'Interlingua' , 'ie' => 'Interlingue' , 'ik' => 'Inupiak' , 'in' => 'Indonesian' , 'is' => 'Icelandic' , 'it' => 'Italian' , 'iw' => 'Hebrew' , 'ja' => 'Japanese' , 'ji' => 'Yiddish' , 'jw' => 'Javanese' , 'ka' => 'Georgian' , 'kk' => 'Kazakh' , 'kl' => 'Greenlandic' , 'km' => 'Cambodian' , 'kn' => 'Kannada' , 'ko' => 'Korean' , 'ks' => 'Kashmiri' , 'ku' => 'Kurdish' , 'ky' => 'Kirghiz' , 'la' => 'Latin' , 'ln' => 'Lingala' , 'lo' => 'Laothian' , 'lt' => 'Lithuanian' , 'lv' => 'Latvian/Lettish' , 'mg' => 'Malagasy' , 'mi' => 'Maori' , 'mk' => 'Macedonian' , 'ml' => 'Malayalam' , 'mn' => 'Mongolian' , 'mo' => 'Moldavian' , 'mr' => 'Marathi' , 'ms' => 'Malay' , 'mt' => 'Maltese' , 'my' => 'Burmese' , 'na' => 'Nauru' , 'ne' => 'Nepali' , 'nl' => 'Dutch' , 'no' => 'Norwegian' , 'oc' => 'Occitan' , 'om' => '(Afan)/Oromoor/Oriya' , 'pa' => 'Punjabi' , 'pl' => 'Polish' , 'ps' => 'Pashto/Pushto' , 'pt' => 'Portuguese' , 'qu' => 'Quechua' , 'rm' => 'Rhaeto-Romance' , 'rn' => 'Kirundi' , 'ro' => 'Romanian' , 'ru' => 'Russian' , 'rw' => 'Kinyarwanda' , 'sa' => 'Sanskrit' , 'sd' => 'Sindhi' , 'sg' => 'Sangro' , 'sh' => 'Serbo-Croatian' , 'si' => 'Singhalese' , 'sk' => 'Slovak' , 'sl' => 'Slovenian' , 'sm' => 'Samoan' , 'sn' => 'Shona' , 'so' => 'Somali' , 'sq' => 'Albanian' , 'sr' => 'Serbian' , 'ss' => 'Siswati' , 'st' => 'Sesotho' , 'su' => 'Sundanese' , 'sv' => 'Swedish' , 'sw' => 'Swahili' , 'ta' => 'Tamil' , 'te' => 'Tegulu' , 'tg' => 'Tajik' , 'th' => 'Thai' , 'ti' => 'Tigrinya' , 'tk' => 'Turkmen' , 'tl' => 'Tagalog' , 'tn' => 'Setswana' , 'to' => 'Tonga' , 'tr' => 'Turkish' , 'ts' => 'Tsonga' , 'tt' => 'Tatar' , 'tw' => 'Twi' , 'uk' => 'Ukrainian' , 'ur' => 'Urdu' , 'uz' => 'Uzbek' , 'vi' => 'Vietnamese' , 'vo' => 'Volapuk' , 'wo' => 'Wolof' , 'xh' => 'Xhosa' , 'yo' => 'Yoruba' , 'zh' => 'Chinese' , 'zu' => 'Zulu' , );

    foreach ($language_codes as $language)
    {
      echo "<option class='option' value='$language'>$language</option>";
    }
  }
}
else
{
  $conf = htmlentities($_GET['conf']);

  if(isset($_SESSION['tempOwner']))
  {
    $tempUsername = $_SESSION['tempOwner']['username'];
  }
  else if(isset($_GET['owner'], $_GET['conf']))
  {
    $tempUsername = $_GET['owner'];
  }
  else
  {
    header('Location: ../?err=needlogin');
    exit();
  }

  // Try to confirm the temp owner
  if($conf)
  {
    $errMsg .= " $tempUsername ";
    $tempOwner = new TempOwner($con, 'get', array('username' => $tempUsername));
    if($tempOwner->getError())
    {
      $errMsg .= "Error initialising owner: " . $tempOwner->GetError();
    }
    else
    {
      if($tempOwner->confirm($conf))
      {
        header('Location: ../');
        exit();
      }
      else
      {
        $errMsg .= "The confirmation code is wrong. Please try again: " . $tempOwner->getError();
      }
    }
    $title='Confirm';
  }
?>
<?php require_once __ROOT__."/inc/html/head.php";?>
    <?php require_once __ROOT__."/inc/html/header.$ioStatus.php";?>
    <!--Header, etc-->

    <!-- test form-->
    <form method="GET" action="">
      <?=$errMsg?>
      <input type="text" name="conf" placeholder="Input confirmation code">
      <button type="submit">Submit</button>
      <a href="./?logout=yes">logout</a>
    </form>
<?php require_once __ROOT__."/inc/html/footer.php";?>
<?php }?>