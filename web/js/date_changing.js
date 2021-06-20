var start_date_element;
var end_date_element;


$(window).on("load", function() {
    start_date_element = $('#start_date');
    end_date_element = $('#end_date');

    dateChange();

    start_date_element.change(dateChange);
    end_date_element.change(dateChange);
});

/**
 * Get dates from start_date_element and end_date_element.
 * Checks are they correct. Calls createChart() function.
 * If date is incorrect, sets the value as today.
 * If the value of start_date_element is greater than value of end_date_element,
 * sets the end_date_element's value to both.
 */
function dateChange() {
    let start_date = new Date(start_date_element.val());
    let end_date = new Date(end_date_element.val());

    if (start_date == "Invalid Date") {
        start_date = new Date();
        start_date_element.val(start_date.toISOString().slice(0, 10));
    }
    if (end_date == "Invalid Date") {
        end_date = new Date();
        end_date_element.val(end_date.toISOString().slice(0, 10));
    }
    console.log(start_date > end_date);
    if (start_date > end_date) {
        start_date_element.val(end_date.toISOString().slice(0, 10));
        end_date_element.val(start_date.toISOString().slice(0, 10));
        start_date = end_date;
    }

    createChart(start_date, end_date);
}
