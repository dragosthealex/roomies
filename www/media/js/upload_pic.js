// Show thumbnails for pictures to be uploaded
if(window.FileReader) {
  var photos = document.getElementsByClassName('photo-input'),
      i;

  for(i=0; i<photos.length; i++) {
    photos[i].addEventListener('change', insertThumbnail, false);
  }
}

function insertThumbnail(evt) {
  var files = evt.target.files,
      reader = new FileReader(),
      container = document.getElementById(evt.target.getAttribute('data-thumbnail-container')),
      filesLimit = evt.target.getAttribute('data-files-limit');

  if(container.childNodes.length >= filesLimit)
  {
    container.removeChild(container.firstChild);
  }

  for(i=0; i<files.length; i++) {
    var f = files[i];

    reader.onload = (function(theFile) {
      return function(e) {
        var img;
        img = document.createElement('DIV');
        img.style.background = 'url(' + e.target.result + ');';
        img.style.backgroundSize = 'cover';
        img.style = 'background: url(' + e.target.result + '); background-size:cover;';
        img.className = 'acc-pic short';
        img.name = theFile.name;

        container.appendChild(img);
      }
    })(f);
    reader.readAsDataURL(f);
  }
}