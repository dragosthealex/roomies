(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var html = document.documentElement;
  var body = document.body;
  // Localise the newError function
  var newError = window.newError;
  // Localise Array.prototype
  var aProto = Array.prototype;
  // Set a variable which contains the current hovered button, and its old text
  var hovered = {element:false,oldText:""};
  // Get the header element
  var header = document.getElementsByClassName("header")[0];

  /**
   * A function to get the current size of the page
   */
  var size = function () {
    return {
      width:  window.innerWidth  || html.clientWidth  || body.clientWidth,
      height: window.innerHeight || html.clientHeight || body.clientHeight
    };
  }; // size

  /**
   * A function to use ajax on an element (using its data attributes)
   */
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
        } // if
      } // if
    }; // onreadystatechange

    xmlhttp.open('GET', action);
    xmlhttp.setRequestHeader('Roomies','cactus');
    xmlhttp.send();
    if (element.hasAttribute('data-text-pending')) {
      element.innerHTML = element.getAttribute('data-text-pending');
    } // if
  }; // ajax

  /**
   * A function to toggle the visibility of an element
   */
  var toggleElement = function (target) {
    // If the target is hidden, show it, else hide it
    if (/ hidden /.exec(target.className)) {
      target.className = target.className.replace(/ hidden /, ' ');
    } else {
      target.className += 'hidden ';
    } // else
  }; // toggleElement

  /**
   * A function to delete a specific element
   */
  var deleteElement = function (element) {
    element.parentNode.removeChild(element);
  }; // deleteElement

  /**
   * A function to handle click events on the window
   */
  window.onclick = function (e) {
    // If the button press is not the left button, then return true.
    if ((e.which && e.which !== 1) || (e.button !== 1 && e.button !== 0)) {
      return true;
    } // if

    // Localise the class string of the target
    var className = e.target.className;

    // If we are to toggle visibility of something, do so.
    if (/ toggle /.exec(className) && e.target.hasAttribute('data-target')) {
      toggleElement(document.getElementById(e.target.getAttribute('data-target')));
    } // if

    // If we are to delete something, do so.
    if (/ delete /.exec(className) && e.target.hasAttribute('data-target')) {
      deleteElement(document.getElementById(e.target.getAttribute('data-target')));
    } // if

    // If we are to ajax the target, do so.
    if (/ ajax /.exec(className) && e.target.hasAttribute('data-action')) {
      ajax(e.target);
      // Prevent links
      return false;
    } // if
  }; // onclick

  /**
   * Function to detect mouse movement.
   */
  window.onmousemove = function (e) {
    // Localise the target
    var target = e.target;

    // If the hovered element is not the target element (or false), then reset it
    if (hovered.element && hovered.element !== target) {
      hovered.element.innerHTML = hovered.oldText;
      hovered.element = false;
    } // if

    // If there is no hovered element and the target has a data-hover-text attribute, toggle the
    // text of the hovered element.
    if (!hovered.element && target.hasAttribute('data-hover-text')) {
      hovered.element = target;
      hovered.oldText = target.innerHTML;
      target.innerHTML = target.getAttribute('data-hover-text');
    } // if
  }; // onmousemove

  /**
   * Function which fires whenever the user scrolls on the page
   *
   * Fade in the box shadows when a desktop PC scrolls down
   */
  window.onscroll = function () {
    // Get the distance to the top of the page
    var scrollTop = (   window.pageYOffset
                     || document.documentElement.scrollTop
                     || document.body.scrollType || 0);

    // Get the percentage distance from the top of the page
    var percent = scrollTop / 57;

    // Create the string for the box shadows
    var boxShadow = "0px 6px 4px -4px rgba(0,0,0," + ((percent > 1 ? 1 : percent) * 0.08) + ")";

    // Get the dimensions of the window
    var dim = size();

    // If the current size of the window is for mobiles, slide the navigation bar down
    // Only fix the navigation bar if the height of the window is big enough
    if (dim.width < 624) {
      if (dim.height > 320) {
        header.style.boxShadow = boxShadow;
        header.className = (scrollTop > 60 && dim.height > 320) ? "header header-fixed" : "header";
      } else {
        header.style.boxShadow = "none";
        header.className = "header";
      }
      return;
    }

    // Get all of the elements which need a box-shadow
    var boxShadowElements =         aProto.slice.call(document.getElementsByClassName("header"    ))
                            .concat(aProto.slice.call(document.getElementsByClassName("box"       )))
                            .concat(aProto.slice.call(document.getElementsByClassName("column-box")));

    // For each element which needs a box-shadow, apply one
    boxShadowElements.forEach(function (element) {
      element.style.boxShadow = boxShadow;
    });
  }; // onscroll

}(window)); // Localise the window