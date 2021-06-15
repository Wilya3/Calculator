const chart = new Chart();

function createChart(start_date, end_date) {
    const result = getSumByCharge(charges, start_date, end_date);
    let preparedCharges = Array.from(result.keys());
    let preparedSums = Array.from(result.values());
    chart.drawChart(preparedCharges, preparedSums, Chart.sumByCategoryBarOptions);
}