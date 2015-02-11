(function(window, undefined){
  // Localise the document
  var document = window.document;

  var errorList = document.getElementById('error');

  var newError = function (message) {
    // Add an error box to #error and set timeout to remove
    var errorBox = document.createElement('div');
    errorBox.className = 'errorBox';
    errorBox.innerHTML = message;
    errorList.appendChild(errorBox);

    setTimeout(function () {
      errorList.removeChild(errorBox);
    });
    alert(message);
  };

  var ajax = function (element) {
    var action = element.getAttribute('data-action'),
        originalText = element.innerHTML,
        xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState === 4) {
        // If there was anything output, new error
        if (xmlhttp.status === 404) {
          newError("Not found suck balls");
        } else if (xmlhttp.responseText) {
          newError(xmlhttp.responseText);
        } else if (   element.hasAttribute('data-action-toggle')
                   && element.hasAttribute('data-text-toggle')
                   && xmlhttp.status === 200) {
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

    xmlhttp.open('GET', action);
    xmlhttp.setRequestHeader('Roomies','cactus');
    xmlhttp.send();
    if (element.hasAttribute('data-text-pending')) {
      element.innerHTML = element.getAttribute('data-text-pending');
    }
  };

  // Function to toggle the visibility of an element
  var toggleElement = function (target) {
    // If the target is hidden, show it, else hide it
    if (/ hidden /.exec(target.className)) {
      target.className = target.className.replace(/ hidden /, ' ');
    } else {
      target.className += 'hidden ';
    }
  };

  // Function to delete a specific element
  var deleteElement = function (element) {
    element.parentNode.removeChild(element);
  };

  window.onclick = function (e) {
    // If the button press is not the left button, then return true.
    if ((e.which && e.which !== 1) || (e.button !== 1 && e.button !== 0)) {
      return true;
    }

    // Localise the class string of the target
    var className = e.target.className;

    // If we are to toggle visibility of something, do so.
    if (/ toggle /.exec(className) && e.target.hasAttribute('data-target')) {
      toggleElement(document.getElementById(e.target.getAttribute('data-target')));
    }

    // If we are to delete something, do so.
    if (/ delete /.exec(className) && e.target.hasAttribute('data-target')) {
      deleteElement(document.getElementById(e.target.getAttribute('data-target')));
    }

    // If we are to ajax the target, do so.
    if (/ ajax /.exec(className) && e.target.hasAttribute('data-action')) {
      ajax(e.target);
      // Prevent links
      return false;
    }
  };

  var hovered = {element:false,oldText:""};

  window.onmousemove = function (e) {
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
  //TERNARY
  window.onscroll = function () {
    var percent = (window.pageYOffset || document.documentElement.scrollTop || document.body.scrollType || 0) / 114;
    percent = percent > 1 ? 1 : percent;
    document.getElementsByClassName("header")[0].style.boxShadow="0px 6px 4px -4px rgba(0,0,0," + (percent * 0.08) + ")";
  };
}(window)); // Localise the window