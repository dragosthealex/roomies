<div class="box-padding">
  <h2 class="h2" name="Optional_details">
    More details
  </h2>
  <p>
    The following details are optional and can be completed later, from your profile settings. They are helping other potential roomies to find you.
  </p>
  <form action="processOpt.php" name="opt_details" method="POST">
    <div>
      <input class="input" type="text" required="" title="2 to 20 characters" placeholder="Mock stuff here" name="fi"></input>
    </div>
    <div>
      <span>
        <p>
          Again mock:
        </p>
      </span>
      <select class="select has-submit" required="" id="shit1" name="sel1" form="opt_details">
        <option class="option" selected="" value="" >Default Stuff</option>
      </select>
      <select class="select has-submit" required="" id="shit2" name="sel2" form="opt_details">
        <option class="option" selected="" value="" >Select this stuff</option>
        <option class="option" value="2" >No, select this</option>
      </select>
    </div>
  </form>
</div>