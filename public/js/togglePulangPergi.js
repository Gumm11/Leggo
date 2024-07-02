const toggleSwitch = document.getElementById('toggle-switch');
const searchFlightMainPanelTeksPulang = document.getElementById('search-flight-main-panel-teks-pulang');
const searchFlightMainPanelInputPulang = document.getElementById('search-flight-main-panel-input-pulang');
const searchFlightMainPanelInputPulangDate = document.getElementById('search-flight-main-panel-input-pulang-date');

toggleSwitch.addEventListener('change', () => {
  if (toggleSwitch.checked) {
    searchFlightMainPanelTeksPulang.style.display = 'block';
    searchFlightMainPanelInputPulang.style.display = 'block';
    searchFlightMainPanelInputPulangDate.style.display = 'block';
  } else {
    searchFlightMainPanelTeksPulang.style.display = 'none';
    searchFlightMainPanelInputPulang.style.display = 'none';
    searchFlightMainPanelInputPulangDate.style.display = 'none';
  }
});

// Set the initial display property of the Tanggal Pulang elements to none
searchFlightMainPanelTeksPulang.style.display = 'none';
searchFlightMainPanelInputPulang.style.display = 'none';
searchFlightMainPanelInputPulangDate.style.display = 'none';