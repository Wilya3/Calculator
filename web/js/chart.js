/**
 * Class wrapper simplifying work with ApexCharts. Has preset options for chart.
 * Validate incoming data for each type of charts.
 * @requires <div id=chart> Div-container in which the chart should be drawn
 */
class Chart {
    entity = null;

    /**
     * @param element Div-container in which the chart should be drawn
     */
    constructor(element) {
        this.element = element;
    }

    /**
     * This function get keys (X axis), values (Y axis) and chart options.
     * Renders chart with incoming data. If chart is already rendered,
     * then deletes it and renders a new one.
     * If all values equals zero, throws ValueError
     * @param keys array of data, that will be drown (Y axis)
     * @param values array of column names, that will be drown (Y axis)
     * @param options one of Chart class static fields (or custom, see apexcharts documentations)
     * @param title title of Chart
     * @see https://apexcharts.com/docs/options/
     * @throws ValueError
     */
    drawChart(keys, values, options, title = "") {
        console.log(keys);
        console.log(values);
        options.title.text = title;
        if (options.chart.type === "pie") {
            Chart.deleteNegativeValues(values);
            options.series = values;
            options.labels = keys;
        } else {
            options.series[0].data = values;
            options.xaxis.categories = keys;
        }

        if (this.entity != null) {
            this.entity.destroy();
        }
        console.log("values");
        console.log(values);
        if (Chart.isValuesEqualsZero(values)) {
            throw new ValueError("The chart cannot be drawn with empty data");
        }
        console.log("Рисую график")
        this.entity = new ApexCharts(this.element, options);
        this.entity.render();

        element.firstChild.style.margin = "0 auto";
    }

    /**
     * Changes negative values to zero.
     * Checks if these values are valid for pie type of chart.
     * Pie chart cannot work with negative values
     * @param values array of floats
     */
    static deleteNegativeValues(values) {
        for (let i = 0; i < values.length; i++) {
            if (values[i] < 0) {
                values[i] = 0;
            }
        }
    }

    /**
     * Checks if all values equals 0.
     * @param values array of floats
     * @returns {boolean}
     */
    static isValuesEqualsZero(values) {
        for (let value of values) {
            if (value !== 0) {
                return false;
            }
        }
        return true;
    }


    static BAR_OPTIONS = {
        title: {
            text: "Сумма по дате"
        },
        chart: {
            type: "bar",
            height: document.documentElement.clientHeight * 0.7,
            width: "80%"
        },
        plotOptions: {
            bar: {
                barHeight: "100%",
                columnWidth: "60%",
                distributed: true,
                dataLabels: {
                    position: "bottom"
                }
            }
        },
        series: [{
            name: 'Сумма',
        }],
        colors: ["#FF4500", "#1E90FF", "#FF69B4", '#7B68EE', '#9ACD32', '#FFD700', '#98FB98', '#FFDEAD'],
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        xaxis: {
            type: "categories",
            // colors: ["#FF4500", "#1E90FF", "#FF69B4", '#7B68EE', '#9ACD32', '#FFD700', '#98FB98', '#FFDEAD'],
            labels: {
                show: false,
            },
        },
        tooltip: {
            followCursor: false,
            intersect: false,
        }
    };

    static PIE_OPTIONS = {
        chart: {
            type: "pie",
            width: "40%",
            height: document.documentElement.clientHeight * 0.75,
        },
        series: [],
    };
}
    class ValueError extends Error {
    constructor(message) {
        super(message);
        this.name = "ValueError";
    }
}