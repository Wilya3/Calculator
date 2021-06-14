/**
 * This function gets tables, matches them so that each category contains sum of charges belongs this category.
 * @param charges Object. It should contain:
 * name, amount, user_category_id
 * @param user_category Object. It should contain:
 * id, category_id
 * @param categories Object. It should contain:
 * id, name
 * @param start_date Date object represents start border
 * @param end_date Date object represents end border
 * @returns {Map<string, float>} It returns map, which keys contains categories and values contains related sums.
 * (For example: function returns {'food': 200, 'car': 1500, 'travel': 1000}. It means food spending was 200, car
 * spending was 1500 and travel spending was 1000).
 */
function prepareSumByCategory(charges, user_category, categories, start_date, end_date) {
    let result = new Map();
    for (let charge of charges) {
        let category = getCategoryByCharge(charge, user_category, categories);
        if (!result.has(category.name)) result.set(category.name, 0);

        let date = new Date(charge.date);
        if (!checkDate(date, start_date, end_date)) {
            continue;
        }

        let oldSum = result.get(category.name);
        let newSum = Math.round(oldSum + parseFloat(charge.amount));
        result.set(category.name, newSum);
    }
    return result;
}

/**
 * Is date between start_date and end_date
 * @param date Date
 * @param start_date Date
 * @param end_date Date
 * @returns {boolean}
 */
function checkDate(date, start_date, end_date) {
    return date >= start_date && date <= end_date;
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
    throw new ReferenceError('There is no any category, which references to this charge.');
}
