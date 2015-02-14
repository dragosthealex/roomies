(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var update = window.roomies.update;

  var updateMessages = function () {
    update('messages', '../php/update_message.process.php?otherId=' + window.location.href.split("=")[1], null, null, updateMessagesAgain);
  };

  var updateMessagesAgain = function () {
    // Give 3 seconds before updating again
    setTimeout(updateMessages, 3000);
  };

  updateMessagesAgain();
}(window)); // Localise the window