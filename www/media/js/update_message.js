(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var update = window.roomies.update;
  var conv = document.getElementById('conv');
  var convParent = conv.parentNode;

  var updateMessages = function () {
    update('messageNew', '../php/update_message.process.php?type=new&lastId=' + conv.lastChild.getAttribute('data-message-id') + '&otherId=' + /messages\/([^\?]+)/.exec(window.location.href)[1], null, null, updateMessages);
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