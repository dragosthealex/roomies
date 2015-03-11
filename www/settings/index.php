<?php
/*
This is the page that contains account settings, privacy settings, etc
*/
require_once "../../inc/init.php";

function validate_pass($con, $id, $pass)
{
	$stmt = $con->prepare("SELECT user_pass, user_salt FROM rusers WHERE user_id = $id");
	try
	{
		// Weird if not executed
		if(!$stmt->execute())
		{
			throw new Exception("Error in query when validating the pass", 1);
		}

		// Fetch the thingies
		$stmt->bindColumn(1, $dbPass);
		$stmt->bindColumn(2, $dbSalt);
		$stmt->fetch();

		// Weirder if no pass for this id
		if(!$stmt->rowCount())
		{
			throw new Exception("Error. User not found with this id. Weird.", 1);
		}

		// Check if it's good pass
		if(hash('sha256', $pass.$dbSalt) == $dbPass)
		{
			return true;
		}
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		return false;
	}
}

// Title of the page, used in head.php
$title = "Settings";
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>

<div class="box">
	<div class="box-padding">
		<ul class="ul">
	    <li class="li tab">
	      <a class="link-button" name="questions" onclick="show(this);">
	        Account Settings
	      </a>
	    </li>
	    <li class="li tab">
	      <a class="link-button" name="insert" onclick="show(this);">
	        Notifications
	      </a>
	    </li>
	    <li class="li tab">
	      <a class="link-button" name="insert" onclick="show(this);">
	        Connected accounts
	      </a>
	    </li>
	    <li class="li tab">
	      <a class="link-button" name="insert" onclick="show(this);">
	        My reviews
	      </a>
	    </li>
	    <li class="li tab">
	      <a class="link-button" name="insert" onclick="show(this);">
	        SHITFUCK
	      </a>
	    </li>
  	</ul>
		<?php require_once __ROOT__.'/inc/html/account_settings.php';?>
		<?php require_once __ROOT__.'/inc/html/notification_settings.php';?>
		<?php require_once __ROOT__.'/inc/html/connected_settings.php';?>
		<?php require_once __ROOT__.'/inc/html/reviews_settings.php';?>
	</div>
</div>

<?php
require_once __ROOT__."/inc/html/footer.php";
?>