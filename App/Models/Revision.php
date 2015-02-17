<?php

class Revision {

    public function createFirstRevision($app) {

        $revisionNumber = "1";
        $revisionFolder = Filesystem::createDirectory($app->config['files']['pathToRevisions'] . $revisionNumber);
        if (!$revisionFolder) throw new Exception("Folder couln't be created");
        $finalPathDump = $revisionFolder . "/" . $app->revisionFilename;
        Database::dumpDatabase($app->target, $finalPathDump, $app->config, 'target');

    }

    public function initializeCurrentRevision($app) {
        $revisionFolder = Filesystem::createDirectory($app->config['files']['pathToRevisions'] . $app->currentRevision);
        if (!$revisionFolder) throw new Exception("Folder couln't be created");
        $app->result = Result::initResult($revisionFolder . "/" . $app->revisionFilename);
    }

    public function generateDatabaseWithRevisions($app,$revisions) {

        $finalQuery = "";
        foreach ($revisions as $revision) {

            $filename = ROOT_PATH . $app->config['files']['pathToRevisions'] . $revision . "/" . $app->revisionFilename;
            $finalQuery .= file_get_contents($filename);
            $finalQuery .= "\n\n";

        }

        Database::createDatabaseFromQuery($app->config['origin']['database'], $finalQuery, $app->config,'origin');
    }

    public function deleteCurrentRevision($app) {
        $path = ROOT_PATH . $app->config['files']['pathToRevisions'] . $app->currentRevision;
        Filesystem::deleteDirectory($path);
    }


}