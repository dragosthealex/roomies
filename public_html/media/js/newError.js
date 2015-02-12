(function (window) {
  // Localise the document
  var document = window.document;

  // Get the errorList element
  var errorList = document.getElementById('errorList');

  /**
   * A function to add an element to the list which removes itself.
   *
   * @param message The message to display inside the error box.
   * @param timeout The length of time, in milliseconds for error to appear for.
   */
  var newError = function (message, timeout) {
    // Create a new error box <div class="box errorBox "></div>
    var errorBox = document.createElement('div');
    errorBox.className = 'box errorBox ';

    // Fill the error box with the message
    errorBox.innerHTML = message;

    // Append the new error box to the list of errors
    errorList.appendChild(errorBox);

    // Default the timeout to 3 seconds
    timeout = timeout || 3000;

    // Set a timeout to remove the error box after "timeout" milliseconds
    setTimeout(function () {
      errorList.removeChild(errorBox);
    }, timeout);
  }; // newError

  // Allow the newError function to be used globally
  window.newError = newError;
}(window));