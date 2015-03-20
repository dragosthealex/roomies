<?php  $title = "Feedback";
  require_once '../../inc/init.php';
  require_once __ROOT__."/inc/html/head.php";
  require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
      <div class="column-wrapper">
        <div class="column-box">
          <div class="box-padding"> 
            <div class="input-wrapper">
              <h2 class="h2">Submit Feedback</h2>
              <form action="#" method="POST" enctype="text/plain" class="messages">
              <form action="action_page.php">
            	<h3 class="h3"> Username:</h3>
            	<input type="text" class="input" name="username" placeholder="Username"><br>
            	<h3 class="h3"> Email: </h3>
            	<input type="text" class="input" name="mail" placeholder="Email"><br>
              <h3 class="h3">Subject: </h3>
              <input type="text" class="input" name="subject" placeholder="subject"><br>
              <h3 class="h3"> Feedback: </h3>
                <div class="textarea-holder">
              	 <textarea type="textarea" class="textarea" id="message" placeholder="Enter feedback" name="comment" value="" size="100"></textarea>
                </div>
              <div class="input-wrapper">
            	   <input class="input-button" type="submit" value="Submit">
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>

<?php
if(isset($_POST["submit"]))
{
  // Checking For Blank Fields..
  if($_POST["email"]==""||$_POST["username"]==""||$_POST["subject"]==""||$_POST["message"]=="")
  {
    echo "Please fill all required fields.";
  }
  else
  {
    // Check if the "Sender's Email" input field is filled out
    $email=$_POST['email'];
    // Sanitize E-mail Address
    $email =filter_var($email, FILTER_SANITIZE_EMAIL);
    // Validate E-mail Address
    $email= filter_var($email, FILTER_VALIDATE_EMAIL);
      if (!$email)
      {
        echo "Invalid Sender's Email";
      }
      else
      {
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $headers = 'From:'. $email . PHP_EOL; // Sender's Email
        $headers .= 'Cc:'. $email . PHP_EOL; // Carbon copy to Sender
        // Message lines should not exceed 70 characters (PHP rule), so wrap it
        $message = wordwrap($message, 70);
        // Send Mail By PHP Mail Function
        mail("admin@roomies.co.uk", $subject, $message, $headers);
        echo "Your mail has been sent successfully! Thank you for your feedback";
      }
  }
}
?>

<?php
	include __ROOT__."/inc/html/footer.php";
?>