/**
 * This function matches tables so, that each category will contain sum of charges belongs this category.
 * Takes only those charges that are included in the time period between start_date and end_date
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
function getSumByCategory(charges, user_category, categories, start_date, end_date) {
    let result = new Map();
    for (let charge of charges) {
        let category = findCategoryByCharge(charge, user_category, categories);

        let date = new Date(charge.date);
        if (!checkDate(date, start_date, end_date)) {
            continue;
        }
        if (result.has(category.name)) {
            let oldSum = result.get(category.name);
            let newSum = Math.round(oldSum + parseFloat(charge.amount));
            result.set(category.name, newSum);
        } else {
            result.set(category.name, Math.round(parseFloat(charge.amount)));
        }
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

/**
 * Finds the category, to which this charge belongs.
 * @param charge Object. It should contain: user_category_id
 * @param user_category Array of objects. Object should contain: id, category_id
 * @param categories Array of objects. Object should contain: id
 * @returns {Object}
 */
function findCategoryByCharge(charge, user_category, categories) {
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

/**
 * This function gets table of charges and returns a map,
 * that contains pairs of date and sum.
 * Takes only those charges that are included in the time period between start_date and end_date
 * @param charges Array of objects. Object should contain: id, category_id
 * @param start_date Date
 * @param end_date Date
 * @returns {Map<string, float>} It returns map, which keys contains dates and values contains related sums.
 */
function getSumByDate(charges, start_date, end_date) {
    let result = new Map();
    for (let charge of charges) {
        let rawDate = new Date(charge.date);
        if (!checkDate(rawDate, start_date, end_date)) {
            continue;
        }
        let date = rawDate.getFullYear()+"-"+(rawDate.getMonth() + 1)+"-"+rawDate.getDate();

        if (result.has(date)) {
            let oldSum = result.get(date);
            let newSum = Math.round(oldSum + parseFloat(charge.amount));
            result.set(date, newSum);
        } else {
            result.set(date, Math.round(parseFloat(charge.amount)));
        }
    }
    return result;
}
