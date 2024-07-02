document.addEventListener("DOMContentLoaded", function () {
    createInformationCard();
});

async function createInformationCard() {
    let div = document.querySelector("#search-information-panel");
    let card = document.createElement('div');
    card.className = 'list-flight-information-panel';

    const urlParams = new URLSearchParams(window.location.search);
    const departureLocation = urlParams.get('departure');
    const arrivalLocation = urlParams.get('arrival');
    const passengers = urlParams.get('passengers');
    const ticketClass = urlParams.get('ticketClass');
    const departureDate = urlParams.get('departureDate');

    const response = await fetch("http://localhost/github/leggo/public/php/bandara_kota.php")
    const data = await response.json();

    const departureAirport = data.find(penerbangan => penerbangan.Nama_kota === departureLocation)?.Kode_bandara;
    const arrivalAirport = data.find(penerbangan => penerbangan.Nama_kota === arrivalLocation)?.Kode_bandara;

    card.innerHTML = `  <div class="list-flight-panel-information-text-big">
                            ${departureLocation}, ${departureAirport} ➟ ${arrivalLocation}, ${arrivalAirport}
                        </div>
                        <div class="list-flight-panel-information-text-small">
                            ${departureDate} | ${passengers} penumpang | ${ticketClass}
                        </div>`;

    div.innerHTML = '';
    div.appendChild(card);
}