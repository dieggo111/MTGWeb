<?php

class Logger {

    public $log_cfg;
    private $default_template = "datetime [level] - log";
    private $log_template = "datetime [level] - log";

    public function __construct($config_path, $timezone='Europe/Berlin')
    {
        $this->log_cfg = json_decode(
            file_get_contents($config_path), true)["logging"];
        date_default_timezone_set($timezone);
        // $this->log("../logs/mtgweb_server_log_2020-06-22.txt", ["level" => "INFO", "datetime" => date('H:i:s:u'), "log" => "loggggggggggg"]);
    }

    public function initLogFile($header="")
    {
        $date = date('Y-m-d');
        $log_file = $this->log_cfg["basepath"]
                    .$this->log_cfg["basename"]
                    .$date.".txt";
        if (!file_exists($log_file)) {
            $stream = fopen($log_file, "wb");
            fwrite($stream, $header);
            fclose($stream);
        }
        return $log_file;
    }

    public function log($log_file, $dict, $default=false)
    {
        $dict = $this->adjustLogType($dict);
        if ($default === true) {
            $log = strtr($this->default_template, $dict);
        } else {
            $log = strtr($this->log_template, $dict);
        }

        file_put_contents($log_file, $log, FILE_APPEND);
        error_log($dict["log"]);
    }

    /**
     * Set template for log output. The template must contain the 'log'
     * keyword. The 'log' keyword will be replaced by the actual log message
     * before output.
     * @param   string  $template       template string defining the log output
     *  */
    public function setTemplate($template)
    {
        if (strpos($template, "log") === false) {
            $this->log(
                [
                    "level" => "ERROR",
                    "datetime" => date('H:i:s:u'),
                    "log" => "Invalid template: template does not contain 'log' keyword"
                ],
                true
            );
        }
        $this->log_template = $template;
    }

    private function adjustLogType($dict)
    {
        if (gettype($dict["log"]) != "string") {
            $dict["log"] = json_encode($dict["log"]);
        }
        return $dict;
    }

}
?>