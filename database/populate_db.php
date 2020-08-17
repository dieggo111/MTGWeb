<?php

require '../src/sql_queries.php';
require '../database/database.php';

$SQL = new SqlQueries();

$DB = new Database("../config/config.json");
$DB->initConnection();

// $SQL->drop(['Sets', 'Cards', 'Types', 'Supertypes']);
$DB->createTables("../database/models.json");
$DB->createDefaultEntries("../database/default_values.json");
$DB->uploadSet("../test_data/Ikoria - Lair of Behemoths.json");
$DB->uploadSet("../test_data/Theros Beyond Death.json");
$DB->uploadSet("../test_data/Throne of Eldraine.json");

$DB->closeConnection();
?>