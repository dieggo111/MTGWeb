<?php


require 'sql_queries.php';

$sql = new SqlQueries();

function initConnection() {
    $dbconn = pg_connect("host=localhost port=5432 user=postgres password=root")
    or die("Failed to connect to database");
    return $dbconn;
}

function createDefaultTables() {
    $models = json_decode(file_get_contents("../database/models.json"), true);

    if (pg_query($models["Sets"])) {
        echo "Set table succesfully created...\n";
    }
    if (pg_query($models["Data"])) {
        echo "Data table succesfully created...\n";
    }
}

function getJsonData($path) {
    $data = json_decode(file_get_contents($path), true);
    $columns = array_keys($data["Test"]);
    $values = array_values($data["Test"]);
    return [$columns, $values];
}

function getImageUrl($sf_id) {
    $content = json_decode(json_encode(file_get_contents($sf_id)));
    print_r($content["image_uris"]);
}



$arr = [];
$dbconn = initConnection();
// $sql->drop(['Sets', 'Data']);
// drop('Sets');

// createDefaultTables();
$data = getJsonData("../test_data/test.json");
// $sql->insert("Sets", ["setName"], ["Ikora"]);
// $sql->insert("Data", $data[0], $data[1]);

// $result = fetch('Data', 'colors');
$result = $sql->fetchAll('Data');

getImageUrl("https://api.scryfall.com/cards/"."d8a2e243-e446-46c6-8a37-e26620951c41");

print_r($result);
pg_close($dbconn);
?>