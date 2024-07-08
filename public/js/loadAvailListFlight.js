document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const departureLocation = urlParams.get('departure');
    const arrivalLocation = urlParams.get('arrival');
    const departureDate = urlParams.get('departureDate');
    const returnDate = urlParams.get('returnDate');
    const ticketClass = urlParams.get('ticketClass');
    const passengers = urlParams.get('passengers');

    // Fetch departure flights
    fetchFlights(departureLocation, arrivalLocation, departureDate, ticketClass, passengers, false);
});

let selectedDepartureFlight = null;
let selectedReturnFlight = null;

async function fetchFlights(departureLocation, arrivalLocation, date, ticketClass, passengers, isReturn) {
    console.log(`Fetching flights: ${departureLocation} to ${arrivalLocation} on ${date} (isReturn: ${isReturn})`);
    let url = 'http://localhost/github/leggo/public/php/list_avail.php';
    let queryParams = [
        `departure=${encodeURIComponent(departureLocation)}`,
        `arrival=${encodeURIComponent(arrivalLocation)}`,
        `departureDate=${encodeURIComponent(date)}`,
        `ticketClass=${encodeURIComponent(ticketClass)}`,
        `passengers=${encodeURIComponent(passengers)}`
    ];

    if (queryParams.length > 0) {
        url += '?' + queryParams.join('&');
    }

    console.log(`Fetching URL: ${url}`); // Log URL for debugging

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            console.log(`Fetched ${data.length} flights (isReturn: ${isReturn})`);
            if (data && data.length > 0) {
                (async () => {
                    for (const penerbangan of data) {
                        await createFlightCard(penerbangan, isReturn);
                    }
                })();
            }
        })
        .catch((error) => {
            console.error("Error fetching penerbangan data: ", error);
        });
}

async function createFlightCard(penerbangan, isReturn) {
    let div = isReturn ? document.querySelector("#return-flight-container") : document.querySelector("#list-flight-container");
    let card = document.createElement('div');
    card.className = 'list-flight-information-card';

    let departure = penerbangan.Jam_keberangkatan;
    const departureTime = departure.substr(0, 5);
    let arrival = penerbangan.Jam_kedatangan;
    const arrivalTime = arrival.substr(0, 5);

    let price = penerbangan.Harga_tiket;

    if (price === null) {
        price = "N/A";
    } else {
        price = String(price).replace(/[^0-9]/g, '');

        const digits = price.split('');
        digits.reverse();
        digits.splice(0, 2);

        let formattedPrice = '';
        let digitCount = 0;
        for (let i = 0; i < digits.length; i++) {
            if (digitCount % 3 === 0 && digitCount > 0) {
                formattedPrice += '.';
            }
            formattedPrice += digits[i];
            digitCount++;
        }

        formattedPrice = formattedPrice.split('').reverse().join('');
        price = formattedPrice;
    }

    card.innerHTML = `<div class="list-flight-information-card-row">
                            <div class="list-flight-panel-information-text-big">
                                ${penerbangan.Nama_maskapai} (${penerbangan.bandara_keberangkatan} ➟ ${penerbangan.bandara_kedatangan})
                            </div>
                        </div>
                        <div class="list-flight-information-card-row">
                            <div class="list-flight-panel-information-text-big" style="margin-bottom: 20px;">
                            ${departureTime} ➟ ${arrivalTime}
                            </div>
                            <div class="list-flight-panel-information-text-big"
                            style="margin-left: 30px; margin-bottom: 15px;">
                                IDR ${price}/pax
                            </div>
                        </div>`;

    if (!isReturn) {
        card.addEventListener('click', function() {
            handleDepartureSelection(card, penerbangan);
        });
    } else {
        card.addEventListener('click', function() {
            handleReturnSelection(card, penerbangan);
        });
    }

    div.appendChild(card);
}

function handleDepartureSelection(card, penerbangan) {
    // Highlight selected departure card
    card.classList.add('selected-flight-card');

    // Store selected departure flight
    selectedDepartureFlight = penerbangan;

    // Check if returnDate is provided
    const returnDate = new URLSearchParams(window.location.search).get('returnDate');
    if (!returnDate) {
        // Prepare URL parameters for pay.html
        const urlParams = new URLSearchParams(window.location.search);
        const params = new URLSearchParams({
            departure: urlParams.get('departure'),
            arrival: urlParams.get('arrival'),
            departureDate: urlParams.get('departureDate'),
            ticketClass: urlParams.get('ticketClass'),
            passengers: urlParams.get('passengers'),
            departureFlight: JSON.stringify(selectedDepartureFlight)
        });

        // Redirect to pay.html
        window.location.href = `pay.html?${params.toString()}`;
        return;
    }

    // Clear previous departure flights
    const listFlightContainer = document.getElementById('list-flight-container');
    listFlightContainer.innerHTML = ''; 
    listFlightContainer.appendChild(card);

    // Add "Select Return Flight" label
    const selectReturnLabel = document.createElement('div');
    selectReturnLabel.className = 'select-return-label';
    selectReturnLabel.textContent = 'Select Return Flight';
    listFlightContainer.appendChild(selectReturnLabel);

    // Create return flight container
    const returnFlightContainer = document.createElement('div');
    returnFlightContainer.id = 'return-flight-container';
    listFlightContainer.appendChild(returnFlightContainer);

    // Update search information panel with selected departure details
    updateSearchInformationPanel(penerbangan);

    // Swap departure and arrival locations for return flights
    const departure = new URLSearchParams(window.location.search).get('departure');
    const arrival = new URLSearchParams(window.location.search).get('arrival');
    fetchFlights(arrival, departure, returnDate, new URLSearchParams(window.location.search).get('ticketClass'), new URLSearchParams(window.location.search).get('passengers'), true);
}

function handleReturnSelection(card, penerbangan) {
    // Highlight selected return card
    card.classList.add('selected-flight-card');

    // Store selected return flight
    selectedReturnFlight = penerbangan;

    // Prepare URL parameters for pay.html
    const urlParams = new URLSearchParams(window.location.search);
    const params = new URLSearchParams({
        departure: urlParams.get('departure'),
        arrival: urlParams.get('arrival'),
        departureDate: urlParams.get('departureDate'),
        returnDate: urlParams.get('returnDate'),
        ticketClass: urlParams.get('ticketClass'),
        passengers: urlParams.get('passengers'),
        departureFlight: JSON.stringify(selectedDepartureFlight),
        returnFlight: JSON.stringify(selectedReturnFlight)
    });

    // Redirect to pay.html
    window.location.href = `pay.html?${params.toString()}`;
}

function updateSearchInformationPanel(penerbangan) {
    const departureLocation = penerbangan.bandara_keberangkatan;
    const arrivalLocation = penerbangan.bandara_kedatangan;
    const departureTime = penerbangan.Jam_keberangkatan.substr(0, 5);
    const arrivalTime = penerbangan.Jam_kedatangan.substr(0, 5);
    const price = penerbangan.Harga_tiket || "N/A";
    const departure = new URLSearchParams(window.location.search).get('departure');
    const arrival = new URLSearchParams(window.location.search).get('arrival');
    const passengerCount = new URLSearchParams(window.location.search).get('passengers');
    const ticketClass = new URLSearchParams(window.location.search).get('ticketClass');
    const returnDate = new URLSearchParams(window.location.search).get('returnDate');

    const searchInformationPanel = document.getElementById('search-information-panel');
    searchInformationPanel.innerHTML = `<div class="list-flight-panel-information-text-big">
                                        ${arrival}, ${arrivalLocation} ➟ ${departure}, ${departureLocation}
                                        </div>
                                        <div class="list-flight-panel-information-text-small">
                                            ${returnDate} | ${passengerCount} penumpang | ${ticketClass}
                                        </div>`;
}
