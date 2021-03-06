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
        options.title.text = title;
        if (options.chart.type === "pie") {
            // Checks if values are valid for pie chart.
            // Pie chart cannot work with negative values
            Chart.absoluteNegativeValues(values);
            options.series = values;
            options.labels = keys;
        } else {
            options.series[0].data = values;
            options.xaxis.categories = keys;
        }

        if (this.entity != null) {
            this.entity.destroy();
        }
        if (Chart.isValuesEqualsZero(values)) {
            throw new ValueError("The chart cannot be drawn with empty data");
        }
        this.entity = new ApexCharts(this.element, options);
        this.entity.render();

        element.firstChild.style.margin = "0 auto";
    }

    /**
     * Changes negative numbers to their absolute values.
     * @param values array of floats
     */
    static absoluteNegativeValues(values) {
        for (let i = 0; i < values.length; i++) {
            if (values[i] < 0) {
                values[i] *= -1;
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
            text: "?????????? ???? ????????"
        },
        chart: {
            type: "bar",
            height: document.documentElement.clientHeight * 0.533,
            redrawOnParentResize: true,
        },
        plotOptions: {
            bar: {
                barHeight: "100%",
                columnWidth: "60%",
                distributed: true,
                dataLabels: {
                    position: "center"
                },
            }
        },
        series: [{
            name: '??????????',
        }],
        colors: ["#FF4500", "#1E90FF", "#FF69B4", '#7B68EE', '#9ACD32', '#FFD700', '#98FB98', '#FFDEAD'],
        stroke: {
            width: 1,
            colors: ["#fff"]
        },
        xaxis: {
            type: "categories",
            // labels: {
            //     show: true,
            // },
        },
        legend: {
            show: false,
        },
        // yaxis: {
        //     reversed: true,
        // },
        tooltip: {
            followCursor: false,
            intersect: false,
        }
    };

    static PIE_OPTIONS = {
        chart: {
            type: "pie",
            // width: "40%",
            height: document.documentElement.clientHeight * 0.55,
            parentHeightOffset: 0,
            redrawOnParentResize: true,
        },
        title: {
            text: "",
        },
        series: [{}],
    };
}
    class ValueError extends Error {
    constructor(message) {
        super(message);
        this.name = "ValueError";
    }
}