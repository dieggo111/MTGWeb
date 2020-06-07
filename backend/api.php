<?php


require 'sql_queries.php';

$sql = new SqlQueries();

function initConnection() {
    $dbconn = pg_connect("host=localhost port=5432 user=postgres password=root")
    or die("Failed to connect to database");
    return $dbconn;
}

function createTables() {
    $models = json_decode(file_get_contents("../database/models.json"), true);

    if (pg_query($models["Sets"])) {
        echo "Set table succesfully created...\n";
    }
    if (pg_query($models["Cards"])) {
        echo "Data table succesfully created...\n";
    }
}

function uploadCards($path, $setId) {
    $data = getJsonData($path)["cards"];
    $data = addCardProps($data, $setId);
    $data = adjustCardData($data);
    // print_r($data);
    foreach ($data as $card) {
        $columns = array_keys($card);
        $values = array_values($card);
        // print_r($columns);
        $GLOBALS["sql"]->insert("Cards", $columns, $values);
    }
}

function addCardProps($data, $setId) {
    for ($i=0; $i<count($data); $i++) {
        $data[$i] = $data[$i] + array("setId" => $setId);
        $images = getImageUrl(
            "https://api.scryfall.com/cards/".$data[$i]["scryfallId"]
        );
        $data[$i] = $data[$i] + array("image_uri_small" => $images[0]);
        $data[$i] = $data[$i] + array("image_uri_normal" => $images[1]);
    }
    return $data;
}

function adjustCardData($data) {
    $valid_columns = $GLOBALS["sql"]->fetchColumns('Cards');
    // print_r($valid_columns);
    foreach ($data as $card_nr => $card_data) {
        foreach(array_keys($card_data) as $key) {
            // print_r($value);
            if (!in_array(strtolower($key), $valid_columns)) {
                unset($data[$card_nr][$key]);
            }

        }
    }
    return $data;
}

function getJsonData($path) {
    $data = json_decode(file_get_contents($path), true);
    return $data;
}

function getImageUrl($sf_id) {
    $content = json_decode(file_get_contents($sf_id), true);
    return [$content["image_uris"]["small"], $content["image_uris"]["normal"]];
}



$dbconn = initConnection();
// $sql->drop(['Sets', 'Cards']);
// drop('Sets');

// createTables();
// $data = getJsonData("../test_data/test.json");
// $sql->insert("Sets", ["setName"], ["Ikora"]);
// $sql->insert("Data", $data[0], $data[1]);



// $result = $sql->fetchAll('Cards');

// uploadCards(
//     "../test_data/test.json",
//     $sql->fetchValue("Sets", "setName", "Ikora")[0]["id"]
// );

// print_r($result);
pg_close($dbconn);
?>