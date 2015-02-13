(function(window, undefined){
  // Localise the document
  var document = window.document;
  // Localise the newError function
  var newError = window.newError;

  /**
   * A function to get the size of the page
   *
   * This function gets the width and height of the page
   */
  var size = function () {
    var width  =    window.innerWidth
                 || document.documentElement.clientWidth
                 || document.body.clientWidth;
    var height =    window.innerHeight
                 || document.documentElement.clientHeight
                 || document.body.clientHeight;

    return {width: width, height: height};
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

  // Function to toggle the visibility of an element
  var toggleElement = function (target) {
    // If the target is hidden, show it, else hide it
    if (/ hidden /.exec(target.className)) {
      target.className = target.className.replace(/ hidden /, ' ');
    } else {
      target.className += 'hidden ';
    } // else
  }; // toggleElement

  // Function to delete a specific element
  var deleteElement = function (element) {
    element.parentNode.removeChild(element);
  }; // deleteElement

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

  // Set a variable which contains the current hovered button, and its old text
  var hovered = {element:false,oldText:""};

  /**
   * Function to detect mouse movement.
   *
   * If the hovered element is not the target element (or false), then reset the hovered element.
   * If there is no hovered element and the target has a data-hover-text attribute, toggle the
   * text of the hovered element.
   * @param e The event object of the mouse movement.
   */
  window.onmousemove = function (e) {
    var tgt = e.target;

    if (hovered.element && hovered.element !== tgt) {
      hovered.element.innerHTML = hovered.oldText;
      hovered.element = false;
    } // if

    if (!hovered.element && tgt.hasAttribute('data-hover-text')) {
      hovered.element = tgt;
      hovered.oldText = tgt.innerHTML;
      tgt.innerHTML = tgt.getAttribute('data-hover-text');
    } // if
  }; // onmousemove

  // Get the header element
  var header = document.getElementsByClassName("header")[0];

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
    // Set the box-shadow of the header to the boxShadow string
    // TODO: Create an array of elements with class (box, column-box or header),
    //       and for each element in the array, apply the box shadow.
    //var slice = Array.prototype.slice.call;
    var sliced = Array.prototype.slice.call(document.getElementsByClassName("header"))
                                .concat(Array.prototype.slice.call(document.getElementsByClassName("box")))
                                .concat(Array.prototype.slice.call(document.getElementsByClassName("column-box")));

    sliced.forEach(
                function addShadow(value){
                  value.style.boxShadow = boxShadow;
                });
  }; // onscroll

}(window)); // Localise the window