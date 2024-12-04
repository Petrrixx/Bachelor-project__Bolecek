document.addEventListener("DOMContentLoaded", function() {
    console.log('register-validation.js loaded');

    var registerForm = document.getElementById('register-form-action');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email-register').value;
            const password = document.getElementById('password-register').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const contact = document.getElementById('contact').value;

            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email.match(emailPattern)) {
                alert("Prosím, zadajte platný email.");
                return;
            }

            // Validácia hesla (min 6 znakov, 1 veľké, 1 malé písmeno)
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
            if (!password.match(passwordPattern)) {
                alert("Heslo musí obsahovať minimálne 6 znakov, vrátane veľkého a malého písmena.");
                return;
            }

            if (password !== passwordConfirmation) {
                alert("Heslá sa nezhodujú.");
                return;
            }

            if (contact) {
                const phonePattern = /^[0-9]{10}$/;
                if (!contact.match(phonePattern)) {
                    alert("Telefónne číslo musí mať presne 10 číslic.");
                    return;
                }
            }

            registerForm.submit();
        });
    } else {
        console.warn('register-form-action not found');
    }
});
