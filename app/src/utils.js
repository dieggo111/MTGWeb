var backendAddress = '/'
// var backendAddress = 'http://localhost:8000/'

function convertSqlArrays(array) {
    for (let i=0; i<array.length; i++) {
        array[i] = array[i].replace("{", "");
        array[i] = array[i].replace("}", "");
        array[i] = array[i].replace(",", " ");
    }
    return array;
}

function countDuplicates(item, array) {
    var count = 0;
    array.forEach(element => {
        if (element == item) {
            count += 1;
        }
    });
    return count;
}

function sortArrayByProp(prop, array) {
    return array.sort((a, b) => (a[prop] > b[prop]) ? 1 : -1)
}

export {convertSqlArrays, countDuplicates, sortArrayByProp, backendAddress};
