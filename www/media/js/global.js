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
  // Set a variable for whether the mouse is down
  var mouseIsDown = false;
  var target;
  var newMessageCount = 0;
  var originalTitle = document.title;

  var everyElement = aProto.slice.call(body.getElementsByTagName('*'));
  everyElement.forEach(function (element) {
    if (!/^ /.exec(element.className)) {
      element.className = ' ' + element.className;
    }
    if (!/ $/.exec(element.className)) {
      element.className += ' ';
    }
  });

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
   * A function to return the offset of an element from the document
   */
  var offset = function (element) {
    var x = 0;
    var y = 0;
    while (element && !isNaN(element.offsetLeft) && !isNaN(element.offsetTop)) {
        x += element.offsetLeft - element.scrollLeft;
        y += element.offsetTop - element.scrollTop;
        element = element.offsetParent;
    }
    return {top: y, left: x};
  };

  /**
   * An object which holds javascript functions for interactivity
   */
  var roomies = {
    // A function to hide a list of elements
    'hide': function (elements) {
      for (var i = 0; i < elements.length; i += 1) {
        if (!/ hidden /.exec(elements[i].className)) {
          elements[i].className += "hidden ";
        }
      }
    },

    // A function to toggle the visibility of an element
    'toggle': function (element) {
      // If the element is hidden, show it, else hide it
      if (/ hidden /.exec(element.className)) {
        element.className = element.className.replace(/ hidden /, ' ');
        // Get any scroll areas and ensure they have a scrollbar
        var scrollAreas = aProto.slice.call(element.getElementsByClassName('scroll-area'));
        scrollAreas.forEach(function (scrollArea) {
          scrollAreaFunc(scrollArea);
        });
      } else {
        element.className += 'hidden ';
      } // else
    },

    // A function to delete an element
    'delete': function (element) {
      element.parentNode.removeChild(element);
    },

    // A function to return an array of all parent drops and minidrops
    'getDrops': function getDrops(element) {
      return element === body ? [] : (/ drop /.exec(element.className) || / minidrop /.exec(element.className)) ? [element].concat(getDrops(element.parentNode)) : getDrops(element.parentNode);
    },

    // A function to scroll an scroll thingy, given the scrollbar element and the distance from the top of the element
    'scroll': function (element, mouseY) {
      mouseY += (window.pageYOffset || document.documentElement.scrollTop || document.body.scrollType || 0);
      mouseY -= offset(element).top;
      var boxHeight = element.offsetHeight;
      var scrollHeight = element.parentNode.scrollHeight;
      element.parentNode.scrollTop = (scrollHeight - boxHeight) * ((mouseY - 0.1 * boxHeight) / 0.8) / boxHeight;
    },

    // A function to update something in the page
    'update': function (part, url, className1, className2, callBack) {
      var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4) {
          // If there was anything output, new error
          if (xmlhttp.status === 404) {
            newError("Not found suck balls");
          } else if (xmlhttp.status === 200 && xmlhttp.responseText) {
            var objs = JSON.parse(xmlhttp.responseText);
            var obj;
            var newHTML = [];

            for (var i = 0; i < objs.length; i += 1) {
              obj = objs[i];
              newHTML[i] = "";

              for (var j = 0; j < obj.length; j += 1) {
                for (var k = 0; k < obj.template.length - 1; k += 1) {
                  newHTML[i] += obj.template[k] + obj[j][k];
                }
                newHTML[i] += obj.template[obj.template.length - 1];
              }
            }

            var conv = document.getElementById('conv');
            var convParent = conv.parentNode;

            switch (part) {
              case 'messageNew':
                if (objs[0].length) {
                  var scrolledAtBottom = convParent.scrollHeight - convParent.scrollTop - convParent.offsetHeight === 0;
                  conv.innerHTML += newHTML[0];
                  if (scrolledAtBottom) {
                    convParent.scrollTop = convParent.scrollHeight;
                  }
                  newMessageCount = document.getElementsByClassName('unread received').length;
                  var newTitle = newMessageCount ? "(" + newMessageCount + ") " : "";
                  newTitle += originalTitle;
                  document.title = newTitle;
                }
                if (objs[1].length) {
                  document.getElementById('allConversations').innerHTML = newHTML[1];
                }
                break;
              case 'messageOld':
                if (objs[0].length) {
                  var previousScrollHeight = convParent.scrollHeight - convParent.scrollTop;
                  conv.innerHTML = newHTML[0] + conv.innerHTML;
                  convParent.scrollTop = convParent.scrollHeight - previousScrollHeight;
                }
                break;
            }
          }

          typeof callBack === 'function' && callBack();
        } // if
      }; // onreadystatechange

      var delimiter = /\?/.exec(url) ? '&' : '?';

      xmlhttp.open('GET', url + delimiter + "offset1=" + document.getElementsByClassName(className1).length
                                          + "&offset2=" + document.getElementsByClassName(className2).length);
      xmlhttp.setRequestHeader('Roomies','cactus');
      xmlhttp.send();
    },

    // A function to use ajax on an element
    'ajax': function (element) {
      var url = element.getAttribute('data-ajax-url'),
          originalText = element.innerHTML,
          hideText,
          xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4) {
          // Return the text to its original state
          element.innerHTML = originalText;

          // If there was anything output, new error
          if (xmlhttp.status === 404) {
            newError("Not found suck balls");
          } else if (xmlhttp.responseText) {
            newError(xmlhttp.responseText);
          } else if ((hideText = element.getAttribute('data-ajax-hide')) && xmlhttp.status === 200) {
            hideText = hideText.split(" ");

            var elementsToHide = aProto.slice.call(document.getElementsByClassName(hideText[0]));

            elementsToHide.forEach(function (element) {
              element.style.display = "none";
            });

            document.getElementById(hideText[1]).removeAttribute('style');
          } // if
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
          element.value = '';
          element.focus();
        });

        xmlhttp.send(letter);
      } else {
        xmlhttp.send();
      }

      if (element.hasAttribute('data-ajax-text')) {
        element.innerHTML = element.getAttribute('data-ajax-text');
      } // if

      var callBack;
      if (callBack = element.getAttribute('data-ajax-callback')) {
        callBack = callBack.split(" ");
        roomies[callBack[0]](callBack[1], callBack[2], callBack[3], callBack[4]);
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
    var target, targets, targetWasAlreadyHidden = false;

    // If a target needs toggling, check if it was already hidden
    if (target = document.getElementById(element.getAttribute('data-toggle'))) {
      targetWasAlreadyHidden = / hidden /.exec(target.className);
    }

    // Get an array of all the minidrops and drops that the current element is in
    var elementsToShowAgain = roomies['getDrops'](element);
    // Hide all drops and minidrops
    roomies['hide'](document.getElementsByClassName('drop'));
    roomies['hide'](document.getElementsByClassName('minidrop'));
    // Show the previous elements again
    elementsToShowAgain.forEach(function (elementToShow) {
      roomies['toggle'](elementToShow);
    });

    // If a target was hidden and needs toggling, toggle it
    if (target && targetWasAlreadyHidden) {
      roomies['toggle'](target);
    } // if

    // If a target needs deleting, do so.
    if (target = document.getElementById(element.getAttribute('data-delete'))) {
      roomies['delete'](target);
    } // if

    // If the element employs ajax, do some ajax.
    if (element.hasAttribute('data-ajax-url')) {
      roomies['ajax'](element);
      return false;
    } // if

    // If the element is a scroll bar, scroll something.
    // if (element.className === 'scroll-tracker') {
    //   roomies['scroll'](element.parentNode, e.clientY);
    //   return false;
    // } else if (element.className === 'scroll-bar') {
    //   roomies['scroll'](element, e.clientY);
    //   return false;
    // }
  }; // onclick

  /**
   * A function to detect if the mouse has been pressed
   */
  window.onmousedown = function (e) {
    target = e.target;
    var element = target;
    mouseIsDown = true;
    if (element.className === 'scroll-tracker') {
      roomies['scroll'](element.parentNode, e.clientY);
      return false;
    } else if (element.className === 'scroll-bar') {
      roomies['scroll'](element, e.clientY);
      return false;
    }
  };

  /**
   * A function to detect if the mouse has been released
   */
  window.onmouseup = function () {
    mouseIsDown = false;
  };

  /**
   * A function to clear the selected text
   */
  var clearSelection = function (element) {
    var selection;

    if (document.selection && document.selection.empty) {
      document.selection.empty();
    } else if (window.getSelection && (selection = window.getSelection()) && selection.removeAllRanges) {
      selection.removeAllRanges();
    }
  };

  /**
   * A function to detect mouse movement
   */
  window.onmousemove = function (e) {
    // Shortcut the element
    var element = target;

    // If the mouse is down and the target is a scrollbar or the like, then scroll
    if (mouseIsDown) {
      // If the element is a scroll bar, scroll something.
      if (element.className === 'scroll-tracker') {
        element = element.parentNode;
      }

      if (element.className === 'scroll-bar') {
        roomies['scroll'](element, e.clientY);
        clearSelection();
        e.preventDefault();
      }
    }
  }

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
      boxShadowElements = boxShadowElements.concat(aProto.slice.call(document.getElementsByClassName("header")));
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

  var gettingNextSet = false; // for message box
  var scrollAreaFunc = function (element) {
    if (!element.getElementsByClassName('scroll-bar').length && element.scrollHeight > element.offsetHeight) {
      element.innerHTML += "<div class='scroll-bar'><div class='scroll-tracker'></div></div>";
    }

    if (element.hasAttribute('data-message-id') && element.scrollTop < 100 && !gettingNextSet) {
      gettingNextSet = true;
      roomies['update']('messageOld', '../php/update_message.process.php?type=old&otherId=' + element.getAttribute('data-message-id'), 'message', null,
        function () {
          gettingNextSet = false;
        }
      );
    }

    var boxShadow = "none";
    if (element.scrollTop < element.scrollHeight - element.offsetHeight) {
        boxShadow = "inset 0 -6px 4px -4px rgba(0,0,0,0.12)";
        if (element.scrollTop > 0) {
            boxShadow += ", inset 0 6px 4px -4px rgba(0,0,0,0.12)";
        }
    } else if (element.scrollTop > 0) {
        boxShadow = "inset 0 6px 4px -4px rgba(0,0,0,0.12)";
    }
    element.style.boxShadow = boxShadow;

    var scrollTrackers;
    if ((scrollTrackers = element.getElementsByClassName('scroll-tracker')).length) {
      scrollTrackers[0].style.top = (80 * element.scrollTop / (element.scrollHeight - element.offsetHeight)) + "%";
    }
  };

  var scrollAreas = aProto.slice.call(document.getElementsByClassName('scroll-area'));
  scrollAreas.forEach(function (scrollArea) {
    if (scrollArea.scrollHeight > scrollArea.offsetHeight) {
      scrollArea.innerHTML += "<div class='scroll-bar'><div class='scroll-tracker'></div></div>";
    }
    scrollArea.onscroll = function () {
      scrollAreaFunc(this);
    };
  });

  window.roomies = roomies;
}(window)); // Localise the window
