<div class="box" id="opt-details">
  <div class="box-padding">
          <h2 class="h2">My Details</h2>
          <form id="details-form">
            <?php
              foreach ($x as $y) {
                $z = ucwords($y);
                if($z == "Uni_city")
                {
                  $z = "University City";
                }

                echo "

                  <div class='details-wrapper'>
                    <div class='details-key'>
                                    $z      
                    </div>
                    <div class='details-value'>
                                    $details[$y]
                    </div>
                                <div class='new-val'><select class='select-details' name='$y' data-default='$details[$y]'>";

                        $thisKeyArr = array(
                                '-'
                            );
                        $stmt = $con->prepare("SELECT map_$y FROM rfiltersmap");
                        $stmt->execute();
                        while($result = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                            if (!$result['map_'.$y]) break;
                            array_push($thisKeyArr, ucwords($result['map_'.$y]));
                        }
                        for ($i=0;$i<count($thisKeyArr);$i ++)
                        {   
                            if($details[$y] == $thisKeyArr[$i])
                                echo "<option value='$i' selected>$thisKeyArr[$i]</option>";
                            else
                                echo "<option value='$i'>$thisKeyArr[$i]</option>";
                        }

                        echo       "</select>
                                </div>
                  </div>";
                } 
                ?>
              </form>
            </div>
</div>
<script type="text/javascript">
    var detailsVal = document.getElementsByClassName('details-value');
    var detailsNewVal = document.getElementsByClassName('new-val');
    var questionsUnanswered = document.getElementsByClassName('unanswered');
    var questionsAnswered = document.getElementsByClassName('answered');
    var answeredIndex = 2;
    var unansweredIndex = 2;
    var prevButton = document.getElementById('prevButton');
    var nextButton = document.getElementById('nextButton');

    checkButtons();
    for(var count = 0; count < questionsAnswered.length; count ++)
    {
        questionsAnswered[count].className = questionsAnswered[count].className + ' hidden';
    };

    for(var count = 2; count < questionsUnanswered.length; count ++)
    {
        questionsUnanswered[count].className = questionsUnanswered[count].className + ' hidden';
    };



    for(var count = 0; count < detailsNewVal.length; count ++)
        detailsNewVal[count].style.display = 'none';

    function editProfile() {
        for(var i = 0; i < detailsVal.length; i ++)
        {
            if(detailsVal[i].style.display == '')
            {
                detailsVal[i].style.display = 'none';
                detailsNewVal[i].style.display = '';
            }
            else
            {
                detailsVal[i].style.display = '';
                detailsNewVal[i].style.display = 'none';
            }                      
        };

        for (var i = 0; i < questionsAnswered.length; i++) {
            questionsAnswered[i].className = ' question answered ';

        };

        for (var i = 0; i < questionsUnanswered.length; i++) {
            questionsUnanswered[i].className = ' question unanswered hidden ';
            
        };
    }

    function saveEdit() {
      for(var i=0; i<detailsVal.length; i++) {
        var element = detailsNewVal[i].firstChild;
        detailsVal[i].innerHTML = element.options[element.selectedIndex].text;
      }
      cancelEdit();
    }

    function cancelEdit() {
        for(var i = 0; i < detailsVal.length; i ++)
        {
            if(detailsVal[i].style.display == '')
            {
                detailsVal[i].style.display = 'none';
                detailsNewVal[i].style.display = '';
            }
            else
            {
                detailsVal[i].style.display = '';
                detailsNewVal[i].style.display = 'none';
            }                      
        };

        for (var i = 0; i < questionsAnswered.length; i++) {
            questionsAnswered[i].className = ' question answered hidden';

        };

        answeredIndex = 2;
        printQuestionsUnanswered();

    }
</script>