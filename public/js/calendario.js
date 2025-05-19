daysEl = document.getElementById('days');
monthYearEl = document.getElementById('month-year');
prevBtn = document.getElementById('prev');
nextBtn = document.getElementById('next');

let currentDate = new Date();

function renderCalendar(date) {
    year = date.getFullYear();
    month = date.getMonth();

    firstDay = new Date(year, month, 1);
    lastDay = new Date(year, month + 1, 0);
    startDay = (firstDay.getDay() + 6) % 7;

    today = new Date();
    monthName = date.toLocaleString('es-ES', { month: 'long' });
    monthYearEl.textContent = `${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${year}`;
    daysEl.innerHTML = '';

    for (let i = 0; i < startDay; i++) {
        daysEl.innerHTML += `<div></div>`;
    }

    for (let d = 1; d <= lastDay.getDate(); d++) {
        isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
        dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        dayEvents = events.filter(event => event.date === dateStr);
        workEvents = dayEvents.filter(event => event.type === 'work');
        hasWorkEvents = workEvents.length > 0;

        classes = [
            isToday ? 'today' : '',
            hasWorkEvents ? 'has-event' : ''
        ].join(' ').trim();

        let workIcons = '';
        if (hasWorkEvents) {
            workIcons = workEvents.map((event, index) => `<i class="fa fa-briefcase work-event" style="left:${8 + (index * 20)}px;"></i>`).join('');
        }

        const weekdayShort = new Date(year, month, d).toLocaleDateString('es-ES', { weekday: 'short' });

        daysEl.innerHTML += `
            <div class="${classes}" data-date="${dateStr}">
                <span class="day-number">${d}</span>
                <span class="day-weekday mobile-only">${weekdayShort}</span>
                ${workIcons}
            </div>
        `;
    }

    document.querySelectorAll('.days-grid div[data-date]').forEach(day => {
        day.addEventListener('click', () => {
            dateClicked = day.getAttribute('data-date');
            [year, month, dayNum] = dateClicked.split('-');
            formattedDate = `${parseInt(dayNum)} de ${new Date(dateClicked).toLocaleString('es-ES', { month: 'long' })} de ${year}`;
            dayEvents = events.filter(event => event.date === dateClicked);

            if (dayEvents.length > 0) {
                let eventList = `<ul style="text-align:left;">${dayEvents.map(e => `<li>${e.text}</li>`).join('')}</ul>`;

                Swal.fire({
                    title: `Eventos del ${formattedDate}`,
                    html: eventList,
                    icon: 'info',
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#EC6A6A',
                });
            } else {
                Swal.fire({
                    title: `Sin eventos`,
                    text: `No hay eventos programados para el ${formattedDate}`,
                    icon: 'info',
                    confirmButtonColor: '#EC6A6A',
                });
            }
        });
    });
}

prevBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

nextBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

renderCalendar(currentDate);
