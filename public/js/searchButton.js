document.addEventListener('DOMContentLoaded', () => {
    const searchFlightForm = document.getElementById('search-flight-button');
    const toggleSwitch = document.getElementById('toggle-switch');
    const returnDateInput = document.getElementById('search-flight-main-panel-input-date-to');

    // Function to toggle return date input visibility based on toggle switch state
    function toggleReturnDateInput() {
        if (toggleSwitch.checked) {
            returnDateInput.style.display = 'block'; // Show return date input
        } else {
            returnDateInput.style.display = 'none';  // Hide return date input
        }
    }

    // Initial call to toggleReturnDateInput to set initial state
    toggleReturnDateInput();

    // Event listener for toggle switch change
    toggleSwitch.addEventListener('change', () => {
        toggleReturnDateInput(); // Update return date input visibility on toggle change
    });

    // Event listener for search button click
    searchFlightForm.addEventListener('click', (event) => {
        event.preventDefault();

        const departureLocation = document.getElementById('search-flight-main-panel-from').value;
        const arrivalLocation = document.getElementById('search-flight-main-panel-to').value;
        const departureDate = document.getElementById('search-flight-main-panel-input-date-from').value;
        const returnDate = toggleSwitch.checked ? returnDateInput.value : ''; // Check toggle state for return date
        const ticketClass = document.getElementById('ticketClass').value;
        const passengers = document.getElementById('search-flight-main-panel-input-half-jmlh').value;

        if (!departureLocation || !arrivalLocation || !departureDate || !passengers) {
            alert('Please fill in all required fields.');
            return;
        }

        if (toggleSwitch.checked && !returnDate) {
            alert('Please fill in the return date.');
            return;
        }

        //const url = `https://localhost/github/leggo/list-flight.html?departure=${encodeURIComponent(departureLocation)}&arrival=${encodeURIComponent(arrivalLocation)}&departureDate=${encodeURIComponent(departureDate)}&returnDate=${encodeURIComponent(returnDate)}&ticketClass=${encodeURIComponent(ticketClass)}&passengers=${encodeURIComponent(passengers)}`;
        const url = `./list-flight.html?departure=${encodeURIComponent(departureLocation)}&arrival=${encodeURIComponent(arrivalLocation)}&departureDate=${encodeURIComponent(departureDate)}&returnDate=${encodeURIComponent(returnDate)}&ticketClass=${encodeURIComponent(ticketClass)}&passengers=${encodeURIComponent(passengers)}`;
        window.location.href = url;
    });
});
