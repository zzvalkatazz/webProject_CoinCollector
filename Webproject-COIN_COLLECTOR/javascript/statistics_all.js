document.addEventListener("DOMContentLoaded",function()
{
fetch('../php/statistics_all.php')
.then(response=>response.json())
.then(data=> {


    
    document.getElementById('total-coins-all').innerText=data.total_coins || 0;
    document.getElementById('total-value-all').innerText = (parseFloat(data.total_value) || 0).toFixed(2) + " лв";

    
    const continentStatistics=document.getElementById("continent-all-statistics"); 
    continentStatistics.innerHTML="";
    if(!data.continent_data ||data.continent_data.length === 0)
    {
        continentStatistics.innerHTML="<p>Няма монети в галерията по континенти</p>";
    }
    else{
        data.continent_data.forEach(item=>
            {
                let li=document.createElement("li");
                li.innerText=`${item.continent}: ${item.count} монети`;
                continentStatistics.appendChild(li);
            
            
        });
    }

const countryStatistics=document.getElementById("country-all-statistics");
countryStatistics.innerHTML="";
if(!data.country_data || data.country_data.length === 0)
{
        countryStatistics.innerHTML="<p>Няма монети в галерията по държави</p>";
}
else
{
        data.country_data.forEach(item=>{
        let li=document.createElement("li");
        li.innerText=`${item.country}: ${item.count} монети`;
        countryStatistics.appendChild(li);
    });
}

const topUsers=document.getElementById("top-users");
topUsers.innerHTML="";
if(!data.user_data || data.user_data.length==0)
{
    topUsers.innerHTML="<p>Все още няма активни потребители.</p>";
}

else{
    data.user_data.forEach(user=> 
    {
        let li=document.createElement("li");
        li.innerText=`${user.username}: ${user.count} монети`;
        topUsers.appendChild(li);
    });
}
const topDecadesAll = document.getElementById("top-decades-all");
topDecadesAll.innerHTML="";
if(!data || !data.top_decades_all || data.top_decades_all.length===0)
{
    topDecadesAll.innerHTML="<p>Няма информация за класация на монети по десетилетие</p>"
}
 else
 {
    data.top_decades_all.forEach(item=>{
        let li= document.createElement("li");
        li.innerText=`${item.decade}s:${item.count} монети`;
        topDecadesAll.appendChild(li);
    });

}
 
})
.catch(error=>console.error("Грешка при отварянето на статистиката:",error));
});
