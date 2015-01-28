<?php
require_once "../../inc/init.php";
if(LOGGED_IN)
{
  $title = "Complete Registration";
?>
<?php require_once __ROOT__."/inc/html/head.php";?>
    <!--header-->
    <?php require_once __ROOT__."/inc/html/header.".$ioStatus.".php";?>
    
    Complete registration

    </form>
    <?php require_once __ROOT__."/inc/html/footer.php";?>
  </body>
</html>
<?php
}
else
{
  require_once '../../inc/html/notfound.php';
  exit();
}
?>