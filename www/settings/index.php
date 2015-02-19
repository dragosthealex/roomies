<?php
/*
This is the page that contains account settings, privacy settings, etc
*/
require_once "../../inc/init.php";

// Title of the page, used in head.php
$title = "Settings";
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>


<h3> Account Settings </h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
 <p>Change Password: <input type="password" name="password" /></p>
 <p>Change Email ID: <input type="email" name="emailid" /></p>
 
 <!---make this bold-->
 <p>
 	Privacy Settings : 
 <input type = "radio" name = "privacy" />Show my full name 
  <input type = "radio" name = "privacy" />Only show username
</p>
 <p><input type="radio" name="user_invisible" />Make me invisible</p> 
 <p><input type="radio" value="delete_acc" />Delete Account </p>
 <p><input type="submit" value="Update" /></p>
</form>

<?php
	
	// Output settings stuff here

	$username = $user -> getIdentifier("username");

	//EMAIL 
	if(isset($_POST['emailid']) && $_POST['emailid'] )
	{
		$email = htmlspecialchars($_POST["emailid"]);
		//if email is valid, update the table
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) //code taken from W3 schools
		{
			$stmt = $con->prepare("UPDATE rusers SET user_email = $email WHERE username = $username");
			$stmt->execute();
			$stmt->bindColumn(1, $dbId);
	    	$stmt->fetch();
		}
		//else, return an error message
		{
			echo "Error: Invalid Email ID";
		}
	}

	//PASSWORD
	if(isset($_POST['password']) && $_POST['password'])
	{
		$password = $_POST['password'];
		//if password is valid, proceed to update the table
		if(valid_pass($password))
		{
			// Create random salt, hash the pass
			$salt = mt_rand();
			$password = hash('sha256', $password1.$salt);
			$stmt = $con->prepare("UPDATE rusers SET user_salt = $salt, user_pass  = $password WHERE username = $username");
			$stmt->execute();
			$stmt->bindColumn(1, $dbId);
	    	$stmt->fetch();

		}

		//else return an error message
		else
		{
			echo "Error: Invalid Password";
		}

	} //end-if



	function valid_pass($password) 
	{
	   $r1='/[A-Z]/';  //Uppercase
	   $r2='/[a-z]/';  //lowercase
	   $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
	   $r4='/[0-9]/';  //numbers

	   if(!preg_match_all($r1,$password, $o)) return FALSE;

	   if(!preg_match_all($r2,$password, $o)) return FALSE;

	   if(!preg_match_all($r3,$password, $o)) return FALSE;

	   if(!preg_match_all($r4,$password, $o)) return FALSE;

	   if(strlen($password)>25) return FALSE;

	   return TRUE;
	} //end valid_pass

?>


<?php
require_once __ROOT__."/inc/html/footer.php";
?>