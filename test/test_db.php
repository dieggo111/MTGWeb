<?php

require '../src/sql_queries.php';
require '../database/database.php';

$SQL = new SqlQueries();

$DB = new Database("../config/config.json");
$DB->initConnection();
$RESULT = array();

// $SQL->drop(['Sets', 'Cards', 'Types', 'Supertypes']);
// $DB->createTables("../database/models.json");
// $DB->createDefaultEntries("../database/default_values.json");
$DB->uploadSet("../test_data/Throne of Eldraine.json");
// $DB->uploadSet("../test_data/Throne of Eldraine.json");

// $RESULT = $SQL->fetchAll("Types", "card_type");
// $RESULT = $SQL->fetchAll("Supertypes");


// $RESULT = $SQL->fetchItems("Cards", ["types"], ["{Artifact,Creature}"]);



// print_r($RESULT);
$DB->closeConnection();
?>