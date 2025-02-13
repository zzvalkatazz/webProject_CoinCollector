document.addEventListener("DOMContentLoaded", function () {
    fetch('../php/statistics.php')
        .then(response => response.json())
        .then(data => {
            console.log(" Получени данни:", data); 

            if (data.empty === true) {
                console.error(" Грешни данни при зареждане", data);
                document.getElementById("continent-statistics").innerHTML = "<p>Няма монети по континенти</p>";
                document.getElementById("country-statistics").innerHTML = "<p>Няма монети по държави</p>";
                document.getElementById("top-decades").innerHTML = "<p>Няма информация за класация на монети по десетилетие</p>";
                return;
            }

            document.getElementById('total-coins').innerText = data.total_coins || 0;
            document.getElementById('total-value').innerText = (parseFloat(data.total_value) || 0).toFixed(2) + " лв";


          
            const continentStatistics = document.getElementById("continent-statistics");
            continentStatistics.innerHTML = "";
            if (!data.continent_data || data.continent_data.length === 0) {
                continentStatistics.innerHTML = "<p>Няма монети по континенти</p>";
            } else {
                data.continent_data.forEach(item => {
                    let li = document.createElement("li");
                    li.innerText = `${item.continent}: ${item.count} монети`;
                    continentStatistics.appendChild(li);
                });
            }

            
            const countryStatistics = document.getElementById("country-statistics");
            countryStatistics.innerHTML = "";
            if (!data.country_data || data.country_data.length === 0) {
                countryStatistics.innerHTML = "<p>Няма монети по държави</p>";
            } else {
                data.country_data.forEach(item => {
                    let li = document.createElement("li");
                    li.innerText = `${item.country}: ${item.count} монети`;
                    countryStatistics.appendChild(li);
                });
            }

          
            const topDecadesUsers = document.getElementById("top-decades");
            topDecadesUsers.innerHTML = "";
            if (!data.top_decades_user || data.top_decades_user.length === 0) {
                topDecadesUsers.innerHTML = "<p>Няма информация за класация на монети по десетилетие</p>";
            } else {
                data.top_decades_user.forEach(item => {
                    let li = document.createElement("li");
                    li.innerText = `${item.decade}s: ${item.count} монети`;
                    topDecadesUsers.appendChild(li);
                });
            }
        })
        .catch(error => console.error(" Грешка при зареждане на статистиката:", error));
});
