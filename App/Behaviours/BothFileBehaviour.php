<?php

class BothFileBehaviour implements Behaviour {

    public function initialize($app) {

        // Not sure if we should get rid of this one, and just use the ControlVersion behaviours
        // For the moment, this is not working
        throw new Exception("This behaviour is not working for the moment");

        // Let's retrieve the configuration options
        $app->config = parse_ini_file(realpath('App/Configs/' . get_class($this) . '/config.ini'), true);

        // First, we should create the databases and import the data
        Database::createDatabaseFromFile($app->config['origin']['database'],$app->config['files']['origin'],$app->config,'origin');
        Database::createDatabaseFromFile($app->config['target']['database'],$app->config['files']['target'],$app->config,'target');

        // Connect to origin database
        $app->origin = Database::getOrigin($app->config);

        // Connect to target database
        $app->target = Database::getTarget($app->config);

        // Create empty file
        $app->result = Result::initResult($app->config['files']['result']);

        // Get tables Origin
        $app->originTables = Database::getTablesDatabase($app->origin);

        // Get tables Target
        $app->targetTables = Database::getTablesDatabase($app->target);

    }

    public function finalize($app) {

        // To finish, we remove the databases
        Database::deleteDatabase($app->config['origin']['database'],$app->config,'origin');
        Database::deleteDatabase($app->config['target']['database'],$app->config,'target');

        // Simple output
        echo "Script run successfully!" . PHP_EOL;

    }


}