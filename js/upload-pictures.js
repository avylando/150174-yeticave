'use strict';


(function () {

  // Constants
  var FILE_TYPES = ['jpg', 'jpeg', 'png', 'gif'];

  // DOM-Elements
  var uploadPhotoInput = document.querySelector("#photo2"),
      photoPreview = document.querySelector('.preview__img > img'),
      photoContainer = document.querySelector('.photo__container'),
      progressBar = document.querySelector(".preview__progress");;

  // Useful functions

  var checkFileValidity = function (file, fileTypes) {
    var fileName = file.name.toLowerCase();
    var fileTypeValidity = fileTypes.some(function (type) {
      return fileName.endsWith(type);
    });

    return fileTypeValidity;
  };

  var readImageFile = function (image, container, callback, progress = null) {
    var reader = new FileReader();

    if (progress) {
      reader.addEventListener('progress', function (event) {
        console.log(event);
        if (event.lengthComputable) {
          progress.max = event.total;
          progress.value = event.loaded;
        }
      });

      reader.addEventListener('loadend', function (event) {
        var contents = event.target.result,
        error = event.target.error;
        if (error != null) {
          console.error("File could not be read! Code " + error.code);
        } else {
          progress.max = 1;
          progress.value = 1;
        }

        progress.style.display = 'none';
        container.style.display = 'block';
        callback(reader, container);
      });

    } else {
      reader.addEventListener('load', function () {
        container.style.display = 'block';
        callback(reader, container);
      });
    }

    reader.readAsDataURL(image);
  };

  var setImageSrc = function (fileReader, container) {
    container.src = fileReader.result;
  };

  var createImageInContainer = function (fileReader, container) {
    var photo = document.createElement('img');
    photo.src = fileReader.result;
    photo.classList.add('photo');
    container.appendChild(photo);
  };


  // Image upload

  if (uploadPhotoInput && photoPreview && photoContainer) {
    var removeBtn = document.querySelector(".preview__remove");

    uploadPhotoInput.addEventListener('change', function () {
      var photo = uploadPhotoInput.files[0];

      if (checkFileValidity(photo, FILE_TYPES)) {
        readImageFile(photo, photoPreview, setImageSrc, progressBar);
        photoContainer.style.display = 'block';
        progressBar.style.display = 'block';
      }

      removeBtn.addEventListener('click', function () {
        uploadPhotoInput.value = '';
        photoPreview.src = '';
        photoContainer.style.display = 'none';
        photoPreview.style.display = 'none';
      }, false);
    });
  }

})();
