(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var update = window.roomies.update;
  var conv = document.getElementById('main_conversation');
  var convParent = conv.parentNode;
  var currentUrl = window.location.href;
  var otherId = (currentUrl.indexOf('conv=')===-1)
                ? /messages\/([^\?]+)/.exec(currentUrl)[1]
                : currentUrl.split("=")[1];

  var updateMessages = function () {
    update('messageNew', '../php/update_message.process.php?type=new&lastId='
      + conv.lastChild.getAttribute('data-message-id') + '&otherId=' + otherId,
      null, null, updateMessages);
  };

  var updateMessagesAgain = function () {
    // Give 1 second before updating again
    setTimeout(updateMessages, 1000);
  };

  updateMessages();

  setTimeout(function () {
    convParent.scrollTop = convParent.scrollHeight;
  }, 100);
}(window)); // Localise the window