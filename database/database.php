<?php

class Database {

    private $credentials;
    public $dbconn;

    public function __construct($config_path)
    {
        $this->credentials = json_decode(
            file_get_contents($config_path), true)["database"];
    }

    public function initConnection()
    {
        $this->dbconn = pg_connect(
            "host=".$this->credentials["host"]." ".
            "port=".$this->credentials["port"]." ".
            "user=".$this->credentials["user"]." ".
            "password=".$this->credentials["password"])
            or die("Failed to connect to database");
    }

    public function closeConnection()
    {
        pg_close($this->dbconn);
    }

    public function createTables($model_path) {
        $models = json_decode(file_get_contents($model_path), true);
        foreach ($models as $model_name => $model) {
            echo $model_name;
            if (pg_query($model)) {
                echo "$model_name table succesfully created...\n";
            }
        }
    }

    public function uploadSet($path, $setName=null) {
        if (is_null($setName)) {
            $setId = $GLOBALS["sql"]->fetchItems(
                "Sets", ["setName"], [basename($path, ".json")]);
            }
        if (empty($setId)) {
            echo "Couldn't find set reference in database...\n";
            return false;
        }
        if (is_array($setId)) {
            $setId = $setId[0]["setname"];
        }
        $data = $this->getJsonData($path)["cards"];
        foreach ($data as $card) {
            if (isset($card["frameEffect"]) || isset($card["flavorName"])) {
                $card = $card + array("additional_print" => "true");
            }
            $card = $this->addCardProps($card, $setId);
            $card = $this->purgeCardData($card);
            $card = $this->handleApostrophe($card);
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
    private function addCardProps($card, $setId) {
        $card = $card + array("setId" => $setId);
        $images = $this->getImageUrl(
            "https://api.scryfall.com/cards/".$card["scryfallId"]
        );
        $card = $card + array("image_uri_small" => $images[0]);
        $card = $card + array("image_uri_normal" => $images[1]);
        return $card;
    }

    /**
     * Remove the redundant card information.
     * */
    private function purgeCardData($card) {
        $valid_columns = $GLOBALS["sql"]->fetchColumns('Cards');
        foreach(array_keys($card) as $key) {
            if (!in_array(strtolower($key), $valid_columns)) {
                unset($card[$key]);
            }
        }
        return $card;
    }

    private function handleApostrophe($card) {
        $card["name"] = str_replace("'", "''", $card["name"]);
        if (isset($card["text"])) {
            $card["text"] = str_replace("'", "''", $card["text"]);
        }
        return $card;
    }

    private function getJsonData($path) {
        $data = json_decode(file_get_contents($path), true);
        return $data;
    }

    private function getImageUrl($sf_id) {
        $content = json_decode(file_get_contents($sf_id), true);
        return [$content["image_uris"]["small"], $content["image_uris"]["normal"]];
    }


}
