(function(window, undefined){
  // Localise the document, html and body
  var document = window.document;
  var html = document.documentElement;
  var body = document.body;
  // Localise the newError function
  var newError = window.newError;
  // Localise Array.prototype
  var aProto = Array.prototype;
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
   * An object which holds javascript functions for interactivity
   */
  var func = {
    // A function to toggle the visibility of an element
    'toggle': function (element) {
      // If the element is hidden, show it, else hide it
      if (/ hidden /.exec(element.className)) {
        element.className = element.className.replace(/ hidden /, ' ');
      } else {
        element.className += 'hidden ';
      } // else
    },

    // A function to delete an element
    'delete': function (element) {
      element.parentNode.removeChild(element);
    },

    // A function to update something in the page
    'update': function (part, url, className) {
      var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4) {
          // If there was anything output, new error
          if (xmlhttp.status === 404) {
            newError("Not found suck balls");
          } else if (xmlhttp.status === 200 && xmlhttp.responseText) {
            var obj = JSON.parse(xmlhttp.responseText);

            var newHTML = obj.template;

            for (var i = 0; i < obj.length; i += 1) {
              for (var j = 0; j < obj.template.length - 1; j += 1) {
                newHTML += obj.template[j] + obj[i][j];
              }

              newHTML += obj.template[obj.template.length - 1];
            }

            newError(newHTML);
          }
        } // if
      }; // onreadystatechange

      xmlhttp.open('GET', url);
      xmlhttp.setRequestHeader('Roomies','cactus');
      xmlhttp.send('offset=' + document.getElementsByClassName(className).length);
    },

    // A function to use ajax on an element
    'ajax': function (element) {
      var url = element.getAttribute('data-ajax-url'),
          originalText = element.innerHTML,
          hideText,
          xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4) {
          // If there was anything output, new error
          if (xmlhttp.status === 404) {
            newError("Not found suck balls");
          } else if (xmlhttp.responseText) {
            newError(xmlhttp.responseText);
          } else if (hideText = element.getAttribute('data-ajax-hide') && xmlhttp.status === 200) {
            hideText = hideText.split(" ");

            var elementsToHide = aProto.splice.call(document.getElementsByClassName(hideText[0]));

            elementsToHide.forEach(function (element) {
              element.style.visibility = "none";
            });

            document.getElementById(hideText[1]).removeAttribute('style');
          } // if

          // TODO: data-ajax-callback
        } // if
      }; // onreadystatechange

      var post = element.getAttribute('data-ajax-post');

      xmlhttp.open((post ? 'POST' : 'GET'), url);
      xmlhttp.setRequestHeader('Roomies','cactus');
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      if (post) {
        var letter = "kiwi=awesome";

        post = post.split(" ");

        post.forEach(function (id) {
          var element = document.getElementById(id);

          letter += "&" + id + "=" + encodeURIComponent(element.value);
        });

        xmlhttp.send(letter);
      } else {
        xmlhttp.send();
      }

      if (element.hasAttribute('data-ajax-text')) {
        element.innerHTML = element.getAttribute('data-ajax-text');
      } // if
    }
  };

  /**
   * A function to handle click events on the window
   */
  window.onclick = function (e) {
    // If the button press is not the left button, then return true.
    if ((e.which && e.which !== 1) || (e.button !== 1 && e.button !== 0)) {
      return true;
    } // if

    // Localise the element that was clicked and its className
    var element = e.target;
    // Localise the class string of the target
    var className = element.className;

    // Localise a variable for later use
    var target;

    // If a target needs toggling, do so.
    if (target = document.getElementById(element.getAttribute('data-toggle'))) {
      func['toggle'](target);
    } // if

    // If a target needs deleting, do so.
    if (target = document.getElementById(element.getAttribute('data-delete'))) {
      func['delete'](target);
    } // if

    // If the element employs ajax, do some ajax.
    if (element.hasAttribute('data-ajax-url')) {
      func['ajax'](element);
      return false;
    } // if
  }; // onclick

  /**
   * A function to apply box-shadows to certain elements, dependent upon the distance from the top.
   * If the window is mobile-sized, then work on the header instead
   */
  var setBoxShadows = function () {
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

    // Preset a variable to allow modififying the header boxshadow
    var modifyHeader = true;

    // If the current size of the window if for mobiles, handle things differently
    if (dim.width < 624) {
      // Apply the box shadow to the header, and fix the navigation bar for phones
      // (Only if the window is big enough: if not, ensure it isn't fixed)
      if (dim.height > 320) {
        header.style.boxShadow = boxShadow;
        header.className = (scrollTop > 60) ? "header header-fixed" : "header";

        // Prevent the header from being changed again
        modifyHeader = false;
      } else {
        // Ensure the header is not fixed
        header.className = "header";
      }

      // If on phones, box-shadows are 'none'
      boxShadow = "none";
    }

    // Get all of the elements which need a box-shadow
    var boxShadowElements =         aProto.slice.call(document.getElementsByClassName("box"))
                            .concat(aProto.slice.call(document.getElementsByClassName("column-box")));

    // If we are to also get the header, then do so
    if (modifyHeader) {
      boxShadowElements.concat(aProto.slice.call(document.getElementsByClassName("header")));
    }

    // For each element which needs a box-shadow, apply one
    boxShadowElements.forEach(function (element) {
      element.style.boxShadow = boxShadow;
    });
  };

  // When the user scrolls, set the box shadows
  // If the window is resized, set the box shadows
  // Upon page load, set the box shadows
  window.onscroll = window.onresize = window.onload = setBoxShadows;
}(window)); // Localise the window