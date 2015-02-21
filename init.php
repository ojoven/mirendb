<?php

/** Initializer script **/
function showMessageAndReturnInput($message) {
    echo $message. " ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    return $line;
}

function showMessageLine($message) {
    echo $message . PHP_EOL;
}

function writeBreakLine() {
    echo PHP_EOL;
}

function highlightMessage($message,$decorator) {
    $multiplier = ceil(strlen($message)/strlen($decorator));
    writeBreakLine();
    showMessageLine(str_repeat($decorator,$multiplier + 6));
    showMessageLine(str_repeat($decorator,2) . " " . $message . " " . str_repeat($decorator,2));
    showMessageLine(str_repeat($decorator,$multiplier + 6));
    writeBreakLine();
}

function underlineMessage($message,$decorator) {
    $multiplier = ceil(strlen($message)/strlen($decorator));
    writeBreakLine();
    showMessageLine($message);
    showMessageLine(str_repeat($decorator,$multiplier));
    writeBreakLine();
}

function title($message) {
    highlightMessage($message,"=");
}

// Parse user input
function trueOrFalseDefaultTrue($input) {
    $input = strtolower(trim($input));
    return ($input=="no" || $input=="n") ? false : true;
}

function trueOrFalseDefaultFalse($input) {
    $input = strtolower(trim($input));
    return ($input=="yes" || $input=="y") ? true : false;
}

underlineMessage("SQL Diff Generator successfully Installed!",'-');
showMessageLine("This is a control version system for your database, hope you enjoy it.");
$messageStep0 = "Do you want me to help you with the initial configurations? [Y/n]";
$configure = trueOrFalseDefaultTrue(showMessageAndReturnInput($messageStep0));

// If not configure, bye bye!
if (!$configure) {
    $messageByeBye = "OK, remember to configure manually your database access credentials at .sql/config.ini";
    highlightMessage($messageByeBye,"=");
    exit;
}

title("Basic configuration");