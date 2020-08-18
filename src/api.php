<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/../src/sql_queries.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../database/database.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../logger/logger.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../src/security_gateway.php';

class Api {

    private $db;
    private $requestMethod;
    private $query;
    private $sql;
    private $url_path;
    private $log;

    public function __construct($requestMethod, $url_path, $query)
    {
        $this->log = new Logger();
        $this->log->header->setHeader("MTGWeb Server Logs");
        $this->security_gateway = new SecurityGateway(
            $requestMethod,
            $url_path,
            $query,
            $this->log
        );
        $this->db = new Database("../config/config.json");
        $this->db->initConnection();
        $this->requestMethod = $requestMethod;
        $this->query = $this->processQuery($query);
        $this->sql = new SqlQueries($this->getArrayFields());
        $this->sql->setLogger($this->log);
        $this->url_path = $url_path;
    }

    public function processRequest()
    {
        if ($this->security_gateway->securityCheck() === false) {
            $response = $this->unprocessableEntityResponse();
        } else {
            switch ($this->requestMethod) {
                case 'GET':
                    // if (is_null($this->query)) {
                    //     $response = $this->unprocessableEntityResponse();
                    //     break;
                    // }
                    if ($this->url_path == "/cards") {
                        $response = $this->getCards();
                    }
                    if ($this->url_path == "/sets") {
                        $response = $this->getSets();
                    }
                    if ($this->url_path == "/types") {
                        $response = $this->getTypes();
                    }
                    if ($this->url_path == "/supertypes") {
                        $response = $this->getSupertypes();
                    }
                    break;
                case 'POST':
                    if ($this->url_path == "/upload") {
                        $response = $this->insertCard();
                    }
                    break;
                default:
                    $response = $this->notFoundResponse();
            }
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
        $result = $this->sql->fetchItems("Cards", $keys_values[0], $keys_values[1], true);
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
        // $response['body'] = json_encode($this->query);
        $data = json_decode(file_get_contents('php://input'));
        $this->log->debug($data);
        return $response;
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
            $idx = array_search($exploded[0], $key_array, true);
            if ($idx !== false) {
                if (gettype($value_array[$idx]) == "array") {
                    array_push($value_array[$idx], $exploded[1]);
                } else {
                    $value_array[$idx] = [$value_array[$idx], $exploded[1]];
                }
            } else {
                array_push($key_array, $exploded[0]);
                array_push($value_array, $exploded[1]);
            }
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
        $response['body'] = json_encode([]);
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


    /**
     * Check meta data from specific table in order to identify columns which
     * contain arrays.
     */
    private function getArrayFields($table_name="cards")
    {
        $array_fields = Array();
        foreach(pg_meta_data($this->db->dbconn, $table_name) as $column => $fields) {
            if (strpos($fields["type"], "_") !== false) {
                array_push($array_fields, $column);
            }
        }
        return $array_fields;
    }

}

?>