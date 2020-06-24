<?php

class Logger {

    public $log_cfg;
    private $default_template;
    private $log_template;
    private $log_file;
    public $header;

    public function __construct($config_path, $timezone='Europe/Berlin')
    {
        $log_cfg = json_decode(
            file_get_contents($config_path), true)["logging"];
        date_default_timezone_set($timezone);
        $this->default_template = "%datetime% [%level%] - %log%";
        $this->log_template = $this->default_template;
        $this->header = new Header();
        $this->log_file = new LogFile(
            $log_cfg["base_path"],
            $log_cfg["base_name"],
            $this->header
        );
    }

    /**
     * Basic logging function, which writes log to log file and console. The
     * log entry is created by replacing the template keys with the values in
     * the 'params' array. The template keys which must be sandwiched by '%'.
     * The key '%log%' is mandatory and must refer to the log message.
     * @param   array  $params      array which holds the template keys and log
     *                              parameters
     * @param   bool   $default     use default template if true
     *  */
    public function log(array $params, bool $default=false)
    {
        $params = $this->adjustLogType($params);
        if ($default === true) {
            $log = strtr($this->default_template, $params);
        } else {
            $log = strtr($this->log_template, $params);
        }
        $this->log_file->write($log);
        error_log($params["%log%"]);
    }

    /**
     * Set template for log output. The template must contain the 'log'
     * keyword. The 'log' keyword will be replaced by the actual log message
     * before output.
     * @param   string  $template       template string defining the log output
     *  */
    public function setTemplate(string $template)
    {
        if (strpos($template, "%log%") === false) {
            $this->log(
                [
                    "%level%" => "ERROR",
                    "%datetime%" => date('H:i:s:u'),
                    "%log%" => "Invalid template: template does not contain 'log' keyword"
                ],
                true
            );
        }
        $this->log_template = $template;
    }


    private function adjustLogType(array $dict)
    {
        if (gettype($dict["%log%"]) != "string") {
            $dict["%log%"] = json_encode($dict["%log%"]);
        }
        return $dict;
    }

}


class LogFile {

    private $base_name;
    private $base_path;
    private $header;

    public function __construct(string $base_path, string $base_name, &$header)
    {
        $this->base_name = $base_name;
        $this->base_path = $base_path;
        $this->file_name = $this->getFileName();
        $this->header = $header;
    }

    public function write($log)
    {
        $log_file = $this->getFileName();
        if (!file_exists($log_file)) {
            error_log("Creating new log file: $log_file");
            $stream = fopen($log_file, "wb");
            fwrite($stream, $this->header->getHeader());
            fclose($stream);
        }
        file_put_contents($log_file, $log."\n", FILE_APPEND);
        return $log_file;
    }

    public function setBasePath(string $base_path)
    {
        $this->base_path = $base_path;
    }

    public function setBaseName(string $base_name)
    {
        $this->base_name = $base_name;
    }

    private function getFileName()
    {
        if (is_dir($this->base_path)) {
            return $this->base_path.$this->base_name.date('Y-m-d').".txt";
        }
        throw new Exception("Basepath doesn't exist!");
    }

}

class Header {

    private $header;
    private $append_date;

    public function __construct(
        string $header="Server Log",
        bool $append_date=true)
    {
        $this->header = $header;
        $this->append_date = $append_date;
    }

    public function getHeader()
    {
        $header = $this->header;
        if ($this->append_date === true) {
            $header = $header." ".date('Y-m-d');
        }
        $underscore = str_repeat("#", strlen($header));
        return $header."\n".$underscore."\n";
    }

    public function setHeader(string $header="Server Log ")
    {
        $this->header = $header;
        // error_log("Set log file header to: $header");
    }

    public function setAppenDate(bool $append_date)
    {
        $this->append_date = $append_date;
    }


}

?>