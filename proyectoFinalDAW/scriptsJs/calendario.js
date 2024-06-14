//Carga el contenido del DOM
document.addEventListener('DOMContentLoaded', function () {
  //Referencias a los elementos del DOM
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const currentMonthDisplay = document.getElementById('currentMonth');
    const calendarTable = document.getElementById('calendar');
    const eventDescriptionInput = document.getElementById('eventDescription');
    const eventForm = document.getElementById('eventForm');
    const eventList = document.getElementById('eventList');

    //Variables de la fecha y lista de eventos
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let events = [];
  
    renderCalendar(currentMonth, currentYear);
  
    //Añade evento al botón
    prevBtn.addEventListener('click', function () {
      currentMonth -= 1;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear -= 1;
      }
      renderCalendar(currentMonth, currentYear);
    });
  
    nextBtn.addEventListener('click', function () {
      currentMonth += 1;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear += 1;
      }
      renderCalendar(currentMonth, currentYear);
    });
  
    //Función de rendirezar
    function renderCalendar(month, year) {
      //Limpia la tabla
      calendarTable.innerHTML = '';
  
      //Obtiene el num. de días del mes, el 1 día del mes.
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const firstDayOfMonth = new Date(year, month, 1).getDay();
  
      //Muestra el mes y año
      currentMonthDisplay.textContent = `${getMonthName(month)} ${year}`;
  
      const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
  
      // Crea la fila de días de la semana, encabezado
      let headerRow = calendarTable.insertRow();
      for (let day of daysOfWeek) {
        let cell = headerRow.insertCell();
        cell.textContent = day;
      }
  
      let dayCounter = 1;
      // Crea las filas y celdas del calendario
      for (let i = 0; i < 6; i++) {
        let row = calendarTable.insertRow();
        for (let j = 0; j < 7; j++) {
          let cell = row.insertCell();
          //Llena las celdas vacias antes del 1 día
          if (i === 0 && j < firstDayOfMonth) {
            cell.classList.add('empty');
          } else if (dayCounter > daysInMonth) {

            //LLena celdas vacias despues del ultimo día
            cell.classList.add('empty');
          } else {

            //Llena con días del mes
            cell.textContent = dayCounter;
            cell.dataset.date = `${year}-${month + 1}-${dayCounter}`;
            cell.addEventListener('click', function () {
              selectDay(cell);
            });
            dayCounter++;
          }
        }
      }
    }
  
    function selectDay(cell) {

      //Desmarca el día seleccionado
      const selectedDate = document.querySelector('.selected');
      if (selectedDate) {
        selectedDate.classList.remove('selected');
      }
      cell.classList.add('selected');
      const selectedDateString = cell.dataset.date;
      const [selectedYear, selectedMonth, selectedDay] = selectedDateString.split('-').map(Number);

      addEvent(selectedDay, selectedMonth - 1, selectedYear);
    }

    //Función que devuelve nombre del mes
    function getMonthName(monthIndex) {
      const months = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
      ];
      return months[monthIndex];
    }
  
    //Añade evento
    function addEvent() {
      const selectedDate = document.querySelector('.selected');
      if (!selectedDate) {
         alert("Seleccione un día del calendario.");
         return;
      }
  
      const day = parseInt(selectedDate.textContent);
      const month = currentMonth;
      const year = currentYear;

      const event = {
        date: new Date(year, month, day),
        description: eventDescriptionInput.value
      };
      events.push(event);
      renderEventList();
      clearEventForm();
    }

    // Función que renderiza la lista de eventos
    function renderEventList() {
      eventList.innerHTML = '';
      events.forEach((event, index) => {
        const eventDiv = document.createElement('div');
        eventDiv.classList.add('event');
        eventDiv.style.marginBottom = '10px';
        const formattedDate = `${event.date.getDate()}/${event.date.getMonth() + 1}/${event.date.getFullYear()}`;
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'X';
        deleteButton.classList.add('btn', 'btn-sm', 'btn-outline-success');
        deleteButton.addEventListener('click', function() {
              deleteEvent(index);
        });
        const eventText = document.createElement('span');
        eventText.innerHTML = `<strong>${formattedDate}:</strong> ${event.description}`;
        eventDiv.appendChild(deleteButton);
        eventDiv.appendChild(document.createTextNode('\u00A0'));
        eventDiv.appendChild(eventText);
        eventList.appendChild(eventDiv);
      });
    }
  
    //Función que limpia el formulario
    function clearEventForm() {
      eventDescriptionInput.value = '';
    }

    // Función que elimina un evento de la lista
    function deleteEvent(index) {
      events.splice(index, 1);
      renderEventList();
    }
});
