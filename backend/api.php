<?php


require 'sql_queries.php';

$sql = new SqlQueries();

function initConnection() {
    $dbconn = pg_connect("host=localhost port=5432 user=postgres password=root")
    or die("Failed to connect to database");
    return $dbconn;
}

function createTables($model_path) {
    $models = json_decode(file_get_contents($model_path), true);
    foreach ($models as $model_name => $model) {
        echo $model_name;
        if (pg_query($model)) {
            echo "$model_name table succesfully created...\n";
        }
    }
}

function uploadSet($path, $setId=null) {
    if (is_null($setId)) {
        $setId = $GLOBALS["sql"]->fetchValue(
            "Sets", "setName", basename($path, ".json"));
        }
    if (empty($setId)) {
        echo "Couldn't find set reference in database...\n";
        return false;
    }
    if (is_array($setId)) {
        $setId = $setId[0]["id"];
    }
    $data = getJsonData($path)["cards"];
    $data = addCardProps($data, $setId);
    $data = adjustCardData($data);
    foreach ($data as $card) {
        $columns = array_keys($card);
        $values = array_values($card);
        $GLOBALS["sql"]->insert("Cards", $columns, $values);
    }
}

/**
 * Add the setId reference from 'Sets' table as well as image urls, which
 * can be fetched from scryfall.com via the scryfallId.
 */
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

/**
 * Remove the redundant card information.
 * */
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

// createTables("../database/models.json");

// $sql->insert("Sets", ["setName"], ["Ikora"]);

// $data = getJsonData("../test_data/test.json");
// $sql->insert("Data", $data[0], $data[1]);



// $result = $sql->fetchAll('Cards');

uploadSet("../test_data/test.json", 1);

// print_r($result);
pg_close($dbconn);
?>