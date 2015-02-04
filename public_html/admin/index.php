<?php
/*
This is the admin page
For now, we will be able to insert new questions, delete them and insert new ones
*/
require_once '../../inc/init.php';
if(!LOGGED_IN && !ADMIN)
{
  require_once __ROOT__."/inc/html/notfound.php";
}

$dots = '../';
$title = 'Admin';
require_once __ROOT__."/inc/html/head.php";
require_once __ROOT__."/inc/html/header.$ioStatus.php";
?>
<!--main content-->
<div class="main">
  <div class="box">
    <div class="box-padding">
      <div class="box-tab">
        <ul class="ul">
          <li class="li tab">
            <a class="link-button" name="questions" onclick="show(this);">
              Questions
            </a>
          </li>
          <li class="li tab">
            <a class="link-button" name="insert" onclick="show(this);">
              Insert Question
            </a>
          </li>
          <li class="li tab">
          </li>
        </ul>
      </div>
      <div id="questions" class="box-content">
        <?php include __ROOT__.'/inc/html/questions.php';?>
      </div>
      <div id="insert" class="box-content">
        <?php include __ROOT__.'/inc/html/insert.php';?>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="../media/js/adminTabs.js"></script>
<?php require_once __ROOT__."/inc/html/footer.php";?>