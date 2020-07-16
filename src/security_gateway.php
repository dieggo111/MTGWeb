<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/../logger/logger.php';


class SecurityGateway {

    private $log;
    private $requestMethod;
    private $url_path;
    private $query;
    private $security_params;

    public function __construct(
        string $requestMethod,
        string $url_path,
        string $query,
        &$logger
    )
    {
        $this->log = $logger;
        $this->requestMethod = $requestMethod;
        $this->url_path = $url_path;
        $this->query = $query;
        $this->security_params = json_decode(
            file_get_contents(__DIR__."/security.json"), true);
    }

    public function securityCheck() {
        if ($this->whiteList() !== true) {
            return false;
        }
        if ($this->blackList() !== true) {
            return false;
        }
        return true;
    }

    private function whiteList() {
        if (!in_array(
                $this->url_path,
                $this->security_params["whitelist"]["paths"])) {
            return false;
        }
        if (!in_array(
            $this->requestMethod,
            $this->security_params["whitelist"]["requestMethods"])) {
        return false;
        }
        return true;
    }

    private function blackList() {
        foreach($this->security_params["blacklist"]["characters"] as $char) {
            if (strpos($this->query, $char) !== false) {
                return false;
            }
        }
        return true;
    }



}

?>