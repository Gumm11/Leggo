class RecommendationCard {
  constructor(kota) {
    this.kota = kota;
    this.card = this.createCard();
  }

  createCard() {
    let id = this.kota.Id_kota;
    const imageUrl = `https://ik.imagekit.io/iwrtsyly3o/Leggo/attraction_${id}.png`;

    const card = document.createElement("div");
    card.className = "index-body-main-recommendation-panel";
    card.innerHTML = `
      <img src="${imageUrl}" alt="${this.kota.Tempat_wisata || 'Image Not Found'}" style="border-radius: 10px;">
      <p style="text-align: center;">
        <span>${this.kota.Tempat_wisata || 'N/A'}</span><br>
        ${this.kota.Nama_kota}
      </p>
    `;

    return card;
  }

  render(parentElement) {
    if (parentElement) {
      parentElement.appendChild(this.card);
    } else {
      console.error("Parent element not found");
    }
  }
}

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
    const recommendationCard = new RecommendationCard(kota);
    recommendationCard.render(recommendationInnerContainer);
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