<?php
require_once 'Lib/ScriptFunctions.php';

/** Messages **/
ScriptFunctions::underlineMessage("Welcome to the Sql Diff Generator configuration assistant",'-');
ScriptFunctions::showMessageLine("Remember you can set your configuration manually on .sql/App/config.ini");

/** STEPS **/
ScriptFunctions::title("Database configuration");
ScriptFunctions::underlineMessage("Database to be set under version control");
ScriptFunctions::getUserInputValueFor("Host","localhost");
ScriptFunctions::getUserInputValueFor("Port","3306");
ScriptFunctions::getUserInputValueFor("User","root");
ScriptFunctions::getUserInputValueFor("Password","");