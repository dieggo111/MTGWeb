function convertSqlArrays(array) {
    for (let i=0; i<array.length; i++) {
        array[i] = array[i].replace("{", "");
        array[i] = array[i].replace("}", "");
        array[i] = array[i].replace(",", " ");
    }
    return array;
}

export {convertSqlArrays};