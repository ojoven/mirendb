<?php

class Result {

    private static $filename;

    private function __construct($filename) {
        file_put_contents($filename,'');
    }

    public static function initResult($filename) {
        if (self::$filename==null) {
            self::$filename = $filename;
            new Result(self::$filename);
        }
        return self::$filename;
    }

    public static function addToResult($data) {
        file_put_contents(self::$filename, $data, FILE_APPEND);
    }

    public static function getResult() {
        return file_get_contents(self::$filename);
    }

}