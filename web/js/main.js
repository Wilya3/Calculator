const sumByCategoryOptions = {text: "Сумма по категориям", type: "bar", xaxisType: "categories", reversed: true};
const sumByDateOptions = {text: "Сумма по дате", type: "bar", xaxisType: "categories", reversed: true};

var start_date_element;
var end_date_element;

$(window).on("load", function() {
    // const start_date_element = $(document).ready($('#start_date'));
    // const end_date_element = $(document).ready($('#end_date'));
    start_date_element = $('#start_date');
    end_date_element = $('#end_date');

    drawSumByCategory(new Date(start_date_element.val()), new Date(end_date_element.val()));

    // Change chart on date changing
    start_date_element.change(dateChange);
    end_date_element.change(dateChange);
});

function dateChange() {
    let start_date = new Date(start_date_element.val());
    let end_date = new Date(end_date_element.val());
    console.log(start_date);
    console.log(end_date);
    if (start_date == "Invalid Date" ||
        end_date == "Invalid Date"   ||
        start_date > end_date) console.log("Ошибка");
    drawSumByCategory(start_date, end_date);
}


function drawSumByCategory(start_date, end_date) {
    const result = prepareSumByCategory(charges, user_category, categories, start_date, end_date);
    let preparedCategories = Array.from(result.keys());
    let preparedSums = Array.from(result.values());

    drawChart(preparedCategories, preparedSums, sumByCategoryOptions);
}



