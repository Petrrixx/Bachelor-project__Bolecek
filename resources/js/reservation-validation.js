document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const help      = document.getElementById('timeHelp');

    const updateTimeLimits = () => {
        if (!dateInput.value) {           // dátum ešte nevybraný
            timeInput.disabled = true;
            help.textContent   = 'Najprv vyberte dátum.';
            return;
        }

        const day  = new Date(dateInput.value).getDay(); // 0=Ne … 6=So
        const min  = '10:30';
        const max  = (day === 5 || day === 6 || day === 0) ? '19:00' : '14:00';

        timeInput.min      = min;
        timeInput.max      = max;
        timeInput.disabled = false;
        help.textContent   = `Možný čas ${min} – ${max}`;

        // ak užívateľ prepísal čas mimo rozsahu, vymažeme ho
        if (timeInput.value && (timeInput.value < min || timeInput.value > max)) {
            timeInput.value = '';
        }
    };

    dateInput.addEventListener('change', updateTimeLimits);
    updateTimeLimits();   // spustíme hneď pri načítaní stránky
});