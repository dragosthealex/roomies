function show(a)
{
  toHide = document.getElementsByClassName("box-content");
  for(i=0; i<toHide.length; i++)
  {
    toHide[i].style.display = "none";
  }

  var div = document.getElementById(a.getAttribute('name'));
  div.style.display="block";
}