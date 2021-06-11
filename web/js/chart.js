/**
 * This function get keys (X axis), values (Y axis) and chart options. Renders chart with incoming data.
 * @param keys
 * @param values
 * @param optionsObj
 * type: "bar"|"line"|"area" and others. <br />
 * text: string(title) <br />
 * valuesName: string <br />
 * xaxisType: "category"|"datetime"|"numeric"
 * @see https://apexcharts.com/docs/options/chart/type/
 */
function drawChart(keys, values, optionsObj) {
    console.log(keys);
    console.log(values);
    var options = {
        title: {
            text: optionsObj.text
        },
        chart: {
            type: optionsObj.type,
            height: 400,
        },
        series: [{
            name: optionsObj.valuesName,
            data: values
        }],
        xaxis: {
            type: optionsObj.xaxisType,
            categories: keys
        },
        yaxis: {
            reversed: optionsObj.reversed
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
}