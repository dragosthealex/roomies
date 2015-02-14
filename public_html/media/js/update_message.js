(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var update = window.roomies.update;
  var convParent = document.getElementById('conv').parentNode;
  var convTracker = convParent.getElementsByClassName('scroll-tracker')[0];
  var gettingNextSet = false;

  convParent.onscroll = function () {
    if (convParent.scrollTop < 100 && !gettingNextSet)  {
        gettingNextSet = true;
        update('messages', '../php/update_message.process.php?otherId=' + window.location.href.split("=")[1], 'message', null, function () {
            gettingNextSet = false;
        });
    }

    var boxShadow = "none";
    if (convParent.scrollTop < convParent.scrollHeight - convParent.offsetHeight) {
        boxShadow = "inset 0 -6px 4px -4px rgba(0,0,0,0.12)";
        if (convParent.scrollTop > 0) {
            boxShadow += ", inset 0 6px 4px -4px rgba(0,0,0,0.12)";
        }
    } else if (convParent.scrollTop > 0) {
        boxShadow = "inset 0 6px 4px -4px rgba(0,0,0,0.12)";
    }
    convParent.style.boxShadow = boxShadow;

    convTracker.style.top = (80 * convParent.scrollTop / (convParent.scrollHeight - convParent.offsetHeight)) + "%";
  };

  var updateMessages = function () {
    update('messages', '../php/update_message.process.php?unread=true&otherId=' + window.location.href.split("=")[1], 'header', null, updateMessagesAgain);
  };

  var updateMessagesAgain = function () {
    // Give 3 seconds before updating again
    setTimeout(updateMessages, 3000);
  };

  updateMessagesAgain();

  setTimeout(function () {
    convParent.scrollTop = convParent.scrollHeight;
  }, 100);
}(window)); // Localise the window