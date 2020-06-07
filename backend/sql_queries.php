<?php


class SqlQueries
{
    public function drop($table) {
        if (is_array($table)) {
            $tables = implode(",", $table);
        } else {
            $tables = $table;
        }
        pg_query("DROP TABLE $tables CASCADE") or pg_last_error();
    }

    /**
     * Inserts a variable amount of values in database table.
     * @param   string  $table          name of the database table
     * @param   array   $column_array   holds the column names (strings)
     * @param   array   $value_array    holds the values (value types must
     * comply with the specifed data types)
     */
    public function insert($table, $column_array, $value_array) {
        $columns = implode(",", $column_array);
        $values = implode("','", $this->convertArrays($value_array));
        $query = "INSERT INTO $table($columns) VALUES ('$values')";
        echo "\n".$query."\n";
        $result = pg_query($query) or pg_last_error();
        return $result;
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
            if (!$result || is_null($result)) {
                return [];
            }
            return pg_fetch_all($result);
        } catch (Exception $e) {
            echo "Couldn't find table in database...";
            return [];
        }
    }

    public function fetchValue($table, $key, $value) {
        try {
            $result = pg_query("SELECT * FROM $table WHERE $key='$value'");
                if (!$result || is_null($result)) {
                return [];
            }
            return pg_fetch_all($result);
        } catch (Exception $e) {
            echo "Couldn't find anything...";
            return [];
        }
    }


    public function fetchColumns($table) {
        $table = strtolower($table);
        try {
            $result = pg_query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$table'");
            if (!$result || is_null($result)) {
                return [];
            }
            return $this->extractColumnNames(pg_fetch_all($result));
        } catch (Exception $e) {
            echo "Couldn't find anything...";
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

}

?>