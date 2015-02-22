<?php
require_once 'Lib/ScriptFunctions.php';

/** Modify folders structure **/
// Composer's standard structure - vendor/[vendor]/[project] - is not the best for us
// Bring all the project to a parent .sql directory

// Create .sql directory on root folder
exec("mkdir .sql");

// Move everything from our project to that folder
exec("mv vendor/ojoven/sqldiffgenerator/* .sql/");

// Remove empty or not used folder's
$numVendors = count(ScriptFunctions::getDirectories("vendor/")) - 1;
// If there are more dependencies, we remove just ours
if ($numVendors>1) {
    exec("rm -r vendor/ojoven");
} else {
    // If just ours, we remove all vendor directory - don't want dead code / files out there
    exec("rm -r vendor");
}

/** Messages **/
ScriptFunctions::underlineMessage("SQL Diff Generator successfully Installed!",'-');
ScriptFunctions::showMessageLine("This is a control version system for your database.");
ScriptFunctions::showMessageLine("You can fork this project on http://github.com/ojoven/sqldiffgenerator");
ScriptFunctions::highlightMessage("Remember to set your configuration on .sql/config.ini or to run .sql/scripts/configurator","=");
ScriptFunctions::showMessageLine("Have a good time!");