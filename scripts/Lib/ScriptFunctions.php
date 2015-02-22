<?php

/** Functions for console scripts: initializer, configurator... **/

class ScriptFunctions {

    public static function showMessageAndReturnInput($message) {
        echo $message. " ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
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
        writeBreakLine();
        showMessageLine(str_repeat($decorator,$multiplier + 6));
        showMessageLine(str_repeat($decorator,2) . " " . $message . " " . str_repeat($decorator,2));
        showMessageLine(str_repeat($decorator,$multiplier + 6));
        writeBreakLine();
    }

    public static function underlineMessage($message,$decorator) {
        $multiplier = ceil(strlen($message)/strlen($decorator));
        writeBreakLine();
        showMessageLine($message);
        showMessageLine(str_repeat($decorator,$multiplier));
        writeBreakLine();
    }

    public static function title($message) {
        highlightMessage($message,"=");
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


}