document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const departureLocation = urlParams.get('departure');
    const arrivalLocation = urlParams.get('arrival');
    const departureDate = urlParams.get('departureDate');
    const returnDate = urlParams.get('returnDate');
    const ticketClass = urlParams.get('ticketClass');
    const passengers = urlParams.get('passengers');

    let url = 'http://localhost/github/leggo/public/php/list_avail.php';
    let queryParams = [];

    if (departureLocation) {
        queryParams.push(`departure=${encodeURIComponent(departureLocation)}`);
    }
    if (arrivalLocation) {
        queryParams.push(`arrival=${encodeURIComponent(arrivalLocation)}`);
    }
    if (departureDate) {
        queryParams.push(`departureDate=${encodeURIComponent(departureDate)}`);
    }
    if (returnDate) {
        queryParams.push(`returnDate=${encodeURIComponent(returnDate)}`);
    }
    if (ticketClass) {
        queryParams.push(`ticketClass=${encodeURIComponent(ticketClass)}`);
    }
    if (passengers) {
        queryParams.push(`passengers=${encodeURIComponent(passengers)}`);
    }

    if (queryParams.length > 0) {
        url += '?' + queryParams.join('&');
    }

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            if (data && data.length > 0) {
                (async () => {
                    for (const penerbangan of data) {
                        await createFlightCard(penerbangan);
                    }
                })();
            }
        })
        .catch((error) => {
            console.error("Error fetching penerbangan data: ", error);
        });
});

async function createFlightCard(penerbangan) {
    let div = document.querySelector("#list-flight-container");
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

    card.addEventListener('click', function() {
        let bookingUrl = 'http://localhost/github/leggo/booking.html';
        let queryParams = [
            `Nama_maskapai=${encodeURIComponent(penerbangan.Nama_maskapai)}`,
            `bandara_keberangkatan=${encodeURIComponent(penerbangan.bandara_keberangkatan)}`,
            `bandara_kedatangan=${encodeURIComponent(penerbangan.bandara_kedatangan)}`,
            `departureTime=${encodeURIComponent(departureTime)}`,
            `arrivalTime=${encodeURIComponent(arrivalTime)}`,
            `price=${encodeURIComponent(price)}`
        ];
        bookingUrl += '?' + queryParams.join('&');
        window.location.href = bookingUrl;
    });

    div.appendChild(card);
}
