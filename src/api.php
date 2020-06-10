<?php

require 'sql_queries.php';
require '../database/database.php';

class Api {

    private $db;
    private $requestMethod;

    public function __construct($requestMethod, $query)
    {
        $this->db = new Database("../config/config.json");
        $this->requestMethod = $requestMethod;
        $this->query = $query;
        $this->sql = new SqlQueries();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getCards();
                // if ($this->userId) {
                //     $response = $this->getUser($this->userId);
                // } else {
                //     $response = $this->getAllUsers();
                // };
                break;
            // case 'POST':
            //     $response = $this->awdawd();
            //     break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getCards()
    {
        $keys_values = $this->splitQuery();
        $result = $this->sql->fetchItems("Cards", $keys_values[0], $keys_values[1]);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    // private function getSetId($setName) {
    //     $result = $this->sql->fetchItems("Sets", ["setName"], [$setName]);
    //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
    //     $response['body'] = json_encode($result["id"]);
    //     return $response;
    // }


    private function splitQuery() {
        $key_array = array();
        $value_array = array();
        foreach (explode("&", $this->query) as $key_val_pair) {
            $exploded = explode("=", $key_val_pair);
            $key_array = array_push($key_array, $exploded[0]);
            $value_array = array_push($value_array, $exploded[1]);
        }
        return [$key_array, $value_array];
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

?>