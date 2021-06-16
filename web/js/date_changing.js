var start_date_element;
var end_date_element;


$(window).on("load", function() {
    start_date_element = $('#start_date');
    end_date_element = $('#end_date');

    dateChange();

    start_date_element.change(dateChange);
    end_date_element.change(dateChange);
});


function dateChange() {
    let start_date = new Date(start_date_element.val());
    let end_date = new Date(end_date_element.val());

    if (start_date == "Invalid Date" ||
        end_date == "Invalid Date"   ||
        start_date > end_date) {
        return;
    }
    console.log(start_date);
    console.log(end_date);
    createChart(start_date, end_date);
}
