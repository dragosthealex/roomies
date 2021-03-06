(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
  console.log('statusChangeCallback');
  console.log(response);
  // The response object is returned with a status field that lets the
  // app know the current login status of the person.
  // Full docs on the response object can be found in the documentation
  // for FB.getLoginStatus().
  if (response.status === 'connected') {
    // Logged into your app and Facebook.
    //testAPI();

    // Get the FB id and access token, send them to php and check if existent
    var fbUserId = response.authResponse.userID,
        fbAccessToken = response.authResponse.accessToken,
        xmlhttp;
    // Send them through ajax to verify if logged in

    if (window.XMLHttpRequest)
    {
      xmlhttp=new XMLHttpRequest();
    }
    else {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function() {
      
      if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        if(xmlhttp.responseText) {
          if(IsJsonString(xmlhttp.responseText))
          {
            resp = JSON.parse(xmlhttp.responseText);
            if(resp.error) {
              alert (resp.error);
            }
            else {
              if(resp.response == 'notInDb') {
                window.location.replace("./complete-register/?ref=fb&tok="+fbAccessToken);
              }
            }
          }
          else
          {
            window.location.reload();
          }
        }
      }
    }
    xmlhttp.open("POST","./php/facebook_login.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("id="+fbUserId+"&acc="+fbAccessToken);

  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
    document.getElementById('status').innerHTML = 'Please log ' +
      'into this app.';
  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    document.getElementById('status').innerHTML = 'Please log ' +
      'into Facebook.';
  }
}

// Test if json
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}

window.fbAsyncInit = function() {
  FB.init({
    appId      : '327250234130442',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.1' // use version 2.1
  });
};