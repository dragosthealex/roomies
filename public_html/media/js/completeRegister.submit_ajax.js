function form_submit()
{
  var form = document.getElementsByTagName("FORM")[0],
      names = document.getElementsByTagName("INPUT"),
      firstName = names[0].value,
      lastName = names[1].value,
      selects = document.getElementsByTagName("SELECT"),
      bYear = selects[0].value,
      bMonth = selects[1].value,
      bDay = selects[2].value,
      country = selects[3].value,
      language = selects[4].value,
      gender = selects[5].value,
      randomKey = names[2].value,
      id = names[3].value;

  var xmlhttp;
  if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
    }
  else
    {// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      if(xmlhttp.responseText == "done")
      {
        document.getElementById("error").style.display = "block";
      }
      else
      {
        document.getElementById("optional_details").style.display = "block";
        document.getElementById("mandatory_details").style.display = "none";
        document.getElementById("mandatory_details").innerHTML = "";
      }
    }
  };

  xmlhttp.open("POST","process.php",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("firstName="+firstName+"&lastName="+lastName+"&bYear="+bYear+"&bMonth="+bMonth+"&bDay="+bDay+"&country="+country+"&language="+language+"&gender="+gender+"&randomKey="+randomKey+"&id="+id);
  return false;
}