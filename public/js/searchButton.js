const searchFlightForm = document.getElementById('search-flight-button');

searchFlightForm.addEventListener('click', (event) => {
    event.preventDefault();

    const departureLocation = document.getElementById('search-flight-main-panel-from').value;
    const arrivalLocation = document.getElementById('search-flight-main-panel-to').value;
    const departureDate = document.getElementById('search-flight-main-panel-input-date-from').value;
    const returnDate = document.getElementById('search-flight-main-panel-input-date-to').value;
    const ticketClass = document.getElementById('ticketClass').value;
    const passengers = document.getElementById('search-flight-main-panel-input-half-jmlh').value;

    const url = `https://localhost/github/leggo/list-flight.html?departure=${departureLocation}&arrival=${arrivalLocation}&departureDate=${departureDate}&returnDate=${returnDate}&ticketClass=${ticketClass}&passengers=${passengers}`;
    window.location.href = url;
});