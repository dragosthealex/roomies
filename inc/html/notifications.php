
<div class="column-wrapper hidden " id="notifications">
  <!--About me-->
  <div class="column-2">
    <div class="column-box">
      <div class="box-padding">
        <h2 class="h2">Messages</h2>
        <!-- php to retrieve 'messages' from database should be here' -->
        <p class="text">
        </p>
      </div>
    </div>
  </div>
  <!--filters-->
  <div class="column-2">
    <div class="column-box">
      <div class="box-padding">
        <h2 class="h2">Friend requests</h2>
        <?php
          include __ROOT__."/inc/html/friend_requests.php";
        ?>
      </div>
    </div>
  </div>
</div>
