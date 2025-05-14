document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#stars i');
    const puntuacionInput = document.getElementById('puntuacion');
    const starError = document.getElementById('star-error');
    let selectedRating = 0;

    stars.forEach(function (star) {
        star.addEventListener('click', function () {
            selectedRating = parseInt(star.getAttribute('data-value'));
            puntuacionInput.value = selectedRating; // Asignar puntuación al input oculto
            updateStars(selectedRating);
            starError.style.display = 'none';
        });
    });

    function updateStars(rating) {
        stars.forEach(function (star) {
            star.classList.remove('selected');
            if (parseInt(star.getAttribute('data-value')) <= rating) {
                star.classList.add('selected');
            }
        });
    }

    const imageInput = document.getElementById('imagen');
    const commentInput = document.getElementById('comentario');
    const commentError = document.getElementById('comment-error');

    function validateComment() {
        if (commentInput.value.trim() === '') {
            commentError.style.display = 'inline';
            return false;
        } else {
            commentError.style.display = 'none';
            return true;
        }
    }

    function validateStars() {
        if (puntuacionInput.value === '') {
            starError.style.display = 'inline';
            return false;
        } else {
            starError.style.display = 'none';
            return true;
        }
    }

    function validateForm() {
        const isCommentValid = validateComment();
        const isStarsValid = validateStars();
        return isCommentValid && isStarsValid;
    }

    commentInput.addEventListener('blur', validateComment);
});

function previewImage(event, previewId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);

    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
