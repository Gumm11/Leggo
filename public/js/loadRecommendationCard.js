let recommendationInnerContainer = null;
let cardWidth = 500;

document.addEventListener("DOMContentLoaded", function() {
  loadRecommendationCards();
  window.addEventListener("resize", loadRecommendationCards);
});

async function loadRecommendationCards() {
  try {
    const response = await fetch("http://localhost/github/leggo/public/php/kota.php");
    const data = await response.json();
    if (data && data.length > 0) {
      const shuffledData = data.slice();
      shuffleArray(shuffledData);

      recommendationInnerContainer = document.getElementById("recommendation-container");
      if (recommendationInnerContainer) {
        clearRecommendationCards();

        // load a minimum of 3 cards when the screen size is smaller than or equal to 900px
        const cardCount = Math.max(3, Math.floor(recommendationInnerContainer.offsetWidth / cardWidth));
        generateRecommendationCards(shuffledData.slice(0, cardCount));
      } else {
        console.error("Could not find #recommendation-container element");
      }
    }
  } catch (error) {
    console.error("Error fetching kota data: ", error);
  }
}

function generateRecommendationCards(shuffledData) {
  for (let i = 0; i < shuffledData.length; i++) {
    const kota = shuffledData[i];
    createRecommendationCard(kota);
  }
}

function clearRecommendationCards() {
  while (recommendationInnerContainer.firstChild) {
    recommendationInnerContainer.removeChild(recommendationInnerContainer.firstChild);
  }
}

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

async function createRecommendationCard(kota) {
  let id = kota.Id_kota;
  const imageUrl = `https://ik.imagekit.io/iwrtsyly3o/Leggo/attraction_${id}.png`;

  const card = document.createElement("div");
  card.className = "index-body-main-recommendation-panel";
  card.innerHTML = `
    <img src="${imageUrl}" alt="${kota.Tempat_wisata || 'Image Not Found'}" style="border-radius: 10px;">
    <p style="text-align: center;">
      <span>${kota.Tempat_wisata || 'N/A'}</span><br>
      ${kota.Nama_kota}
    </p>
  `;

  if (recommendationInnerContainer) {
    recommendationInnerContainer.appendChild(card);
  } else {
    console.error("Could not find #recommendation-container element");
  }
}
