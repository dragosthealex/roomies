(function(win, undefined){
  // Localise the document
  var doc = win.document,
      newError = function (message) {
        // Add an error box to #error and set timeout to remove
        alert(message);
      },
      ajax = function (element) {
        if (!element.hasAttribute('data-action')) {

          return;
        }
        var action = element.getAttribute('data-action'),
            originalText = element.innerHTML,
            xmlhttp = win.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState === 4) {
            // If there was anything output, new error
            if(xmlhttp.status === 404) {
              newError("Not found suck balls");
            }
            else if (xmlhttp.responseText) {
              newError(xmlhttp.responseText);
            }
            else if(element.hasAttribute('data-action-toggle') && element.hasAttribute('data-text-toggle') && xmlhttp.status === 200) {
              var originalAction = element.getAttribute('data-action');

              // Set the next action and text
              element.setAttribute('data-action', element.getAttribute('data-action-toggle'));
              element.innerHTML = element.getAttribute('data-text-toggle');

              // Cache the old action and text
              element.setAttribute('data-text-toggle', originalText);
              element.setAttribute('data-action-toggle', originalAction);
            }
          }
        };

        xmlhttp.open("GET", action);
        xmlhttp.send();
        if (element.hasAttribute('data-text-pending')) {
          element.innerHTML = element.getAttribute('data-text-pending');
        }
      };

  var hovered = {element:false,oldText:""};

  win.onclick = function (e) {
    // If the button press is not the left button, then return true.
    if (e.which !== 1 && e.button !== 1 && e.button !== 0) {
      return true;
    }

    // Localise the class string of the target
    var className = e.target.className;

    // If we are to ajax the target, do so.
    if (/ ajax /.exec(className)) {
      ajax(e.target);
      // Prevent links
      return false;
    }
  };

  win.onmousemove = function (e) {
    var tgt = e.target;

    if (hovered.element && hovered.element !== tgt) {
      hovered.element.innerHTML = hovered.oldText;
      hovered.element = false;
    }

    if (!hovered.element && tgt.hasAttribute('data-hover-text')) {
      hovered.element = tgt;
      hovered.oldText = tgt.innerHTML;
      tgt.innerHTML = tgt.getAttribute('data-hover-text');
    }
  };
}(window)); // Localise the window