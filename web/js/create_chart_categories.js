const element = document.querySelector("#chart")
const chart = new Chart(element);

function createChart(start_date, end_date) {
    const result = getSumByCategory(charges, user_category, categories, start_date, end_date);
    console.log("Подготовленные данные");
    console.log(result);
    let preparedCategories = Array.from(result.keys());
    let preparedSums = Array.from(result.values());
    try {
        chart.drawChart(preparedCategories, preparedSums, Chart.BAR_OPTIONS, "Сумма по категориям");
    } catch (e) {
        element.innerHTML = "<h3>*Красивое предупреждение о том, что данных за этот период нет*</h3>"
    }
}