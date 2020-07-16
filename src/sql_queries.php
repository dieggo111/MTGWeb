<?php

// require_once $_SERVER['DOCUMENT_ROOT'].'/../logger/logger.php';

class SqlQueries
{

    private $arrayFields;
    // private $log
    public function __construct(array $arrayFields=[])
    {
        $this->arrayFields = $arrayFields;
        // if (is_null($logger)) {
        //     $this->log = new Logger();

        // }
    }

    public function drop($tables)
    {
        foreach ($tables as $table) {
            if (pg_query("DROP TABLE $table CASCADE")) {
                error_log("$table table succesfully dropped...");
            }
        }
    }

    /**
     * Inserts a variable amount of values in database table.
     * @param   string  $table          name of the database table
     * @param   array   $column_array   holds the column names (strings)
     * @param   array   $value_array    holds the values (value types must
     * comply with the specifed data types)
     */
    public function insert($table, $column_array, $value_array, $debug=false)
    {
        $columns = implode(",", $column_array);
        $values = implode("','", $this->convertArrays($value_array));
        $query = "INSERT INTO $table($columns) VALUES ('$values')";
        if ($debug === true) {
            error_log($query);
        }
        if (pg_query($query)) {
            error_log("Inserted data successfully...");
        }
    }

    public function insertMany($table, $column, $value_array, $debug=false)
    {
        $query = "INSERT INTO $table ($column) VALUES";
        foreach ($value_array as $value) {
            $query = $query." ('$value'),";
        }
        $query = substr($query, 0, -1);
        if ($debug === true) {
            error_log($query);
        }
        if (pg_query($query)) {
            error_log("Inserted data successfully...");
        }
    }

    /**
     * Fetches all data from specified table (and column). Returns an empty
     * array if nothing was found.
     * @param   string  $table      table name
     * @param   string  $column     specifies the column from which data
     *                              should be fetched
     * @return  array   $result     table data converted into an array
     */
    public function fetchAll($table, $column="*")
    {
        try {
            $result = pg_query("SELECT $column FROM $table");
            if ($this->handleInvalidRequest($result) === False) {
                return array();
            }
            return pg_fetch_all($result);
        } catch (Exception $e) {
            error_log("Couldn't find table in database...");
            return [];
        }
    }


    /**
     * Fetches items which comply with the key=value pair specification given by
     * the key_array and value_array. The key=value pairs are assigned by their
     * array index.
     * @param   string  $table          table name
     * @param   array   $key_array      array containing one/multiple column name(s)
     * @param   string  $value_array    array containing one/multiple values
     * @param   boolean $debug          Enable/disable debug logs
     * @return  array   $result         table data converted into an array
     *  */
    public function fetchItems(
        string $table,
        array $key_array,
        array $value_array,
        bool $debug=False
    ) {
        try {
            if (count($key_array) != count($value_array)) {
                throw new Exception("Number of array elements differs");
            }
            $query = "SELECT * FROM $table WHERE ";
            for ($i=0; $i<count($key_array); $i++) {
                if (gettype($value_array[$i]) != "array") {
                    $query = $this->genQueryArrayType(
                        $query,
                        $key_array[$i],
                        $value_array[$i]
                    );
                } else {
                    $query = $this->genQueryMultiValues(
                        $query,
                        $key_array[$i],
                        $value_array[$i]
                    );
                }
                if ($i != count($key_array) - 1) {
                    $query = $query." AND ";
                }
            }
            $result = pg_query($query);
                if ($debug === True) {
                    error_log($query);
                }
            if ($this->handleInvalidRequest($result) === False) {
                return array();
            }
            return pg_fetch_all($result);
        } catch (Exception $e) {
            error_log($e);
            return array();
        }
    }


    public function fetchColumns($table)
    {
        $table = strtolower($table);
        try {
            $result = pg_query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS
                                WHERE TABLE_NAME='$table'");
            if ($this->handleInvalidRequest($result) === False) {
                return array();
            }
            return $this->extractColumnNames(pg_fetch_all($result));
        } catch (Exception $e) {
            error_log("Couldn't find table in database...");
            return [];
        }
    }

    /**
     * Checks for nested arrays and converts them into a string format, which can
     * be digested by sql, which is its content surrounded by curly brackets.
     * @param   array   $array      initial array
     * @return  array   $array      converted array
     */
    private function convertArrays($array)
    {
        for($i=0; $i<count($array); $i++) {
            if (is_array($array[$i])) {
                $array[$i] = '{'.implode(",", $array[$i]).'}';
            }
        }
        return $array;
    }

    private function extractColumnNames($column_schemas)
    {
        $columns = [];
        foreach ($column_schemas as $col) {
            array_push($columns, $col["column_name"]);
        }
        return $columns;
    }

    private function handleInvalidRequest($result)
    {
        if ($result === False) {
            return False;
        }
        if (!pg_fetch_all($result) || is_null($result)) {
            return False;
        }
    }

    public function setArrayFields(array $arrayFields)
    {
        $this->arrayFields = $arrayFields;
    }

    public function setLogger(&$logger) {
        $this->logger = $logger;
    }

    private function genQueryArrayType(string $query, string $column, string $values) {
        if (in_array($column, $this->arrayFields) === true) {
            $query = $query."$column='{".$values."}'";
        } else {
            $query = $query."$column='$values'";
        }
        return $query;
    }

    private function genQueryMultiValues(string $query, string $column, array $values) {
        if (in_array($column, $this->arrayFields) === true) {
            $imploded_values = implode("}','{", $values);
            $query = $query."$column IN ('{".$imploded_values."}')";
        } else {
            $imploded_values = implode("','", $values);
            $query = $query."$column IN ('$imploded_values')";
        }
        return $query;
    }
}

?>