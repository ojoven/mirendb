<?php

class SqlIgnore {

    public $rules;

    /** SQL Ignore **/
    public function __construct() {

        $file = file(dirname(ROOT_PATH) . "/.sqlignore");
        $rules = array();
        foreach($file as $key => $line) {
            if ($line[0]!="#") { // comments
                array_push($rules,trim($line));
            }
        }

        $this->rules = $rules;

    }

    // Table level
    public function isTableInSqlIgnore($table) {

        foreach ($this->rules as $rule) {

            if ($this->isRuleForTable($table,$rule)) {
                return true;
            }

        }

        return false;
    }

    // Data level
    public function validDataNotInSqlIgnore($table, $data, $newOrRemoved = "new") {

        $ignoreData = array();
        $validData = array();

        foreach ($data as $row) {

            $ignoreRow = false;

            foreach ($this->rules as $rule) {

                // Is it a data related sql ignore rule? If not, let's continue with the next rule
                if (strpos($rule,'[')==FALSE) continue;

                // Let's parse the sqlignore expression
                $expression = $this->_parseSqlIgnoreExpression($rule);

                // Ok, we have a sql ignore rule related to data
                $isDataRelatedToTable = $this->isRuleForTable($table,$expression['table']);

                if ($isDataRelatedToTable) {

                    if ($row[$expression['expression']['column']]==$expression['expression']['value']) {
                        $ignoreRow = true;
                        array_push($ignoreData, $row);
                        continue;
                    }
                }
            }

            // If not ignored, add it as valid
            if (!$ignoreRow) {
                array_push($validData, $row);
            }

        }

        // Logging
        // TODO: so bad, we have to change the way SqlIgnore and App models are related
        // We can't use AppLogger here because of that
        if (!empty($ignoreData)) {
            echo "[" . count($ignoreData) . " " . $newOrRemoved . " rows ignored for " . $table . "]" . PHP_EOL;
        }

        return $validData;
    }

    private function _parseSqlIgnoreExpression($rule) {

        $auxRule = explode("[",$rule);
        $expression['table'] = $auxRule[0];
        $auxExpression = rtrim($auxRule[1],"]"); // String
        $auxExpression = explode("=", $auxExpression);
        $expression['expression']['column'] = $auxExpression[0];
        $expression['expression']['value'] = $auxExpression[1];
        return $expression;

    }

    private function isRuleForTable($table,$rule) {

        //  simple tables
        if ($table==$rule) {
            return true;
        }

        // tables with *, we won't consider [col1=va1] data ignore yet
        if (strpos($rule,"*")!==FALSE && strpos($rule,"[")==FALSE && preg_match( '/^' . $rule . '/' , $table)) {
            return true;
        }

        return false;

    }

}