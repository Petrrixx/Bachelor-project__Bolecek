document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Získame hodnoty z formulárov
    const full_name = document.getElementById('full_name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const phone_number = document.getElementById('phone_number').value;

    // Validácia emailu
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

    // Ak je všetko OK, odošleme formulár
    this.submit();
});
