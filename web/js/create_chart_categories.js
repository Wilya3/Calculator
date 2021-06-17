const element = document.querySelector("#chart")
const chart = new Chart(element);
let clicked_img_id = "pie";

function createChart(start_date="0000-01-01", end_date="") {
    const result = getSumByCategory(charges, user_category, categories, start_date, end_date);
    let preparedCategories = Array.from(result.keys());
    let preparedSums = Array.from(result.values());
    try {
        if (clicked_img_id === "pie") {
            chart.drawChart(preparedCategories, preparedSums, Chart.PIE_OPTIONS, "Сумма по категориям");
        }
        if (clicked_img_id === "bar") {
            chart.drawChart(preparedCategories, preparedSums, Chart.BAR_OPTIONS, "Сумма по категориям");
        }
    } catch (e) {
        element.innerHTML = "<h3>*Красивое предупреждение о том, что данных за этот период нет*</h3>"
    }
}

$("#pie").click(function() {
    clicked_img_id = this.id;
    dateChange();
});

$("#bar").click(function() {
    clicked_img_id = this.id;
    dateChange();
});


