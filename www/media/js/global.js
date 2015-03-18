try{
!Array.isArray && (Array.isArray = function(arg) {
  return Object.prototype.toString.call(arg) === '[object Array]';
});

void function (undefined) {
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
    }],
    "stringOrArray": [false,
    function (variable) {
      return is.string[1](variable) || is.array[1](variable);
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

    if (variables.length > types.length || (Array.isArray(lastElement) && variables.length < types.length - lastElement.length)) {
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
} ();

void function (window, document, undefined) {
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
  hasFocus = true,

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
  getMessageDropItemByConvId = function (convId, dontCreateNew) {
    validate(arguments, "stringable", ["boolean"]);

    // Preset the messageDropItem
    var messageDropItem = null;
    // Cast messageId to a string
    convId += "";
    // Loop through all the message drop items, and find the one with the convid
    forEach.call(body.getElementsByClassName("message-drop-item"), function (element) {
      element.getAttribute("data-conv-id") === convId && (messageDropItem = element);
    });
    // If the messageDropItem is still null, create a new one
    dontCreateNew || (
      messageDropItem === null && (
        // <li
        messageDropItem = document.createElement("li"),
        // class="drop-item message-drop-item"
        messageDropItem.className = " drop-item message-drop-item ",
        // data-conv-id=convId>
        messageDropItem.setAttribute("data-conv-id", convId)
      ),
    
      // Insert after the placeholder
      messageDropList.childNodes.length > 1
      ? messageDropList.insertBefore(messageDropItem, messageDropList.childNodes[1])
      : messageDropList.appendChild(messageDropItem)
    );

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
    box: []
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

  configureSelector = function (element) {
    var
    toggler = document.getElementById(element.parentNode.children[0].getAttribute('data-toggle'));
    hiddenRegex.test(toggler.className)
    ||  roomies[some.call(element.firstChild.children, function (el) {
          return !hiddenRegex.test(el.className);
        })?'show':'hide']([element.parentNode]);

    forEach.call(element.getElementsByClassName('selector-item'), function (item) {
      item.style.paddingRight = element.offsetWidth - element.clientWidth + 6 + "px";
    });
    var boxShadow = "0 -2px 0 -1px #fff,0 3px 6px rgba(0,0,0,.24)";
    if (element.scrollTop < element.scrollHeight - element.offsetHeight) {
        boxShadow += ", inset 0 -6px 4px -4px rgba(0,0,0,0.12)";
        if (element.scrollTop > 0) {
            boxShadow += ", inset 0 6px 4px -4px rgba(0,0,0,0.12)";
        }
    } else if (element.scrollTop > 0) {
        boxShadow += ", inset 0 6px 4px -4px rgba(0,0,0,0.12)";
    }
    element.style.boxShadow = boxShadow;
  },

  configureScrollArea = function (element) {
    var scrollBars = element.getElementsByClassName('scroll-bar');
    var minusScrollBarWidth = element.clientWidth - element.offsetWidth;
    var convstn = element.getElementsByClassName('conversation')[0];
    var convId = convstn && convstn.getAttribute("data-conv-id"), convBox;
    var grpId = convstn && convstn.getAttribute("data-group-id");

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

    if (convstn && (convBox = conv.box[convId]) && !convBox.fetchingPrevious && element.scrollTop < 200) {
      convBox.fetchingPrevious = true;
      roomies.ajax({
        url: '../php/update_message.process.php?type=old&otherId=' + convId + "&offset1=" + convstn.getElementsByClassName("message").length + "&offset2=0&gid=" + grpId,
        success: function (response) {
          response = response[0];
          var newHTML = "";
          forEach.call(response, function (message) {
            response.template.forEach(function (templatePart, i, template) {
              newHTML += templatePart + (i < template.length - 1 ? message[i] : "");
            });
          });

          if (response.length) {
            var previousScrollHeight = element.scrollHeight - element.scrollTop;
            convstn.innerHTML = newHTML + convstn.innerHTML;
            element.scrollTop = element.scrollHeight - previousScrollHeight;
          }
        },
        complete: function () {
          convBox.fetchingPrevious = false;
        }
      });
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
    // A function to focus on an element by its id
    "focusById": function (id) {
      (document.getElementById(id) || {focus:function(){}}).focus();
    },

    // A function to hide a list of elements
    'hide': function (elements) {
      validate(arguments, "HTMLCollection");

      forEach.call(elements, function (element) {
        element.disabled === false && (element.disabled = true);
        !hiddenRegex.test(element.className) && (element.className += "hidden ");
      });
    },

    'show': function (elements) {
      validate(arguments, "HTMLCollection");

      forEach.call(elements, function (element) {
        element.disabled && (element.disabled = false);

        while (hiddenRegex.test(element.className)) {
          element.className = element.className.replace(hiddenRegex, ' ');
        }

        // Get any scroll areas and ensure they have a scrollbar
        forEach.call(element.getElementsByClassName('scroll-area'), function (scrollArea) {
          configureScrollArea(scrollArea);
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

    'markAsRead': function (convId) {
      validate(arguments, "numeric");

      var exampleConv = getElementsByConvId(convId)[0];
      exampleConv && exampleConv.getElementsByClassName("unread received").length && roomies.ajax({
        url: info.webRoot + "/php/read_conversation.process.php",
        post: [{name: "convId", value: convId}],
        success: function (response) {
          response.forEach(function (messageId) {
            getElementsByMessageId(messageId).forEach(function (element) {
              element.className = element.className.replace(" unread ", " read ");
            });
          });

          var messageDropLink = getMessageDropItemByConvId(convId, true);
          if (messageDropLink && (messageDropLink = messageDropLink.firstChild) && / received /.test(messageDropLink.className)) {
            messageDropLink.className = messageDropLink.className.replace(" unread ", " read ");
          }
          roomies.updateUnreadReceived();
          roomies.updateNofifCount();
          roomies.updateTitle();
        }
      });
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
      slice.call(messageDropList.childNodes, 1).forEach(function (childNode) {
        var convId = childNode.getAttribute('data-conv-id');
        childNode.firstChild.childNodes[1].setAttribute("data-unread-count", conv.unread.received[convId] || 0);
      });
    },

    "resetUnreadReceived": function (prevNo, convId) {
      conv.unread.received[convId] = 0;
    },

    "updateUnreadReceived": function () {
      // Reset the unread received message counter
      conv.unread.received.forEach(roomies.resetUnreadReceived);
      // Loop through all unread received messages and add the user id (uniquely) to the unread received ids
      forEach.call(body.getElementsByClassName('unread received message'), function (message) {
        var convId = roomies.getParentsByClassName(message, 'conversation')[0];
        (convId = convId && convId.getAttribute('data-conv-id'))
        && (conv.unread.received[convId] = (conv.unread.received[convId] || 0) + 1);
      });
    },

    "updateTitle": function () {
      var count = 0;
      conv.unread.received.forEach(function(n){n&&count++;console.log(n)});
      document.title = (count ? "(" + count + ") " : "") + originalTitle;
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

    // Ajax function
    "ajax": function (obj) {
      validate(arguments, "object");
      var args = [obj.url];
      args.push(obj.success  || function () {});
      args.push(obj.error  || function () {});
      args.push(obj.complete || function () {});
      args.push(obj.callback || function () {});
      args.push(obj.post     || "");
      args.push(obj.reset    || false);
      validate(args, "string", "function", "function", "function", "function", "stringOrArray", "boolean");

      var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
      var postValues = [];
      var addValueToPostValues = function (key, element, dontResetValue) {
        if (!element) return;

        console.debug(element);

        var value;
        if (element.type === "checkbox") {
          value = element.checked;
        } else if (element.value) {
          value = element.value.trim();
          dontResetValue || element.nodeName !== "TEXTAREA" || (
            element.value = "",
            element.oninput && element.oninput()
          );
        } else if (element.length) {
          forEach.call(element, function (element) {
            element.checked && (value = element.value);
          });
        }
        if (value !== undefined) {
          postValues.push(key + "=" + encodeURIComponent(value));
        }
      };

      xmlhttp.onreadystatechange = function () {
        var response;
        if (xmlhttp.readyState === 4 && xmlhttp.status) {
          if (xmlhttp.status === 503) {
            // If 503, then the server is being a cunt.
          } else if (xmlhttp.status !== 200) {
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

          obj.complete && setTimeout(obj.complete, 0);
        } // if
      }

      xmlhttp.open((obj.post ? "POST" : "GET"), obj.url);
      xmlhttp.setRequestHeader("Roomies", "cactus");
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      if (obj.post) {
        switch (validate.which(obj.post)) {
          case "string":
          // get the first element and assume it is a form
          /*
          If you have a form, you need to supply its ID and the name of its inputs in a string, sepparated by ' ', in 'data-ajax-post'
          */
          var form = document.getElementById(obj.post.split(" ")[0]) || {};
          // if the "form" is actually a form:
          if (form.nodeName === "FORM") {
            obj.post.split(" ").slice(1).forEach(function (elementName) {
              addValueToPostValues(elementName, form.elements[elementName]);
            });
          } else {
            obj.post.split(" ").forEach(function (id) {
              addValueToPostValues(id, document.getElementById(id));
            });
          }
          break;

          case "array":
          obj.post.forEach(function (input) {
            postValues.push(input.name + "=" + input.value);
          });
          break;
        }

        obj.focusId && document.getElementById(obj.focusId).focus();
        xmlhttp.send(postValues.join("&"));
      } else {
        xmlhttp.send();
      }

      obj.callback && setTimeout(obj.callback, 0);
    }
  },

  // Object to store functions to be called by ajax upon success of a request
  successFunctions = {
  };

  /**
   * A function to handle click events on the window
   */
  window.onclick = function (e) {
    // If the button press is not the left button, then return true.
    if ((e.which && e.which !== 1) || (e.button !== 1 && e.button !== 0) || e.target.disabled) {
      return true;
    } // if

    var
    // Localise the element that was clicked and its className
    element = e.target,
    // Localise the class string of the target
    className = element.className,
    // Localise variables for later use
    target = document.getElementById(element.getAttribute("data-toggle")),
    targets,
    // Get an array of all the drops that the current element is in
    exceptions = validate.bool([element], "element")
                 ? roomies.getParentsByClassName(element, 'drop')
                 : [];
    // Add any parent selector's togglers
    roomies.getParentsByClassName(element, "selector").forEach(function(element){
      while((element=element.previousSibling)&&element.nodeType!==1);
      /(^| )selector-toggler( |$)/.test(element.className)&&exceptions.push(element);
      e
    });
    // Add the toggle target if it exists
    target && exceptions.push(target);
    // Function to optionally hide things
    var optDo = function (className, action) {
      roomies[action] && forEach.call(body.getElementsByClassName(className), function (element) {
        exceptions.indexOf(element) === -1 && roomies[action]([element]);
      });
    };
    // Hide the slim
    !roomies.getParentsByClassName(element, "slim").length
    && roomies.show([document.getElementById('slim-toggler')]);
    // Hide all drops, except those in the elements to keep open
    optDo("drop", "hide");
    optDo("selector-toggler", "show");

    // If targets needs hiding, hide them
    (targets = document.getElementsByClassName(element.getAttribute("data-hide"))).length && roomies.hide(targets);
    // If targets needs showing, show them
    (targets = document.getElementsByClassName(element.getAttribute("data-show"))).length && roomies.show(targets);
    // If a target needs toggling, toggle it
    (target) && roomies.toggle(target);
    // If a target needs deleting, delete it
    (target = document.getElementById(element.getAttribute("data-delete"))) && roomies["delete"](target);

    // Configure selectors
    forEach.call(body.getElementsByClassName('selector-content'), function (selector) {
      selector.onscroll && selector.onscroll();
    });

    // If the element employs ajax, do some ajax.
    var ajaxUrl = element.getAttribute("data-ajax-url");
    if (ajaxUrl) {
      var originalText = element.innerHTML;

      roomies.ajax({
        url: ajaxUrl,
        success: function (response) {
          var successFunctionName = element.getAttribute("data-ajax-success");
          successFunctionName && successFunctions[successFunctionName]
                              && successFunctions[successFunctionName](response);

          var hideText = element.getAttribute("data-ajax-hide");
          if (hideText) {
            hideText = hideText.split(" ");

            forEach.call(body.getElementsByClassName(hideText[0]), function (element) {
              element.style.display = "none";
            });

            document.getElementById(hideText[1]).removeAttribute("style");
          }
        },
        complete: function () {
          element.innerHTML = originalText;
        },
        callback: function () {
          if (element.hasAttribute("data-ajax-text")) {
            element.innerHTML = element.getAttribute("data-ajax-text");
          } // if

          var callback, i;
          for (i = 1; callback = element.getAttribute("data-ajax-callback-"+i); i += 1) {
            callback = callback.split(" ");
            callback[4] ? roomies[callback[0]](callback[1], callback[2], callback[3], callback[4]) :
            callback[3] ? roomies[callback[0]](callback[1], callback[2], callback[3]) :
            callback[2] ? roomies[callback[0]](callback[1], callback[2]) :
            callback[1] ? roomies[callback[0]](callback[1]) :
            callback[0] ? roomies[callback[0]]() : undefined;
          } // if
        },
        post: element.getAttribute("data-ajax-post")
      });
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

  /**
   * A function to detect keydown
   */
  window.onkeydown = function (e) {
    e.keyCode===27&&((document.getElementById("errorList")||{}).innerHTML="");
    return true;
  };

  // Loop through all elements in the body and ensure that
  // the className contains a space at the start and end,
  // for manipulating classNames later.
  var elementToFocus;
  concat.apply(body, body.getElementsByTagName('*')).forEach(function (element) {
    element.className = ' ' + element.className + ' ';
    element.hasAttribute("data-focus") && (elementToFocus = element);
  });

  // When the page loads, the user scrolls or the window is resized, configure things
  window.onscroll = window.onresize = window.onload = configure;

  // Loop through all unread sent messages and add the conv id (uniquely) to the unread sent ids
  forEach.call(body.getElementsByClassName('unread sent message'), function (message) {
    var messageId = message.getAttribute('data-message-id');
    conv.unread.sent.indexOf(messageId) === -1 && conv.unread.sent.push(messageId);
  });

  // Update unread received count
  roomies.updateUnreadReceived();

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
      configureScrollArea(this);
    }
    configureScrollArea(scrollArea);
  });

  // Configure any selector contents
  forEach.call(body.getElementsByClassName('selector-content'), function (element) {
    element.onscroll = function () {
      configureSelector(this);
    };
    configureSelector(element);
  });

  forEach.call(body.getElementsByClassName("conversation"), function (conversation) {
    conv.box[conversation.getAttribute("data-conv-id")] = {
      fetchingPrevious: false,
      focused: false
    };
  });

  window.onfocus = function () {
    hasFocus = true;
  };
  window.onblur = function () {
    hasFocus = false;
  };

  forEach.call(body.getElementsByClassName("textarea"), function (textarea) {
    var convId;
    if (convId = textarea.getAttribute('data-conv-id')) {
      textarea.onfocus = function () {
        conv.box[convId].focused = true;
        setTimeout(function () {
          conv.box[convId].focused && roomies.markAsRead(convId);
        }, 100);
      };
      textarea.onblur = function () {
        conv.box[convId].focused = false;
      };
    }
  });

  if (info.userId) {
    // Set up longpolling
    var longpollSuccess = function (response) {
      if (response.nothingChanged) return;
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

      var toRead = [];

      newMessages.content.forEach(function (message) {
        var sent = info.userId == message[6];
        var otherId = sent ? message[7] : message[6];
        var messageHTML = "";

        newMessages.template.forEach(function (templatePart, i, template) {
          messageHTML += templatePart + (i < template.length - 1 ? message[i] : "");
        });

        getElementsByConvId(otherId).forEach(function (element) {
          var
          parent = element.parentNode,
          wasAtBottom = parent.scrollHeight - parent.scrollTop - parent.offsetHeight < 50;
          element.innerHTML += messageHTML;
          wasAtBottom && (parent.scrollTop = parent.scrollHeight);
        });

        var activeElement = document.activeElement;
        document.hasFocus() && activeElement && activeElement.getAttribute("data-conv-id") == otherId && toRead.indexOf(otherId) === -1 && toRead.push(otherId);

        getMessageDropItemByConvId(otherId).innerHTML =
          "<a href='/messages/" + message[8] + "' class=' drop-item-link " + message[0] + " '>"
        + "<span class=' drop-item-pic ' style='background-image: url(" + message[12] + ")'></span>"
        + "<h3 class=' drop-item-header ' data-unread-count='" + conv.unread.received[otherId] + "'>" + message[9] + "</h3>"
        + "<p class=' drop-item-text" + (sent?" drop-item-text-sent":"") + " '>" + message[5].split("<br>")[0].substring(0, 200) + "</p>"
        + "<p class=' drop-item-footer ' title='" + message[10] + "'>" + message[11] + "</p>"
        + "</a>";
      });

      toRead.forEach(function (convId) {
        roomies.markAsRead(convId);
      });

      roomies.updateUnreadReceived();
      roomies.updateNofifCount();
      roomies.updateTitle();

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
      forEach.call(body.getElementsByClassName('unread sent message'), function (message) {
        var messageId = message.getAttribute('data-message-id');
        conv.unread.sent.indexOf(messageId) === -1 && conv.unread.sent.push(messageId);
      });
    };
    var longpoll = function () {
      var frIds = [];
      forEach.call(body.getElementsByClassName('friend-request'), function (friendRequest) {
        var id = friendRequest.getAttribute('data-fr-id');
        !isNaN(id) && frIds.push(+id);
      });
      roomies.ajax({
        url: info.webRoot + "/php/longpoll.php",

        post: [
          {
            name: 'unread',
            value: conv.unread.sent.join(",")
          },
          {
            name: 'lastMessageId',
            value: info.lastMessageId
          },
          {
            name: "friendRequests",
            value: frIds.join(",")
          }
        ],

        success: longpollSuccess,

        complete: longpoll
      });
    };
    longpoll();
  }

  elementToFocus&&(elementToFocus.focus&&elementToFocus.focus(),elementToFocus.onfocus&&elementToFocus.onfocus());

  if (roomiesInfo)delete roomiesInfo;

  var cookieInfo;
  if (cookieInfo=rCookie.get('data-hide')) {
    roomies['hide'](document.getElementsByClassName(cookieInfo));
    rCookie.remove('data-hide');
  }
  if (cookieInfo=rCookie.get('data-show')) {
    roomies['show'](document.getElementsByClassName(cookieInfo));
    rCookie.remove('data-show');
  }
} (window, document); // Localise variables
}catch(e){newError("<strong>JavaScript Error</strong><br><br>"+e)}
