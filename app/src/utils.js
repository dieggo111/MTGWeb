var backendAddress = 'http://mtgweb_backend:8080/'
// var backendAddress = 'http://localhost:8000/'

function convertSqlArrays(array) {
    for (let i=0; i<array.length; i++) {
        array[i] = array[i].replace("{", "");
        array[i] = array[i].replace("}", "");
        array[i] = array[i].replace(",", " ");
    }
    return array;
}

export {convertSqlArrays, backendAddress};