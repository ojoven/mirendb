<?php

class App {

    use AppExporter, AppImporter, AppLogger;

    // connections
    public $origin;
    public $target;
    public $staging;

    // tables
    public $originTables;
    public $targetTables;

    // config
    public $config;
    public $skip = false;

    // file result
    public $result;
    public $currentRevision;
    public $revisionFilename = "revision.sql";

    // import environment
    public $importEnv;

    // SQL Ignore Model
    public $sqlignore;

    // dynamic variables, based on first comparators
    public $newTablesTarget = array();
    public $removedTablesTarget = array();
    public $tablesWithNewFields = array();
    public $tablesWithRemovedFields = array();

    public function __construct() {

        // Let's load the configuration file
        $configFile = ROOT_PATH . 'App/config.ini';
        if (!file_exists($configFile)) {
            throw new Exception("You must set up first the .sql/App/Config.ini.default and rename it to config.ini");
        }

        $this->config = parse_ini_file($configFile, true);
    }


}