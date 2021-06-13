/**
 * This function gets tables, matches them so that each category contains sum of charges belongs this category.
 * @param charges Object. It should contain:
 * name, amount, user_category_id
 * @param user_category Object. It should contain:
 * id, category_id
 * @param categories Object. It should contain:
 * id, name
 * @returns {Map<string, float>} It returns map, which keys contains categories and values contains related sums.
 * (For example: function returns {'food': 200, 'car': 1500, 'travel': 1000}. It means food spending was 200, car
 * spending was 1500 and travel spending was 1000).
 */
function prepareSumByCategory(charges, user_category, categories) {
    let result = new Map();
    for (let charge of charges) {
        let category = getCategoryByCharge(charge, user_category, categories);
        let oldSum = result.get(category.name)
        if (typeof oldSum == "undefined") oldSum = 0;
        let newSum = Math.round(oldSum + parseFloat(charge.amount));
        result.set(category.name, newSum);
    }
    return result;
}

function getCategoryByCharge(charge, user_category, categories) {
    for (let uc of user_category) {
        if (uc.id === charge.user_category_id) {

            for (let category of categories) {
                if (category.id === uc.category_id) {
                    return category;
                }
            }

        }
    }
}
