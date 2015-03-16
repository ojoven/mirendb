<?php

class Database {

    private static $origin;
    private static $target;

    /**  Get Origin **/
    public static function getOrigin($config) {
        if (self::$origin==null) {
            self::$origin = new MysqliDb($config['origin']['host'],$config['origin']['user'],$config['origin']['password'],$config['origin']['database'],$config['origin']['port']);
        }
        return self::$origin;
    }

    /** Get Target **/
    public static function getTarget($config) {
        if (self::$target==null) {
            self::$target = new MysqliDb($config['target']['host'],$config['target']['user'],$config['target']['password'],$config['target']['database'],$config['target']['port']);
        }
        return self::$target;
    }

    /** Get Staging **/
    public static function getStaging($config) {
        if (self::$target==null) {
            self::$target = new MysqliDb($config['staging']['host'],$config['staging']['user'],$config['staging']['password'],$config['staging']['database'],$config['staging']['port']);
        }
        return self::$target;
    }

    /**  Get Tables Database **/
    public static function getTablesDatabase($database) {
        $tables = $database->rawQuery("select table_name from information_schema.tables where table_schema='" . $database->getDbName() . "'");
        $tables = Functions::getSubarrayBasedOnIndex($tables,'table_name');
        return $tables;
    }

    /**  Dump Database **/
    public static function dumpDatabase($database,$filename,$config,$type) {
        $command = $config['bins']['mysqldump'] . " -u " . $config[$type]['user'] . " -h " .$config[$type]['host'];
        if ($config[$type]['password']!="") $command .= " -p" . $config[$type]['password'];
        $command .= " --skip-comments --compact " . $database->getDbName() . " | grep -v '^\/\*![0-9]\{5\}.*\/;$'"; // remove comments
        $data = shell_exec($command);
        // First, we empty the file
        file_put_contents($filename,'');
        file_put_contents($filename,$data,FILE_APPEND);
    }

    /** Create Database from file **/
    public static function createDatabaseFromFile($name,$file,$config,$type) {

        $query = file_get_contents($file);
        self::createDatabaseFromQuery($name,$query,$config,$type);

    }

    public static function createDatabaseFromQuery($name,$query,$config,$type) {

        // First, let's delete the database if it exists
        self::deleteDatabase($name,$config,$type);

        $queryCreateDatabase = "CREATE DATABASE " . $name;
        $dbConnection = new MysqliDb($config[$type]['host'],$config[$type]['user'],$config[$type]['password']);
        $dbConnection->rawQuery($queryCreateDatabase);

        // We put the query into a tmp file
        $tmpPath = ROOT_PATH . "App/tmp/tmp.sql";
        file_put_contents($tmpPath,$query);

        // Now we import the database via mysql
        $command = $config['bins']['mysql'] . " -u " . $config[$type]['user'] . " -h " .$config[$type]['host'];
        if ($config[$type]['password']!="") $command .= " -p" . $config[$type]['password'];
        $command .= " " . $name . " < " . $tmpPath;
        $data = shell_exec($command);
        unlink($tmpPath);

    }

    /** Delete Database **/
    public static function deleteDatabase($name,$config,$type) {

        // First we create the database
        $query = "DROP DATABASE IF EXISTS " . $name;
        $dbConnection = new MysqliDb($config[$type]['host'],$config[$type]['user'],$config[$type]['password']);
        $dbConnection->rawQuery($query);

    }

}