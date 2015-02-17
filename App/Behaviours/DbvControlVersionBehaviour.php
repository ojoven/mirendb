<?php

/**
Dbv is a database versioning control tool: http://dbv.vizuina.com
SqlDiffGenerator can help on creating the revisions automatically
**/

class DbvControlVersionBehaviour implements Behaviour {

    public function initialize($app) {

        $revisionModel = new Revision();

        // Let's retrieve the configuration options
        $app->config = parse_ini_file(realpath('App/Configs/' . get_class($this) . '/config.ini'), true);

        // Let's retrieve the current revisions
        $revisions = Filesystem::getDirectories($app->config['files']['pathToRevisions']);

        // Connect to target database
        $app->target = Database::getTarget($app->config);

        if (empty($revisions)) {

            // No diff comparator, we just dump a whole first revision
            $revisionModel->createFirstRevision($app);
            $app->skip = true;
            $app->firstRevision = true;
            return;
        } else {
            $app->firstRevision = false;
        }

        // If revisions, we should recreate the previous state of the database by
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


}