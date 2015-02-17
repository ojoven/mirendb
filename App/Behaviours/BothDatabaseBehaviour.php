<?php

class BothDatabaseBehaviour implements Behaviour {

    public function initialize($app) {

        // Let's retrieve the configuration options
        $app->config = parse_ini_file(realpath('App/Configs/' . get_class($this) . '/config.ini'), true);

        // Connect to origin database
        $app->origin = Database::getOrigin($app->config);

        // Connect to target database
        $app->target = Database::getTarget($app->config);

        // Create empty file
        $app->result = Result::initResult($app->config['result']['filename']);

        // Get tables Origin
        $app->originTables = Database::getTablesDatabase($app->origin);

        // Get tables Target
        $app->targetTables = Database::getTablesDatabase($app->target);

        // Just for testing / documentation purposes, we'll dump the origin and target databases
        Database::dumpDatabase($app->origin, $app->config['result']['origin'], $app->config, 'origin');
        Database::dumpDatabase($app->target, $app->config['result']['target'], $app->config, 'target');

        return $app;

    }

    public function finalize($app) {

        // Simple output
        echo "Script run successfully!" . PHP_EOL;

    }


}