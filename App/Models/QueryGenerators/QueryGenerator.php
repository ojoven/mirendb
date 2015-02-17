<?php

class QueryGenerator {

    protected static function _addComment($data,$message,$heading = false) {
        if ($heading) {
            $data .= "/* " . $message . " */" . PHP_EOL;
        } else {
            $data .= "-- " . $message . PHP_EOL;
        }
        return $data;
    }

    protected static function finishQuery($data, $finisher = ';') {
        return $data . $finisher . PHP_EOL . PHP_EOL;
    }

}