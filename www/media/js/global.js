!Array.isArray && (Array.isArray = function(arg) {
  return Object.prototype.toString.call(arg) === '[object Array]';
});

(function (undefined) {
  var

  // Functions for testing types of variables
  is = {
    "string": [true,
    function (variable) {
      return typeof variable === "string";
    }],
    "number": [true, function (variable) {
      return typeof variable === "number";
    }],
    "stringable": [false,
    function (variable) {
      return is.string[1](variable) || is.number[1](variable);
    }],
    "numeric": [false,
    function (variable) {
      return !is.boolean[1](variable) && (variable >= 0 || variable < 0);
    }],
    "function": [true,
    function (variable) {
      return typeof variable === "function";
    }],
    "object": [false,
    function (variable) {
      return typeof variable === "object";
    }],
    "boolean": [true,
    function (variable) {
      return typeof variable === "boolean";
    }],
    "array": [true,
    function (variable) {
      return Array.isArray(variable);
    }],
    "element": [true,
    function (variable) {
      return !!variable && variable.nodeType === 1;
    }],
    "HTMLCollection": [true,
    function (variable) {
      return !Array.prototype.some.call(variable, function (element) {
        return !is.element[1](element);
      });
    }],
    "null": [true,
    function (variable) {
      return variable === null;
    }],
    "undefined": [true,
    function (variable) {
      return variable === undefined;
    }]
  },

  // Function for testing a type of a variable
  check = function (variable, type, optional) {
    if (!(type in is && type !== undefined)) {
      throw new Error("Invalid type: " + type)
    }

    if (optional ? !(variable === undefined || is[type][1](variable)) : !is[type][1](variable)) {
      throw new TypeError("Expected " + type + ", got " + typeof variable);
    }
  },

  doValidate = function (args) {
    var
    variables = args[0],
    types = Array.prototype.slice.call(args, 1),
    // Pop the last element off and retreive it.
    lastElement = types.pop(),
    arrayStartIndex = -1;

    if (args.length < 2) {
      throw new Error("Must supply at least two arguments.");
    }

    // If the last element is an array, add each element of it.
    if (Array.isArray(lastElement)) {
      arrayStartIndex = types.length;
      lastElement.forEach(function (element) {
        types.push(element);
      });
    } else if (lastElement !== undefined) {
      // If not array, concatenate the last element back on if it was there.
      types.push(lastElement);
    }

    if (variables.length !== types.length) {
      throw new Error("Variables supplied: " + variables.length + ". Types supplied: " + types.length);
    }

    types.forEach(function (typeExpected, i) {
      check(variables[i], typeExpected, i >= arrayStartIndex);
    });
  };

  window.validate = function () {
    doValidate(arguments);
  };

  window.validate.bool = function () {
    try {
      doValidate(arguments);
      return true;
    } catch (error) {
      return false;
    }
  };

  window.validate.which = function (variable) {
    var type;
    for (type in is) {
      if (is[type][0] && validate.bool([variable], type)) {
        return type;
      }
    }
    return "object";
  };
}());

(function (window, document, undefined) {
  var
  // Localise <html>, <body>, originalTitle, header and newError()
  html = document.documentElement,
  body = document.body,
  main = body.getElementsByClassName('main')[0],
  originalTitle = document.title,
  header = body.getElementsByClassName('header')[0],
  newError = window.newError,
  frequestsDrop = document.getElementById('frequests-drop'),
  frequestsDropList = document.getElementById('frequests-drop-list'),
  messageDrop = document.getElementById('message-drop'),
  messageDropList = document.getElementById('message-drop-list'),

  // Localise some array methods
  slice   = Array.prototype.slice,
  forEach = Array.prototype.forEach,
  some    = Array.prototype.some,
  concat  = Array.prototype.concat,

  // Function for validating types of variables
  validate = window.validate,

  // Function for getting message elements by their message id
  getElementsByMessageId = function (messageId) {
    validate(arguments, "stringable");

    // Preset the array of elements
    var elements = [];
    // Cast messageId to a string
    messageId += '';
    // Loop through all the messages, and find the ones with the message id
    forEach.call(body.getElementsByClassName('message'), function (element) {
      element.getAttribute('data-message-id') === messageId && elements.push(element);
    });
    // Return the list of elements
    return elements;
  },

  // Function for getting message elements by their message id
  getElementsByConvId = function (convId) {
    validate(arguments, "stringable");

    // Preset the array of elements
    var elements = [];
    // Cast messageId to a string
    convId += '';
    // Loop through all the messages, and find the ones with the message id
    forEach.call(body.getElementsByClassName('conversation'), function (element) {
      element.getAttribute('data-conv-id') === convId && elements.push(element);
    });
    // Return the list of elements
    return elements;
  },

  // Function for getting message drop items by their conv id
  getMessageDropItemByConvId = function (convId) {
    validate(arguments, "stringable");

    // Preset the messageDropItem
    var messageDropItem = null;
    // Cast messageId to a string
    convId += "";
    // Loop through all the message drop items, and find the one with the convid
    forEach.call(body.getElementsByClassName("message-drop-item"), function (element) {
      element.getAttribute("data-conv-id") === convId && (messageDropItem = element);
    });
    // If the messageDropItem is still null, create a new one
    messageDropItem === null && (
      // <li
      messageDropItem = document.createElement("li"),
      // class="drop-item message-drop-item"
      messageDropItem.className = " drop-item message-drop-item ",
      // data-conv-id=convId>
      messageDropItem.setAttribute("data-conv-id", convId)
    );
    
    // Insert after the placeholder
    messageDropList.childNodes.length > 1
    ? messageDropList.insertBefore(messageDropItem, messageDropList.childNodes[1])
    : messageDropList.appendChild(messageDropItem);

    // Return the messageDropItem
    return messageDropItem;
  },

  // Function for getting message elements by their friend request id
  getElementsByRequestId = function (requestId) {
    validate(arguments, "stringable");

    // Preset the array of elements
    var elements = [];
    // Cast messageId to a string
    requestId += '';
    // Loop through all the messages, and find the ones with the message id
    forEach.call(body.getElementsByClassName('friend-request'), function (element) {
      element.getAttribute('data-fr-id') === requestId && elements.push(element);
    });
    // Return the list of elements
    return elements;
  },

  // A function to get the current size of the page
  size = function () {
    return {
      width:  window.innerWidth  || html.clientWidth  || body.clientWidth,
      height: window.innerHeight || html.clientHeight || body.clientHeight
    };
  },

  // A function to return the offset of an element from the document
  offset = function (element) {
    validate(arguments, "element");
    var x = 0, y = 0;
    while (element && !isNaN(element.offsetLeft) && !isNaN(element.offsetTop)) {
        x += element.offsetLeft - element.scrollLeft;
        y += element.offsetTop - element.scrollTop;
        element = element.offsetParent;
    }
    return {top: y, left: x};
  },

  // Variable to hold things related to conversations
  conv = {
    // Variable to hold ids of unread messages
    unread: {
      sent: [],
      received: []
    },
    // Variable to hold the ids of all the conversation boxes,
    // along with information about them
    box: {}
  },

  // Variable to hold the info set by the server
  info = window.roomiesInfo,

  // Variable to hold whether the mouse is down
  mouseIsDown = false,
  // Variable to refer to the target element on mouse down
  target,

  // Variable to hold the number of notifications
  numberOfNotifications = 0,

  // Variable to hold the regex for the hidden classname
  hiddenRegex = /(^| )hidden( |$)/,

  scrollAreaFunc = function (element) {
    var scrollBars = element.getElementsByClassName('scroll-bar');
    var minusScrollBarWidth = element.clientWidth - element.offsetWidth;
    var convstn = element.getElementsByClassName('conversation');
    var convId = !!convstn.length && convstn[0].getAttribute("data-conv-id"), convBox;

    if (minusScrollBarWidth) {
      forEach.call(element.childNodes, function (child) {
        if (child.style && !/ scroll-bar /.test(child.className)) {
          child.style.marginRight = minusScrollBarWidth + "px";
        }
      });
    }

    if (!scrollBars.length && element.scrollHeight > element.offsetHeight) {
      element.innerHTML += "<div class=' scroll-bar '><div class=' scroll-tracker '></div></div>";
      scrollBars = element.getElementsByClassName('scroll-bar');
    }

    if (element.scrollHeight <= element.offsetHeight && scrollBars.length) {
      forEach.call(scrollBars, function (scrollBar) {
        scrollBar.parentNode.removeChild(scrollBar);
      });
    }

    if (convId && (convBox = conv.box[convId]) && !convBox.fetchingPrevious && element.scrollTop < 200) {
      convBox.fetchingPrevious = true;
      roomies.update('messageOld', '../php/update_message.process.php?type=old&otherId=' + convId, 'message', null,
        function () {
          convBox.fetchingPrevious = false;
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

    if (scrollBars[0] && scrollBars[0].firstChild) {
      var scrollTracker = scrollBars[0].firstChild;
      var scrollTrackerHeight = (100 * element.offsetHeight / element.scrollHeight);
      scrollTracker.style.height = scrollTrackerHeight + "%";
      scrollTracker.style.top = ((100 - scrollTrackerHeight) * element.scrollTop / (element.scrollHeight - element.offsetHeight)) + "%";
    }
  },

  // A function to configure things based on the window size and distance from top
  configure = function () {
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

    // Get all of the elements which need a box-shadow, and possibly the header
    // For each element which needs a box-shadow, apply one
    (modifyHeader ? [header] : []).concat(
      slice.call(main.getElementsByClassName("box")),
      slice.call(main.getElementsByClassName("column-box"))
    ).forEach(function (element) {
      element.style.boxShadow = boxShadow;
    });

    forEach.call(body.getElementsByClassName('drop'), function (drop) {
      // roomies['toggle'](drop);
      var dropParent = roomies.getParentsByClassName(drop, 'drop-parent')[0];
      var right = (dropParent.parentNode.offsetWidth - (dropParent.offsetLeft + dropParent.offsetWidth) + (dropParent.offsetWidth / 2) - 8);
      var dropIcon = drop.getElementsByClassName('drop-icon')[0];
      var dropIconBorder = drop.getElementsByClassName('drop-icon-border')[0];
      roomies[right <= 304 ? "show" : "hide"]([dropIcon, dropIconBorder]);
      if (right <= 304) {
        drop.getElementsByClassName('drop-icon')[0].style.right = right + "px";
        drop.getElementsByClassName('drop-icon-border')[0].style.right = right + "px";
      }
      // roomies['toggle'](drop);
    });
  },

  // An object which holds javascript functions for interactivity
  roomies = {
    // A function to hide a list of elements
    'hide': function (elements) {
      validate(arguments, "HTMLCollection");

      forEach.call(elements, function (element) {
        !hiddenRegex.test(element.className) && (element.className += "hidden ");
      });
    },

    'show': function (elements) {
      validate(arguments, "HTMLCollection");

      forEach.call(elements, function (element) {
        while (hiddenRegex.test(element.className)) {
          element.className = element.className.replace(hiddenRegex, ' ');
        }

        // Get any scroll areas and ensure they have a scrollbar
        forEach.call(element.getElementsByClassName('scroll-area'), function (scrollArea) {
          scrollAreaFunc(scrollArea);
        });
      });
    },

    // A function to toggle the visibility of an element
    'toggle': function (element) {
      validate(arguments, "element");

      // If the element is hidden, show it, else hide it
      roomies[hiddenRegex.test(element.className) ? "show" : "hide"]([element]);
    },

    // A function to delete an element
    'delete': function (element) {
      validate(arguments, "element");

      element.parentNode && element.parentNode.removeChild(element);
    },

    // A function to delete an element, given an id
    'deleteById': function (id) {
      validate(arguments, "string");

      roomies['delete'](document.getElementById(id));
    },

    // A function to delete a list of elements, given a className
    'deleteByClassName': function (className) {
      validate(arguments, "string");

      forEach.call(document.getElementsByClassName(className), function (element) {
        roomies['delete'](element);
      });
    },

    'scrollToBottom': function (id, parent) {
      validate(arguments, "string", "numeric");

      var element = document.getElementById(id), i;
      if (element) {
        for (i = 0; i < parent; i += 1) {
          element = element.parentNode;
        }
        element.scrollTop = element.scrollHeight;
      }
    },

    // A function to return an array of all parent drops
    'getParentsByClassName': function (element, className) {
      validate(arguments, "element", "string");

      return (!element || element === body)
              ? []
              : new RegExp('(^| )'+className+'( |$)').test(element.className)
                ? [element].concat(roomies.getParentsByClassName(element.parentNode, className))
                : roomies.getParentsByClassName(element.parentNode, className);
    },

    // A function to update the notif counts
    'updateNofifCount': function () {
      frequestsDrop.nextSibling.setAttribute('data-icon-number', frequestsDrop.getElementsByClassName('friend-request').length);
      messageDrop.nextSibling.setAttribute('data-icon-number', messageDrop.getElementsByClassName('drop-item-link unread received').length);
    },

    // A function to scroll an scroll thingy, given the scrollbar element and the distance from the top of the element
    'scroll': function (element, mouseY) {
      validate(arguments, "element", "number");

      mouseY += (window.pageYOffset || html.scrollTop || body.scrollTop || 0);
      mouseY -= offset(element).top;
      var boxHeight = element.offsetHeight;
      var trackerHeight = element.firstChild.offsetHeight / boxHeight;
      element.parentNode.scrollTop = (element.parentNode.scrollHeight - boxHeight) * ((mouseY - (trackerHeight / 2) * boxHeight) / (1 - trackerHeight)) / boxHeight;
    },

    // A function to update something in the page
    'update': function (part, url, className1, className2, callback) {
      validate(arguments, "string", "string", ["string", "string", "function"]);

      var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status) {
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

            var convElement = document.getElementById('main_conversation');
            var convParent = convElement.parentNode;

            switch (part) {
              case 'messageNew':
                if (objs[0].length) {
                  var scrolledAtBottom = convParent.scrollHeight - convParent.scrollTop - convParent.offsetHeight === 0;
                  convElement.innerHTML += newHTML[0];
                  if (scrolledAtBottom) {
                    convParent.scrollTop = convParent.scrollHeight;
                  }
                  document.title = (conv.unread.received.length ? "(" + conv.unread.received.length + ") " : "") + originalTitle;
                }
                if (objs[1].length) {
                  document.getElementById('allConversations').innerHTML = newHTML[1];
                }
                break;
              case 'messageOld':
                if (objs[0].length) {
                  var previousScrollHeight = convParent.scrollHeight - convParent.scrollTop;
                  convElement.innerHTML = newHTML[0] + convElement.innerHTML;
                  convParent.scrollTop = convParent.scrollHeight - previousScrollHeight;
                }
                break;
            }
          }

          typeof callback === 'function' && callback();
        } // if
      }; // onreadystatechange

      var delimiter = /\?/.test(url) ? '&' : '?';

      xmlhttp.open('GET', url + delimiter + "offset1=" + document.getElementsByClassName(className1).length
                                          + "&offset2=" + document.getElementsByClassName(className2).length);
      xmlhttp.setRequestHeader('Roomies','cactus');
      xmlhttp.send();
    },

    // A function to use ajax on an element
    'ajax': function (element) {
      validate(arguments, "element");

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

            forEach.call(body.getElementsByClassName(hideText[0]), function (element) {
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

          letter += "&" + id + "=" + encodeURIComponent(element.value.trim());
          element.value = '';
          element.oninput && element.oninput();
          element.focus();
        });

        xmlhttp.send(letter);
      } else {
        xmlhttp.send();
      }

      if (element.hasAttribute('data-ajax-text')) {
        element.innerHTML = element.getAttribute('data-ajax-text');
      } // if

      var callback, i;
      for (i = 1; callback = element.getAttribute('data-ajax-callback-'+i); i += 1) {
        callback = callback.split(" ");
        callback[4] ? roomies[callback[0]](callback[1], callback[2], callback[3], callback[4]) :
        callback[3] ? roomies[callback[0]](callback[1], callback[2], callback[3]) :
        callback[2] ? roomies[callback[0]](callback[1], callback[2]) :
        callback[1] ? roomies[callback[0]](callback[1]) :
        (callback[0] && roomies[callback[0]]())
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
      targetWasAlreadyHidden = hiddenRegex.test(target.className);
    }

    // Get an array of all the drops that the current element is in
    var elementsToShowAgain = validate.bool([element], "element")
                              ? roomies.getParentsByClassName(element, 'drop')
                              : [];
    // Hide all drops
    roomies.hide(body.getElementsByClassName('drop'));
    // Show the previous elements again
    elementsToShowAgain.forEach(function (elementToShow) {
      roomies.toggle(elementToShow);
    });

    // If a target was hidden and needs toggling, toggle it
    if (target && targetWasAlreadyHidden) {
      roomies.toggle(target);
    } // if

    // If a target needs deleting, do so.
    if (target = document.getElementById(element.getAttribute('data-delete'))) {
      roomies['delete'](target);
    } // if

    // If the element employs ajax, do some ajax.
    if (element.hasAttribute('data-ajax-url')) {
      roomies.ajax(element);
      return false;
    } // if
  }; // onclick

  /**
   * A function to detect if the mouse has been pressed
   */
  window.onmousedown = function (e) {
    var element = target = e.target;
    mouseIsDown = true;

    element.className === ' scroll-tracker ' && (element = element.parentNode);

    return element.className !== ' scroll-bar ' || (roomies.scroll(element, e.clientY), false);
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
      if (element.className === ' scroll-tracker ') {
        element = element.parentNode;
      }

      if (element.className === ' scroll-bar ') {
        roomies.scroll(element, e.clientY);
        clearSelection();
        e.preventDefault();
      }
    }
  };

  // Loop through all elements in the body and ensure that
  // the className contains a space at the start and end,
  // for manipulating classNames later.
  concat.apply(body, body.getElementsByTagName('*')).forEach(function (element) {
    element.className = ' ' + element.className + ' ';
  });

  // When the page loads, the user scrolls or the window is resized, configure things
  window.onscroll = window.onresize = window.onload = configure;

  // Loop through all unread sent messages and add the conv id (uniquely) to the unread sent ids
  forEach.call(body.getElementsByClassName('unread sent message'), function (message) {
    var convId = roomies.getParentsByClassName(message, 'conversation')[0].getAttribute('data-conv-id');
    conv.unread.sent.indexOf(convId) === -1 && conv.unread.sent.push(convId);
  });

  // Loop through all unread received messages and add the user id (uniquely) to the unread received ids
  forEach.call(body.getElementsByClassName('unread received message'), function (message) {
    var convId = roomies.getParentsByClassName(message, 'conversation')[0];
    convId = convId && convId.getAttribute('data-conv-id');
    convId && (
      conv.unread.received[convId] = conv.unread.received[convId] || 0,
      conv.unread.received[convId]++
    );
  });

  // Loop through all unread received messages and add the user id (uniquely) to the unread received ids
  forEach.call(body.getElementsByClassName('unread received drop-item-link'), function (dropItemLink) {
    var convId = dropItemLink.parentNode.getAttribute('data-conv-id');
    conv.unread.received[convId] = +dropItemLink.childNodes[1].getAttribute('data-unread-count');
  });

  forEach.call(body.getElementsByClassName('scroll-area'), function (scrollArea) {
    if (scrollArea.scrollHeight > scrollArea.offsetHeight) {
      scrollArea.innerHTML += "<div class=' scroll-bar '><div class=' scroll-tracker '></div></div>";
    }
    scrollArea.onscroll = function () {
      scrollAreaFunc(this);
    }
    scrollAreaFunc(scrollArea);
  });

  forEach.call(body.getElementsByClassName("conversation"), function (conversation) {
    conv.box[conversation.getAttribute("data-conv-id")] = {
      fetchingPrevious: false
    };
  });

  // Ajax function
  function ajax(obj) {
    validate(arguments, "object");
    var args = [obj.url];
    args.push(obj.success  || function () {});
    args.push(obj.callback || function () {});
    args.push(obj.post     || "");
    args.push(obj.reset    || false);
    validate(args, "string", "function", "function", "string", "boolean");

    var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var postValues;

    xmlhttp.onreadystatechange = function () {
      var response;
      if (xmlhttp.readyState === 4 && xmlhttp.status) {
        if (xmlhttp.status !== 200) {
          newError(xmlhttp.responseText);
        } else {
          try {
            response = JSON.parse(xmlhttp.responseText);
            if (response.error) {
              newError(response.error);
            } else {
              obj.success && setTimeout(function () {
                obj.success(response)
              }, 0);
            }
          } catch (e) {
            console.error(e);
          }
        }

        obj.callback && setTimeout(obj.callback, 0);
      } // if
    }

    xmlhttp.open((obj.post ? 'POST' : 'GET'), obj.url);
    xmlhttp.setRequestHeader('Roomies','cactus');
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    if (obj.post) {
      switch (validate.which(obj.post)) {
        case "string":
        obj.post.split(" ").forEach(function (id) {
          var element = document.getElementById(id);

          element && postValues.push(id + "=" + encodeURIComponent(element.value.trim()));

          if (resetValues) {
            element.value = '';
            element.oninput && element.oninput();
          }
        });
        break;
        case "array":
        obj.forEach(function (input) {
          postValues.push(input.name + "=" + input.value);
        });
        break;
        default:
        postValues = [];
        break;
      }

      obj.focusId && document.getElementById(obj.focusId).focus();

      xmlhttp.send(postValues.join("&"));
    } else {
      xmlhttp.send();
    }
  }

  if (info) {
    // Set up longpolling
    var longpoll = function () {
      var frIds = [];
      forEach.call(body.getElementsByClassName('friend-request'), function (friendRequest) {
        var id = friendRequest.getAttribute('data-fr-id');
        !isNaN(id) && frIds.push(+id);
      });
      ajax({
        url: info.webRoot + "/php/longpoll.php?unread=" + conv.unread.sent.join(",")
                            + "&lastMessageId=" + info.lastMessageId
                            + "&friendRequests=" + frIds.join(","),

        success: function (response) {
          console.log(response);

          var
          newMessages = response.newMessages,
          readMessage = response.readMessage,
          newRequests = response.newRequests,
          oldRequests = response.oldRequests;

          newMessages.content.length &&
            (info.lastMessageId = newMessages.content[newMessages.content.length-1][1]);

          readMessage.forEach(function (messageId) {
            getElementsByMessageId(messageId).forEach(function (element) {
              element.className = element.className.replace(" unread ", " read ");
              roomies.getParentsByClassName(element, "conversation").forEach(function (element) {
                element.parentNode.scrollTop = element.parentNode.scrollHeight;
              });
            });
          });

          var unreadCount = [];

          newMessages.content.forEach(function (message) {
            var sent = info.userId == message[6];
            var otherId = sent ? message[7] : message[6];
            var messageHTML = "";

            newMessages.template.forEach(function (templatePart, i, template) {
              messageHTML += templatePart + (i < template.length - 1 ? message[i] : "");
            });

            getElementsByConvId(otherId).forEach(function (conv) {
              conv.innerHTML += messageHTML;
              conv.parentNode.scrollTop = conv.parentNode.scrollHeight;
            });

            conv.unread.received[otherId] = conv.unread.received[otherId] || 0;
            !sent && conv.unread.received[otherId]++;

            getMessageDropItemByConvId(otherId).innerHTML =
              "<a href='/messages/" + message[8] + "' class=' drop-item-link " + message[0] + " '>"
            + "<span class=' drop-item-pic ' style='background-image: url(" + message[12] + ")'></span>"
            + "<h3 class=' drop-item-header ' data-unread-count='" + conv.unread.received[otherId] + "'>" + message[9] + "</h3>"
            + "<p class=' drop-item-text" + (sent?" drop-item-text-sent":"") + " '>" + message[5] + "</p>"
            + "<p class=' drop-item-footer ' title='" + message[10] + "'>" + message[11] + "</p>"
            + "</a>";
          });

          oldRequests.forEach(function (requestId) {
            getElementsByRequestId(requestId).forEach(function (element) {
              roomies['delete'](element);
            });
          });

          newRequests.content.forEach(function (request) {
            var requestHTML = "";

            newRequests.template.forEach(function (templatePart, i, template) {
              requestHTML += templatePart + (i < template.length - 1 ? request[i] : "");
            });

            frequestsDropList.innerHTML += requestHTML;
          });

          roomies.updateNofifCount();

          // Reset the unread sent messages
          conv.unread.sent = [];
          // Loop through all unread sent messages and add the conv id (uniquely) to the unread sent ids
          forEach.call(body.getElementsByClassName('unread sent message'), function (message) {
            var convId = roomies.getParentsByClassName(message, 'conversation')[0].getAttribute('data-conv-id');
            conv.unread.sent.indexOf(convId) === -1 && conv.unread.sent.push(convId);
          });
        },

        callback: longpoll
      });
    };

    info.userId && longpoll();

    delete window.roomiesInfo;
  }

  window.roomies = roomies;
}(window, document)); // Localise variables
