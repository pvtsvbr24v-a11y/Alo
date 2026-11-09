/*
| عداد تنازلي لبنر العروض: <span data-countdown="2026-12-01T00:00:00+03:00">
*/
document.querySelectorAll('[data-countdown]').forEach((element) => {
    const endsAt = new Date(element.dataset.countdown).getTime();
    const parts = {
        days: element.querySelector('[data-days]'),
        hours: element.querySelector('[data-hours]'),
        minutes: element.querySelector('[data-minutes]'),
    };

    const tick = () => {
        const remaining = Math.max(endsAt - Date.now(), 0);
        const pad = (value) => String(Math.floor(value)).padStart(2, '0');

        parts.days.textContent = pad(remaining / 86400000);
        parts.hours.textContent = pad((remaining % 86400000) / 3600000);
        parts.minutes.textContent = pad((remaining % 3600000) / 60000);
    };

    tick();
    setInterval(tick, 30000);
});

/*
| عداد إعادة إرسال رمز الدخول
*/
const resendButton = document.querySelector('[data-resend-after]');

if (resendButton) {
    let seconds = Number(resendButton.dataset.resendAfter);
    const label = resendButton.querySelector('[data-resend-label]');
    const original = label.textContent;

    resendButton.disabled = true;

    const timer = setInterval(() => {
        seconds -= 1;
        label.textContent = `${original} (${seconds})`;

        if (seconds <= 0) {
            clearInterval(timer);
            resendButton.disabled = false;
            label.textContent = original;
        }
    }, 1000);
}

/*
| فتح وإغلاق قائمة الجوال
*/
document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.menuToggle)?.classList.toggle('hidden');
    });
});
