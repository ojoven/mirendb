<?php
require_once 'Lib/ScriptFunctions.php';

/** Modify folders structure **/
exec("mv .sql/ojoven/sqldiffgenerator/* .sql/");
exec("rm -r .sql/ojoven");
exec("rm -r .sql/composer");

/** Messages **/
ScriptFunctions::underlineMessage("SQL Diff Generator successfully Installed!",'-');
ScriptFunctions::showMessageLine("This is a control version system for your database.");
ScriptFunctions::showMessageLine("You can fork this project on http://github.com/ojoven/sqldiffgenerator");
ScriptFunctions::highlightMessage("Remember to set your configuration on .sql/config.ini or to run .sql/scripts/configurator","=");
ScriptFunctions::showMessageLine("Have a good time!");