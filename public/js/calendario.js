    const daysEl = document.getElementById('days');
    const monthYearEl = document.getElementById('month-year');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');

    let currentDate = new Date();

    const events = [
        { date: '2025-04-25', text: 'trabajo grifo: 19:00 PM', type: 'work' },
        { date: '2025-04-25', text: 'reunión equipo: 10:00 AM', type: 'work' },
        { date: '2025-04-25', text: 'evento deportivo: 16:00 PM', type: 'work' },
        { date: '2025-04-30', text: 'revisión mensual: 09:00 AM', type: 'work' },
        { date: '2025-05-01', text: 'festivo nacional', type: 'other' },
    ];

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDay = (firstDay.getDay() + 6) % 7;

        const today = new Date();
        const monthName = date.toLocaleString('es-ES', { month: 'long' });
        monthYearEl.textContent = `${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${year}`;
        daysEl.innerHTML = '';

        for (let i = 0; i < startDay; i++) {
            daysEl.innerHTML += `<div></div>`;
        }

        for (let d = 1; d <= lastDay.getDate(); d++) {
            const isToday = d === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const dayEvents = events.filter(event => event.date === dateStr);
            const workEvents = dayEvents.filter(event => event.type === 'work');
            const hasWorkEvents = workEvents.length > 0;

            const classes = [
                isToday ? 'today' : '',
                hasWorkEvents ? 'has-event' : ''
            ].join(' ').trim();

            let workIcons = '';
            if (hasWorkEvents) {
                workIcons = workEvents.map((event, index) => `<i class="fa fa-briefcase work-event" style="left:${8 + (index * 20)}px;"></i>`).join('');
            }

            daysEl.innerHTML += `
                <div class="${classes}" data-date="${dateStr}">
                    <span class="day-number">${d}</span>
                    ${workIcons}
                </div>
            `;
        }

        // Click eventos
        document.querySelectorAll('.days-grid div[data-date]').forEach(day => {
            day.addEventListener('click', () => {
                const dateClicked = day.getAttribute('data-date');
                const [year, month, dayNum] = dateClicked.split('-');
                const formattedDate = `${parseInt(dayNum)} de ${new Date(dateClicked).toLocaleString('es-ES', { month: 'long' })} de ${year}`;
                const dayEvents = events.filter(event => event.date === dateClicked);

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