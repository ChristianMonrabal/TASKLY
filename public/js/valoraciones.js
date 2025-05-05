document.addEventListener('DOMContentLoaded', function() {
    stars = document.querySelectorAll('#stars i');
    selectedRating = 0;

    stars.forEach(function(star) {
        star.addEventListener('click', function() {
            selectedRating = star.getAttribute('data-value');
            updateStars(selectedRating);
        });
    });

    function updateStars(rating) {
        stars.forEach(function(star) {
            star.classList.remove('selected');
            if (parseInt(star.getAttribute('data-value')) <= rating) {
                star.classList.add('selected');
            }
        });
    }

    imageInput = document.getElementById('imagen1');
    imageError = document.getElementById('image-error');

    function validateImages() {
        if (!imageInput.files.length) {
            imageError.style.display = 'inline';
        } else {
            imageError.style.display = 'none';
        }
    }

    commentInput = document.getElementById('comentario');
    commentError = document.getElementById('comment-error');

    function validateComment() {
        if (commentInput.value.trim() === '') {
            commentError.style.display = 'inline';
        } else {
            commentError.style.display = 'none';
        }
    }

    commentInput.addEventListener('blur', validateComment);

    submitButton = document.querySelector('button[type="submit"]');
    submitButton.addEventListener('click', function(event) {
        validateComment();
        validateImages();

        if (commentInput.value.trim() === '' || !imageInput.files.length) {
            event.preventDefault();
        }
    });
});

function previewImage(event, previewId) {
    file = event.target.files[0];
    preview = document.getElementById(previewId);

    if (file) {
        reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
