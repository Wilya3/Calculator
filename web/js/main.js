
const sumByCategoryOptions = {text: "Сумма по категориям", type: "bar", xaxisType: "categories", reversed: true};
const sumByDateOptions = {text: "Сумма по дате", type: "bar", xaxisType: "categories", reversed: true};


sumByCategory();


function sumByCategory() {
    const result = prepareSumByCategory(charges, user_category, categories);
    let preparedCategories = Array.from(result.keys());
    let preparedSums = Array.from(result.values());

    drawChart(preparedCategories, preparedSums, sumByCategoryOptions);
}



