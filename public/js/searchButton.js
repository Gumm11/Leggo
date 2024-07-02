document.addEventListener('DOMContentLoaded', () => {
    const searchFlightForm = document.getElementById('search-flight-button');

    searchFlightForm.addEventListener('click', (event) => {
        event.preventDefault();

        const departureLocation = document.getElementById('search-flight-main-panel-from').value;
        const arrivalLocation = document.getElementById('search-flight-main-panel-to').value;
        const departureDate = document.getElementById('search-flight-main-panel-input-date-from').value;
        const returnDate = document.getElementById('search-flight-main-panel-input-date-to').value;
        const ticketClass = document.getElementById('ticketClass').value;
        const passengers = document.getElementById('search-flight-main-panel-input-half-jmlh').value;

        if (!departureLocation || !arrivalLocation || !departureDate || !passengers) {
            alert('Please fill in all required fields.');
            return;
        }

        const isReturnFlight = document.getElementById('toggle-switch').checked;
        if (isReturnFlight && !returnDate) {
            alert('Please fill in the return date.');
            return;
        }

        const url = `https://localhost/github/leggo/list-flight.html?departure=${encodeURIComponent(departureLocation)}&arrival=${encodeURIComponent(arrivalLocation)}&departureDate=${encodeURIComponent(departureDate)}&returnDate=${encodeURIComponent(returnDate)}&ticketClass=${encodeURIComponent(ticketClass)}&passengers=${encodeURIComponent(passengers)}`;
        window.location.href = url;
    });
});
