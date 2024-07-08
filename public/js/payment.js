document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const departureFlight = JSON.parse(urlParams.get('departureFlight'));
    const returnFlight = urlParams.get('returnFlight') ? JSON.parse(urlParams.get('returnFlight')) : null;

    // Update the departure flight information
    document.getElementById('departure-city').innerText = `${urlParams.get('departure')} (${departureFlight.bandara_keberangkatan})`;
    document.getElementById('arrival-city').innerText = `${urlParams.get('arrival')} (${departureFlight.bandara_kedatangan})`;

    // Update the return flight information if available
    if (returnFlight) {
        document.getElementById('return-departure-city').innerText = `${urlParams.get('arrival')} (${returnFlight.bandara_keberangkatan})`;
        document.getElementById('return-arrival-city').innerText = `${urlParams.get('departure')} (${returnFlight.bandara_kedatangan})`;
    } else {
        document.getElementById('pulang-container').style.display = 'none';
        document.getElementById('labelPulang').style.display = 'none';
    }

    // Calculate the initial total price
    calculateTotalPrice();

    // Add event listeners to update the total price when bagasi is changed
    document.querySelectorAll('.bagasi-container').forEach(bagasiContainer => {
        bagasiContainer.addEventListener('click', function () {
            document.querySelectorAll('.bagasi-container').forEach(container => container.classList.remove('selected'));
            this.classList.add('selected');
            calculateTotalPrice();
        });
    });

    // Add event listener to the payment button
    document.getElementById('btnpayment').addEventListener('click', handlePayment);
    document.getElementById('cancel-payment').addEventListener('click', hideOverlay);
    document.getElementById('confirm-payment').addEventListener('click', processPayment);
});

function calculateTotalPrice() {
    const urlParams = new URLSearchParams(window.location.search);
    const departureFlight = JSON.parse(urlParams.get('departureFlight'));
    const returnFlight = urlParams.get('returnFlight') ? JSON.parse(urlParams.get('returnFlight')) : null;
    const passengers = parseInt(urlParams.get('passengers'));

    const departurePrice = parseInt(departureFlight.Harga_tiket.replace(/[^\d]/g, '')) / 100;
    let totalPrice = departurePrice * passengers;

    if (returnFlight) {
        const returnPrice = parseInt(returnFlight.Harga_tiket.replace(/[^\d]/g, '')) / 100;
        totalPrice += returnPrice * passengers;
    }

    // Calculate bagasi price
    const bagasiPricePerKg = 50000; // Example price per kg
    const selectedBagasi = parseInt(document.querySelector('.bagasi-container.selected').getAttribute('data-weight'));
    const totalBagasiPrice = selectedBagasi * bagasiPricePerKg * passengers;

    totalPrice += totalBagasiPrice;

    document.getElementById('total-price').innerText = `IDR ${totalPrice.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function handlePayment() {
    const namaLengkap = document.getElementById('NamaLengkap').value;
    const nomorTelepon = document.getElementById('NomorTelepon').value;
    const email = document.getElementById('Email').value;
    const namaLengkapCustomer = document.getElementById('NamaLengkapCustomer').value;
    const ageGroup = document.getElementById('age-group').value;

    // Validate form inputs
    if (!namaLengkap || !nomorTelepon || !email || !namaLengkapCustomer || !ageGroup) {
        alert('Please fill in all required fields.');
        return;
    }

    // Show overlay with confirmation details
    showOverlay();
}

function showOverlay() {
    const urlParams = new URLSearchParams(window.location.search);
    const departureFlight = JSON.parse(urlParams.get('departureFlight'));
    const returnFlight = urlParams.get('returnFlight') ? JSON.parse(urlParams.get('returnFlight')) : null;
    const passengers = parseInt(urlParams.get('passengers'));

    const departureDetails = `${urlParams.get('departure')} (${departureFlight.bandara_keberangkatan}) ➟ ${urlParams.get('arrival')} (${departureFlight.bandara_kedatangan})`;
    const returnDetails = returnFlight ? `${urlParams.get('arrival')} (${returnFlight.bandara_keberangkatan}) ➟ ${urlParams.get('departure')} (${returnFlight.bandara_kedatangan})` : '';

    document.getElementById('confirm-departure').innerText = `Pergi: ${departureDetails}`;
    document.getElementById('confirm-return').innerText = returnDetails ? `Pulang: ${returnDetails}` : '';
    document.getElementById('confirm-total-price').innerText = document.getElementById('total-price').innerText;

    document.getElementById('payment-overlay').style.display = 'flex';
}

function hideOverlay() {
    document.getElementById('payment-overlay').style.display = 'none';
}

function processPayment() {
    // Gather all required data
    const paymentData = {
        namaLengkap: document.getElementById('NamaLengkap').value,
        nomorTelepon: document.getElementById('NomorTelepon').value,
        email: document.getElementById('Email').value,
        namaLengkapCustomer: document.getElementById('NamaLengkapCustomer').value,
        ageGroup: document.getElementById('age-group').value,
        selectedBagasi: document.querySelector('.bagasi-container.selected').getAttribute('data-weight'),
        totalPrice: document.getElementById('total-price').innerText,
        departureFlight: JSON.parse(new URLSearchParams(window.location.search).get('departureFlight')),
        returnFlight: new URLSearchParams(window.location.search).get('returnFlight') ? JSON.parse(new URLSearchParams(window.location.search).get('returnFlight')) : null
    };

    console.log(paymentData);

    // Here, you would typically send the paymentData to your server for processing
    // For this example, we'll just log it to the console

    // Hide overlay after processing payment
    hideOverlay();

    // Show success message or redirect to a success page
    alert('Pembayaran berhasil! Terima kasih telah memesan.');
}