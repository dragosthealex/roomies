function add_friend(action)
{
  if(action != 0)
  {
    var userId = document.getElementById("userId").value;
    var otherUserId = document.getElementById("otherUserId").value;

    var xmlhttp;
    if (window.XMLHttpRequest)
    {
      xmlhttp=new XMLHttpRequest();
    }
    else
    {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
      {
        if(xmlhttp.responseText)
        {
          document.getElementById("error").innerHTML = xmlhttp.responseText;
        }
        switch (action)
        {
          case 1:
            document.getElementById("add_friends_button").innerHTML = "Request sent";
            document.getElementById("add_friends_button").setAttribute("id", "sent_friends_button");
            break;
          case 3:
            document.getElementById("received_friends_button").innerHTML = "Friends!";
            document.getElementById("received_friends_button").setAttribute("id", "already_friends_button");
        }
      }
    }
    xmlhttp.open("POST", "../php/friends.process.php");
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("action="+action+"&userId="+userId+"&otherUserId="+otherUserId);
  }
  return false;
}