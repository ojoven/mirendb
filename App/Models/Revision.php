<?php

class Revision {

    public function createFirstRevision($app) {

        $revisionNumber = "1";
        $revisionFolder = Filesystem::createDirectory(ROOT_PATH . $app->config['control_version']['path_to_revisions'] . $revisionNumber);
        if (!$revisionFolder) throw new Exception("Folder couldn't be created");
        $finalPathDump = $revisionFolder . "/" . $app->revisionFilename;
        Database::dumpDatabase($app->target, $finalPathDump, $app->config, 'target');

    }

    public function initializeCurrentRevision($app) {
        $revisionFolder = Filesystem::createDirectory(ROOT_PATH . $app->config['control_version']['path_to_revisions'] . $app->currentRevision);
        if (!$revisionFolder) throw new Exception("Folder couldn't be created");
        $app->result = Result::initResult($revisionFolder . "/" . $app->revisionFilename);
    }

    public function generateDatabaseWithRevisions($app,$revisions) {

        $finalQuery = "";
        foreach ($revisions as $revision) {

            $filename = ROOT_PATH . $app->config['control_version']['path_to_revisions'] . $revision . "/" . $app->revisionFilename;
            $finalQuery .= file_get_contents($filename);
            $finalQuery .= "\n\n";

        }

        Database::createDatabaseFromQuery($app->config['origin']['database'], $finalQuery, $app->config,'origin');
    }

    public function deleteCurrentRevision($app) {
        $path = ROOT_PATH . $app->config['control_version']['path_to_revisions'] . $app->currentRevision;
        Filesystem::deleteDirectory($path);
    }


}