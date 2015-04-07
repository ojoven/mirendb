<?php

class StandardControlVersionBehaviour implements Behaviour {

    /** EXPORT CAPACITIES **/
    /** Steps needed before creating a new revision **/
    public function initialize($app) {

        // First, we validate if the config.ini file is correct
        $this->_validateConfigurationOptions($app);

        // Let's initialize the sqlignore file, too
        $app->sqlignore = new SqlIgnore();

        // Connect to target database
        $app->target = Database::getTarget($app->config);

        // Get tables Target
        $app->targetTables = Database::getTablesDatabase($app->target);

        // Let's work with revisions
        $revisionModel = new Revision();

        // Let's retrieve the current revisions
        $revisions = Filesystem::getDirectoriesCreateIfNotExist(ROOT_PATH . $app->config['control_version']['path_to_revisions']);

        // If no revisions and database already has tables
        if (empty($revisions) && count($app->targetTables)>0) {

            // No diff comparator, we just dump a whole first revision
            $revisionModel->createFirstRevision($app);
            $app->log('[revision 1] database dumped');
            $app->skip = true;
            $app->firstRevision = true;
            return;

        } else {

            // There are previous revisions
            $app->firstRevision = false;

            // We must first recreate the previous state of the database by
            // calling all the queries in the revisions
            $revisionModel->generateDatabaseWithRevisions($app,$revisions);

            // Connect to origin database
            $app->origin = Database::getOrigin($app->config);

            // Create new revision
            $lastRevision = array_pop($revisions);
            $app->currentRevision = $lastRevision + 1;
            $revisionModel->initializeCurrentRevision($app);

            // Get tables Origin
            $app->originTables = Database::getTablesDatabase($app->origin);

        }

    }

    /** Steps after creating a new revision **/
    public function finalize($app) {

        // Let's check if there are changes, if not, we'll remove the revision
        if (!$app->firstRevision) {
            $result = Result::getResult();
            if (trim($result)=="") {
                $revisionModel = new Revision();
                $revisionModel->deleteCurrentRevision($app);
            }
        }

        Database::deleteDatabase($app->config['origin']['database'],$app->config,'origin');

    }

    /** Let's check if the behaviour's configuration options are correct **/
    private function _validateConfigurationOptions($app) {

        // TODO: Validate the config file
        // If not valid, throw new Exception();
        // Validate hooks, are they really added?

    }


    /** IMPORT CAPACITIES **/
    public function import($app) {

        // First, we validate if the config.ini file is correct
        $this->_validateConfigurationOptions($app);

        // Let's make a backup of the current database before importing revisions
        if ($app->importEnv=="local") {
            $app->target = Database::getTarget($app->config);
            $databaseName = $app->config['target']['database'];
            $pathToBackup = ROOT_PATH . "App/Backup/" . $databaseName . ".sql";
            Database::dumpDatabase($app->target, $pathToBackup, $app->config, 'target');
        } else { // staging
            $app->staging = Database::getStaging($app->config);
            $databaseName = $app->config['staging']['database'];
            $pathToBackup = ROOT_PATH . "App/Backup/" . $databaseName . ".sql";
            Database::dumpDatabase($app->staging, $pathToBackup, $app->config, 'staging');
        }

        // Let's retrieve the current revisions
        $revisions = Filesystem::getDirectoriesCreateIfNotExist(ROOT_PATH . $app->config['control_version']['path_to_revisions']);

        // And generate the query from it
        $revisionModel = new Revision();
        $query = $revisionModel->generateQueryWithRevisions($app,$revisions);

        // Now we run that query
        $target = ($app->importEnv=="local") ? "target" : "staging";
        Database::createDatabaseFromQuery($databaseName, $query, $app->config, $target);

    }


}