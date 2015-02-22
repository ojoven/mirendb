<?php

/** Functions for console scripts: initializer, configurator... **/

class ScriptFunctions {

    /** Messages **/
    public static function showMessageAndReturnInput($message) {
        echo $message. " ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        return $line;
    }

    public static function getUserInputValueFor($message,$default) {
        echo $message. " [" . $default . "]: ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if (trim($line)=="") $line = $default;
        return $line;
    }

    public static function showMessageLine($message) {
        echo $message . PHP_EOL;
    }

    public static function writeBreakLine() {
        echo PHP_EOL;
    }

    public static function highlightMessage($message,$decorator) {
        $multiplier = ceil(strlen($message)/strlen($decorator));
        self::writeBreakLine();
        self::showMessageLine(str_repeat($decorator,$multiplier + 6));
        self::showMessageLine(str_repeat($decorator,2) . " " . $message . " " . str_repeat($decorator,2));
        self::showMessageLine(str_repeat($decorator,$multiplier + 6));
        self::writeBreakLine();
    }

    public static function underlineMessage($message,$decorator = '-') {
        $multiplier = ceil(strlen($message)/strlen($decorator));
        self::writeBreakLine();
        self::showMessageLine($message);
        self::showMessageLine(str_repeat($decorator,$multiplier));
        self::writeBreakLine();
    }

    public static function title($message) {
        self::highlightMessage($message,"=");
    }

    // Parse user input
    public static function trueOrFalseDefaultTrue($input) {
        $input = strtolower(trim($input));
        return ($input=="no" || $input=="n") ? false : true;
    }

    public static function trueOrFalseDefaultFalse($input) {
        $input = strtolower(trim($input));
        return ($input=="yes" || $input=="y") ? true : false;
    }


    /** Filesystem **/
    public static function getDirectories($path) {

        $results = scandir($path);
        $directories = array();

        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            if (is_dir($path . '/' . $result)) {
                array_push($directories,$result);
            }
        }

        return $directories;
    }

}