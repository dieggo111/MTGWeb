<?php


require_once $_SERVER['DOCUMENT_ROOT'].'\..\src\sql_queries.php';
require_once $_SERVER['DOCUMENT_ROOT'].'\..\database\database.php';

class Api {

    private $db;
    private $requestMethod;
    private $query;
    private $sql;
    private $path;

    public function __construct($requestMethod, $path, $query)
    {
        $this->db = new Database("../config/config.json");
        $this->db->initConnection();
        $this->requestMethod = $requestMethod;
        $this->query = $this->processQuery($query);
        $this->sql = new SqlQueries();
        $this->path = $path;
        // $this->cardsController = new CardsController();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                // if (is_null($this->query)) {
                //     $response = $this->unprocessableEntityResponse();
                //     break;
                // }
                if ($this->path == "/cards") {
                    $response = $this->getCards();
                }
                if ($this->path == "/sets") {
                    $response = $this->getSets();
                }
                if ($this->path == "/types") {
                    $response = $this->getTypes();
                }
                if ($this->path == "/supertypes") {
                    $response = $this->getSupertypes();
                }
                break;
            case 'POST':
                if ($this->path == "/cards") {
                    $response = $this->insertCard();
                }
                break;
            default:
                $response = $this->notFoundResponse();
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getCards()
    {
        if (is_null($this->query)) {
            $result = $this->sql->fetchAll("Cards");
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        $keys_values = $this->splitQuery();
        $result = $this->sql->fetchItems("Cards", $keys_values[0], $keys_values[1]);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getSets()
    {
        if (is_null($this->query)) {
            $result = $this->sql->fetchAll("Sets", "setName");
            $result = $this->arrayToList($result, "setname");
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        $keys_values = $this->splitQuery();
        $result = $this->sql->fetchItems("Sets", $keys_values[0], $keys_values[1]);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getTypes()
    {
        if (is_null($this->query)) {
            $result = $this->sql->fetchAll("Types", "card_type");
            $result = $this->arrayToList($result, "card_type");
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        $keys_values = $this->splitQuery();
        $result = $this->sql->fetchItems("Types", $keys_values[0], $keys_values[1]);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    private function getSupertypes()
    {
        if (is_null($this->query)) {
            $result = $this->sql->fetchAll("Supertypes", "super_type");
            $result = $this->arrayToList($result, "super_type");
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);
            return $response;
        }
        $keys_values = $this->splitQuery();
        $result = $this->sql->fetchItems("Supertypes", $keys_values[0], $keys_values[1]);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;

    }

    private function insertCard()
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($this->query);
    }

    private function processQuery($query)
    {
        if (is_null($query)) {
            return $query;
        }
        $query = str_replace(["+", "%20"], " ", $query);
        return $query;
    }

    private function splitQuery()
    {
        $key_array = array();
        $value_array = array();
        foreach (explode("&", $this->query) as $key_val_pair) {
            $exploded = explode("=", $key_val_pair);
            array_push($key_array, $exploded[0]);
            array_push($value_array, $exploded[1]);
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

    private function arrayToList($array, $key)
    {
        $list = Array();
        foreach ($array as $nested_array) {
            array_push($list, $nested_array[$key]);
        }
        return $list;
    }
}

?>