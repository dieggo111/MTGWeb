<?php

require '../src/sql_queries.php';
require '../database/database.php';

$SQL = new SqlQueries();

$DB = new Database("../config/config.json");
$DB->initConnection();

// $SQL->drop(['Sets', 'Cards']);

// $DB->createTables("../database/models.json");

// $SQL->insert("Sets", ["setName"], ["Ikora"]);


$DB->uploadSet("../test_data/Ikora.json");

$result = $SQL->fetchItems("Cards", ["card_id"], [1]);

print_r($result);
$DB->closeConnection();
?>