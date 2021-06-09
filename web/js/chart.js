/**
 * This function get keys (X axis) and values (Y axis)
 * @param keys
 * @param values
 */
function chart(keys, values) {
    console.log(keys);
    console.log(values);
    var options = {
        title: {
            text: "Расходы"
        },
        chart: {
            type: 'bar',
            height: 400,
        },
        series: [{
            name: 'Сумма',
            type: 'line',
            data: values
        }],
        xaxis: {
            type: 'category',
            categories: keys
        },
        yaxis: {
            reversed: true
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
}