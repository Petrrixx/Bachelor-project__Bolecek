document.addEventListener("DOMContentLoaded", function() {
    console.log('reservation-validation.js loaded');

    // Pripojenie event listenera na rezervaciu
    var reservationForm = document.querySelector('.reservation-form');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            // Vymažem chybové správy pred novou validáciou
            var errorElements = this.querySelectorAll('.invalid-feedback');
            errorElements.forEach(function(elem) {
                elem.textContent = '';
            });

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email-register').value.trim();
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const guests = document.getElementById('guests').value;
            const terms = document.getElementById('terms').checked;

            let valid = true;

            if (name === '') {
                showError('name', 'Meno je povinné.');
                valid = false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.match(emailPattern)) {
                showError('email-register', 'Neplatný formát e-mailu.');
                valid = false;
            }

            if (date === '') {
                showError('date', 'Dátum je povinný.');
                valid = false;
            }

            if (time === '') {
                showError('time', 'Čas je povinný.');
                valid = false;
            }

            if (guests < 1) {
                showError('guests', 'Počet hostí musí byť aspoň 1.');
                valid = false;
            }

            if (!terms) {
                alert('Musíte súhlasiť s podmienkami použitia.');
                valid = false;
            }

            if (!valid) {
                e.preventDefault(); // zabráni odoslaniu
            }
        });
    }

    function showError(fieldId, message) {
        var field = document.getElementById(fieldId);
        var errorElement = field.nextElementSibling;
        if (errorElement && errorElement.classList.contains('invalid-feedback')) {
            errorElement.textContent = message;
        }
    }
});
