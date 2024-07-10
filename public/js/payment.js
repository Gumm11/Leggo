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

    // Validate form inputs
    if (!namaLengkap || !nomorTelepon || !email) {
        alert('Please fill in all required fields.');
        return;
    }

    // Validate customer inputs
    const urlParams = new URLSearchParams(window.location.search);
    const passengers = parseInt(urlParams.get('passengers')) || 1;

    let valid = true;
    for (let i = 0; i < passengers; i++) {
        const namaLengkapCustomer = document.getElementById(`NamaLengkapCustomer${i}`).value;
        const ageGroup = document.getElementById(`age-group${i}`).value;
        if (!namaLengkapCustomer || !ageGroup) {
            valid = false;
            break;
        }
    }

    if (!valid) {
        alert('Please fill in all customer details.');
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
    const urlParams = new URLSearchParams(window.location.search);
    const passengers = parseInt(urlParams.get('passengers')) || 1;

    // Gather all required data
    const paymentData = {
        namaLengkap: document.getElementById('NamaLengkap').value,
        nomorTelepon: document.getElementById('NomorTelepon').value,
        email: document.getElementById('Email').value,
        selectedBagasi: document.querySelector('.bagasi-container.selected').getAttribute('data-weight'),
        totalPrice: document.getElementById('total-price').innerText,
        departureFlight: JSON.parse(new URLSearchParams(window.location.search).get('departureFlight')),
        returnFlight: new URLSearchParams(window.location.search).get('returnFlight') ? JSON.parse(new URLSearchParams(window.location.search).get('returnFlight')) : null,
        passengers: []
    };

    for (let i = 0; i < passengers; i++) {
        const passengerData = {
            namaLengkapCustomer: document.getElementById(`NamaLengkapCustomer${i}`).value,
            ageGroup: document.getElementById(`age-group${i}`).value
        };
        paymentData.passengers.push(passengerData);
    }

    console.log('Payment Data:', JSON.stringify(paymentData, null, 2));

    // Send paymentData to server for processing
    fetch('public/php/process_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(paymentData)
    })
    .then(response => response.text()) // Get the response text
    .then(text => {
        console.log('Response Text:', text); // Log the response text
        let data;
        try {
            data = JSON.parse(text); // Attempt to parse the text as JSON
        } catch (e) {
            console.error('Error parsing response JSON:', e);
            throw new Error('Invalid server response');
        }

        // Insert paymentData into pemesanan table
        insertIntoPemesanan(paymentData)
        .then(idPemesanan => {
            if (data.token) {
                hideOverlay(); // Hide the overlay before opening the Midtrans popup

                snap.pay(data.token, {
                    onSuccess: function (result) {
                        console.log('Payment success:', result);
                        alert('Pembayaran berhasil! Terima kasih telah memesan.');

                        // Update status to 'Success'
                        updatePaymentStatus(idPemesanan, 'Success');
                        window.location.href = "index.html";
                    },
                    onPending: function (result) {
                        console.log('Payment pending:', result);
                        alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                    },
                    onError: function (result) {
                        console.log('Payment error:', result);
                        alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                    },
                    onClose: function () {
                        console.log('Payment popup closed');
                    }
                });
            } else {
                throw new Error('Failed to get token');
            }
        });
    })
    .catch(error => {
        console.error('Error processing payment:', error);
        alert(`Error processing payment: ${error.message}`);
    });
}

function insertIntoPemesanan(paymentData) {
    return fetch('public/php/insert_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(paymentData)
    })
    .then(response => response.text())
    .then(text => {
        console.log('Inserted into pemesanan:', text);
        let data;
        try {
            data = JSON.parse(text); // Attempt to parse the text as JSON
        } catch (e) {
            console.error('Error parsing response JSON:', e);
            throw new Error('Invalid server response');
        }

        if (data.success) {
            return data.id_pemesanan; // Return the id_pemesanan
        } else {
            throw new Error(data.error);
        }
    })
    .catch(error => {
        console.error('Error inserting into pemesanan:', error);
        throw error;
    });
}

function updatePaymentStatus(idPemesanan, status) {
    const updateData = {
        id_pemesanan: idPemesanan,
        status: status
    };

    fetch('public/php/update_payment_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(updateData)
    })
    .then(response => response.text())
    .then(text => {
        console.log('Updated payment status:', text);
        // Handle success or error response from status update
    })
    .catch(error => {
        console.error('Error updating payment status:', error);
        // Handle status update error
    });
}

function closePaymentPopup() {
    // Assuming the pay-overlay-content is the class of the popup
    const paymentPopup = document.querySelector('.pay-overlay-content');
    if (paymentPopup) {
        paymentPopup.style.display = 'none';
    }
}