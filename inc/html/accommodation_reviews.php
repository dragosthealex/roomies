<?php
// $reviews = $accomInfo['reviews'];

// $replyText = $reviews[$y]['replies'][$x]['text'];
?>
          <div class="review-header">Reviews</div>
            <div class="review-box">
              <div class="author-details">
                  <div class="review-pic">

                  </div>
                  <div class="author-text">
                    <div class="author-name">
                      <a class="link">Liam Higgins</a>
                    </div>
                    <div class="date-text">
                      27 February
                    </div>
                  </div>
              </div>
              <div class="review-text">
                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
              </div>
              <div class="like-reply">
                Like - Reply
              </div>
              <!-- Add php to check for replies -->
                <div class="review-header" style="border-top: 1px solid #d5d1d0; padding-top: 5px; text-align: right; padding-right: 10px;">
                  <a class="click-me" onclick="toggleReply(this)">Replies</a>
                </div>
                <div class="reply-box">
                <!-- add php to loop through the replies for the review -->
                  <div class="reply" id="hide">
                      <div class="author-details">
                        <div class="reply-text">
                          <div class="reply-pic">

                          </div>
                          <a class="link">Alex Radu</a> - The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                        </div>
                      </div>
                  </div>
                  <div class="reply" id="hide">
                      <div class="author-details">
                        <div class="reply-text">
                          <div class="reply-pic">

                          </div>
                          <a class="link">Alex Radu</a> - The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                        </div>
                      </div>
                  </div>
                </div>

            </div>
            <div class="review-box">
              <div class="author-details">
                  <div class="review-pic">

                  </div>
                  <div class="author-text">
                    <div class="author-name">
                      <a class="link">Liam Higgins</a>
                    </div>
                    <div class="date-text">
                      27 February
                    </div>
                  </div>
              </div>
              <div class="review-text">
                The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
              </div>
              <div class="like-reply">
                Like - Reply
              </div>
              <!-- Add php to check for replies -->
                <div class="review-header" style="border-top: 1px solid #d5d1d0; padding-top: 5px; text-align: right; padding-right: 10px;">
                  <a class="click-me" onclick="toggleReply(this)">Replies</a>
                </div>
                <div class="reply-box">
                <!-- add php to loop through the replies for the review -->
                  <div class="reply" id="hide">
                      <div class="author-details">
                        <div class="reply-text">
                          <div class="reply-pic">

                          </div>
                          <a class="link">Alex Radu</a> - The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                        </div>
                      </div>
                  </div>
                  <div class="reply" id="hide">
                      <div class="author-details">
                        <div class="reply-text">
                          <div class="reply-pic">

                          </div>
                          <a class="link">Alex Radu</a> - The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.
                        </div>
                      </div>
                  </div>
                </div>

            </div>
<script type="text/javascript">
  var hideAll = document.getElementsByClassName('reply-box');
  for(i = 0; i < hideAll.length; i ++)
  {
    hideAll[i].style.display = 'none';
  }

  function toggleReply(param) {
    var parent = param.parentNode;
    var nextItem = parent.nextElementSibling;
    if (nextItem.style.display == 'none') {
      nextItem.style.display = '';
    } else {
      nextItem.style.display = 'none';
    }
  }
</script>