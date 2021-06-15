
class Chart {
    entity = null;

    static get sumByCategoryBarOptions() {
        return sumByCategoryBarOptions;
    }

    static get sumByCategoryPieOptions() {
        return sumByCategoryPieOptions;
    }
    static get sumByDateOptions() {
        return sumByDateOptions;
    }

    /**
     * This function get keys (X axis), values (Y axis) and chart options. Renders chart with incoming data. If chart is
     * already rendered, then deletes it and renders a new one.
     * @param keys array of data, that will be drown (Y axis)
     * @param values array of column names, that will be drown (Y axis)
     * @param options
     * @see https://apexcharts.com/docs/options/
     */
    drawChart(keys, values, options) {
        console.log(keys);
        console.log(values);
        if (options.chart.type === "pie") {  //If there is no any number greater than zero, doesn't draw pie
            let numberOfZeros = 0;
            for (let i = 0; i < values.length; i++) {
                if (values[i] > 0) continue;
                values[i] = 0;
                numberOfZeros++;
            }
            if (numberOfZeros === values.length) {
                console.log("Стоп")
                return;
            }
            console.log("values: " + values);
            options.series = values;
            options.labels = keys;
        } else {
            options.series[0].data = values;
            options.xaxis.categories = keys;
        }


        if (this.entity != null) {
            this.entity.destroy();
        }
        console.log("Создание диаграммы");
        this.entity = new ApexCharts(document.querySelector("#chart"), options);
        this.entity.render();
    }
}

const sumByCategoryBarOptions = {
    title: {
        text: "Сумма на категорию"
    },
    chart: {
        type: "bar",
        height: "100%",
    },
    plotOptions: {
        bar: {
            barHeight: "100%",
            distributed: true,
            dataLabels: {
                position: "bottom"
            }
        }
    },
    series: [{
        name: 'Сумма расходов за заданный промежуток времени',
    }],
    colors: ["#FF4500", "#1E90FF", "#FF69B4", '#7B68EE', '#9ACD32', '#FFD700', '#98FB98', '#FFDEAD'],
    stroke: {
        width: 1,
        colors: ["#fff"]
    },
    xaxis: {
        type: "categories",
        colors: ["#FF4500", "#1E90FF", "#FF69B4", '#7B68EE', '#9ACD32', '#FFD700', '#98FB98', '#FFDEAD'],
    },
    yaxis: {
        reversed: true,
    },
    tooltip: {
        theme: "light",
        followCursor: false,
        intersect: false,
    }
};

const sumByCategoryPieOptions = {
    chart: {
        type: "pie",
        height: "100%"
    },
    series: [],
};

const sumByDateOptions = {text: "Сумма по дате", type: "bar", xaxisType: "categories", reversed: true};
