const element = document.querySelector("#chart")
const chart = new Chart(element);

function createChart(start_date, end_date) {
    console.log(charges);
    const result = getSumByDate(charges, start_date, end_date);
    let preparedDates = Array.from(result.keys());
    let preparedCharges = Array.from(result.values());
    try {
        chart.drawChart(preparedDates, preparedCharges, Chart.BAR_OPTIONS, "Сумма по дате");
    } catch (e) {
        element.innerHTML = "<h3>*Красивое предупреждение о том, что данных за этот период нет*</h3>"
    }
}