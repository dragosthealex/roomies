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

  var hoverStack = [];

  win.onclick = function (e) {
    // Localise the class string of the target
    var className = e.target.className;

    // If we are to ajax the target, do so.
    if (/ ajax /.exec(className)) {
      ajax(e.target);
    }
  };

  win.onmousemove = function (e) {
    var tgt = e.target;

    for (var i = hoverStack.length - 1; i >= 0; i--) {
      if (hoverStack[i].tgt !== tgt) {
        hoverStack[i].tgt.innerHTML = hoverStack[i].originalText;
      }
    }

    if (tgt.hasAttribute('data-hover-text')) {
      hoverStack.push({tgt:tgt,originalText:tgt.innerHTML});
      tgt.innerHTML = tgt.getAttribute('data-hover-text');
    }
  };
}(window)); // Localise the window