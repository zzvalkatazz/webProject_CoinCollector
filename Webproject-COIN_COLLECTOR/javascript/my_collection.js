
document.addEventListener("DOMContentLoaded", function () {
    const gallery = document.getElementById("coins-gallery");
    const searchInput = document.getElementById("search");
    const filterContinent = document.getElementById("filter-continent");
    const sortSelect = document.getElementById("sort");

    const modal = document.getElementById("coinModal");
    const modalTitle = modal.querySelector(".modal-title");
    const modalYear = modal.querySelector(".modal-year");
    const modalCountry = modal.querySelector(".modal-country");
    const modalValue = modal.querySelector(".modal-value");
    const modalContinent = modal.querySelector(".modal-continent");
    const modalCollection = modal.querySelector(".modal-collection");
    const modalFront = modal.querySelector(".modal-front");
    const modalBack = modal.querySelector(".modal-back");
    const modalClose = modal.querySelector(".close");

    function fetchAndShowCoins() {
        const searchQuery = searchInput.value.trim();
        const selectedContinent = filterContinent.value;
        const selectedSort = sortSelect.value;

        const params = new URLSearchParams();
        if (searchQuery) params.append("search", searchQuery);
        if (selectedContinent) params.append("continent", selectedContinent);
        if (selectedSort) params.append("sort", selectedSort);

        fetch("../php/my_collection.php?" + params.toString())
            .then(response => response.json())
            .then(data => {
                console.log("Получени данни от сървър:", data);
                gallery.innerHTML = "";
                if (!data.coins || data.coins.length === 0) {
                    gallery.innerHTML = "<p>Няма намерени монети.</p>";
                    return;
                }
                data.coins.forEach(coin => {
                    let div = document.createElement("div");
                    div.className = "coin-item";
                    div.innerHTML = `
                        <div class="coin-container">
                            <div class="coin-images">
                                <img src="../uploads/${coin.front_image}" alt="${coin.name}" class="coin-front">
                                <img src="../uploads/${coin.back_image}" alt="${coin.name}" class="coin-back">
                            </div>
                            <p><strong>${coin.name}</strong> (${coin.year})</p>
                            <p>${coin.value}, ${coin.country}</p>
                            <button onclick="showDetails('${encodeURIComponent(coin.name)}','${coin.year}','${encodeURIComponent(coin.country)}','${coin.value}','${encodeURIComponent(coin.continent)}', '${encodeURIComponent(coin.collection_name)}','${coin.front_image}','${coin.back_image}','${coin.id}')"> Детайли </button>
                            <button onclick="exportCoin('${coin.id}')"> Export </button> 
                            <button onclick="deleteCoin('${coin.id}')">Изтриване</button>
                        </div>
                    `;
                    gallery.appendChild(div);
                });
            })
            .catch(error => console.error("Грешка при зареждането на колекцията", error));
    }

    window.showDetails = function (name, year, country, value, continent, collection, frontImage, backImage, coinId) {
        modal.style.display = "block";
        modalTitle.textContent = decodeURIComponent(name);
        modalYear.textContent = `Година:${decodeURIComponent(year)}`;
        modalCountry.textContent = `Държава: ${decodeURIComponent(country)}`;
        modalValue.textContent = `Стойност:${value} лв`;
        modalContinent.textContent = `Континент:${decodeURIComponent(continent)}`;
        modalCollection.textContent = `Колекция:${decodeURIComponent(collection)}`;
        modalFront.src = `../uploads/${frontImage}`;
        modalBack.src = `../uploads/${backImage}`;
    };

    // Функция за експортиране на монетата в CSV
    window.exportCoin = function (coinId) {
        // Това ще отвори директно генерирания CSV файл чрез PHP
        window.location.href = `../php/export_coin.php?coin_id=${coinId}`;
    };

    window.deleteCoin = function (coinId) {
        if (confirm("Наистина ли искате да изтриете тази монета?")) {
            // Това ще изпрати заявка до PHP скрипт за изтриване на монетата
            fetch(`../php/delete_coin.php?coin_id=${coinId}`, {
                method: 'GET',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Монетата беше успешно изтрита.');
                        fetchAndShowCoins(); // Презареждаме галерията с актуализираните данни
                    } else {
                        alert('Грешка при изтриването на монетата.');
                    }
                })
                .catch(error => {
                    console.error('Грешка при изтриването:', error);
                    alert('Грешка при изтриването на монетата.');
                });
        }
    };


    window.closeModal = function () {
        modal.style.display = "none";
    };

    modalClose.addEventListener("click", closeModal);
    searchInput.addEventListener("input", fetchAndShowCoins);
    filterContinent.addEventListener("change", fetchAndShowCoins);
    sortSelect.addEventListener("change", fetchAndShowCoins);

    fetchAndShowCoins();
});
