const chart = new Chart();

function createChart(start_date, end_date) {
    const result = getSumByCategory(charges, user_category, categories, start_date, end_date);
    let preparedCategories = Array.from(result.keys());
    let preparedSums = Array.from(result.values());
    chart.drawChart(preparedCategories, preparedSums, Chart.sumByCategoryBarOptions);
}