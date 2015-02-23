<?php

class StandardControlVersionBehaviour implements Behaviour {

    /** Steps needed before creating a new revision **/
    public function initialize($app) {

        // First, we validate if the config.ini file is correct
        $this->_validateConfigurationOptions($app);

        // Connect to target database
        $app->target = Database::getTarget($app->config);

        // Let's work with revisions
        $revisionModel = new Revision();

        // Let's retrieve the current revisions
        $revisions = Filesystem::getDirectoriesCreateIfNotExist(ROOT_PATH . $app->config['control_version']['path_to_revisions']);

        // If no revisions
        if (empty($revisions)) {

            // No diff comparator, we just dump a whole first revision
            $revisionModel->createFirstRevision($app);
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

            // Get tables Target
            $app->targetTables = Database::getTablesDatabase($app->target);

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

        // Simple output
        echo "Script run successfully!" . PHP_EOL;

    }

    /** Let's check if the behaviour's configuration options are correct **/
    private function _validateConfigurationOptions($app) {

        // TODO: Validate the config file
        // If not valid, throw new Exception();
        // Validate hooks, are they really added?

    }


}