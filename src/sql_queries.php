<?php

class SqlQueries
{
    public function drop($tables) {
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
    public function insert($table, $column_array, $value_array, $debug=false) {
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

    /**
     * Fetches all data from specified table. Returns an empty array if nothing
     * was found.
     * @param   string  $table      table name
     * @return  array   $result     table data converted into an array
     */
    public function fetchAll($table) {
        try {
            $result = pg_query("SELECT * FROM $table");
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
     * @return  array   $result     table data converted into an array
     *  */
    public function fetchItems($table, $key_array, $value_array, $debug=False) {
        try {
            if (count($key_array) != count($value_array)) {
                throw new Exception("Number of array elements differs");
            }
            $query = "SELECT * FROM $table WHERE $key_array[0]='$value_array[0]'";
            if (count($key_array) > 1) {
                for ($i=1; $i<count($key_array); $i++) {
                    $query = $query." AND $key_array[$i]='$value_array[$i]'";
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


    public function fetchColumns($table) {
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
    private function convertArrays($array) {
        for($i=0; $i<count($array); $i++) {
            if (is_array($array[$i])) {
                $array[$i] = '{'.implode(",", $array[$i]).'}';
            }
        }
        return $array;
    }

    private function extractColumnNames($column_schemas) {
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
}

?>