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
    foreach ($data as $card) {
        if (isset($card["frameEffect"]) || isset($card["flavorName"])) {
            $card = $card + array("additional_print" => "true");
        }
        $card = addCardProps($card, $setId);
        $card = purgeCardData($card);
        $card = handleApostrophe($card);
        $columns = array_keys($card);
        $values = array_values($card);
        $GLOBALS["sql"]->insert("Cards", $columns, $values);
        echo $card["name"]."\n";
    }
}

/**
 * Add the setId reference from 'Sets' table as well as image urls, which
 * can be fetched from 'scryfall.com' via the 'scryfallId'.
 */
function addCardProps($card, $setId) {
    $card = $card + array("setId" => $setId);
    $images = getImageUrl(
        "https://api.scryfall.com/cards/".$card["scryfallId"]
    );
    $card = $card + array("image_uri_small" => $images[0]);
    $card = $card + array("image_uri_normal" => $images[1]);
    return $card;
}

/**
 * Remove the redundant card information.
 * */
function purgeCardData($card) {
    $valid_columns = $GLOBALS["sql"]->fetchColumns('Cards');
    foreach(array_keys($card) as $key) {
        if (!in_array(strtolower($key), $valid_columns)) {
            unset($card[$key]);
        }
    }
    return $card;
}

function handleApostrophe($card) {
    $card["name"] = str_replace("'", "''", $card["name"]);
    if (isset($card["text"])) {
        $card["text"] = str_replace("'", "''", $card["text"]);
    }
    return $card;
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


// $result = $sql->fetchAll('Cards');

uploadSet("../test_data/Ikora.json", 1);

// print_r($result);
pg_close($dbconn);
?>