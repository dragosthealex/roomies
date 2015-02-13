
<div class="column-wrapper" id="notifications">
  <!--About me-->
  <div class="column-2">
    <div class="column-box">
      <div class="box-padding">
        <h2 class="h2">Messages</h2>
        <?php
          include __ROOT__."/inc/html/new_messages.php";
        ?>
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
