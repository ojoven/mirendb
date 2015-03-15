<?php

class TablesQueryGenerator extends QueryGenerator {

    /**  New Tables ̣**/
    public static function generateQueryNewTables($newTablesTarget,$target,$config) {

        $data = "";
        $data = self::_addComment($data, "Create new tables", true);
        foreach ($newTablesTarget as $newTable) {

            $data = self::_addComment($data, "Table `" . $newTable . "`");

            // We'll use mysqldump via exec
            $command = $config['bins']['mysqldump'] . " -u " . $config['target']['user'] . " -h " .$config['target']['host'];
            if ($config['target']['password']!="") $command .= " -p " . $config['target']['password'];
            $command .= " --skip-comments --compact " . $target->getDbName() . " " .$newTable . " | grep -v '^\/\*![0-9]\{5\}.*\/;$'"; // remove comments
            $data .= shell_exec($command);
            $data = self::finishQuery($data,'');

        }

        return $data;
    }

    /**  Removed Tables **/
    public static function generateQueryRemovedTables($removedTablesTarget) {

        $data = "";
        $data = self::_addComment($data, "Remove old tables", true);

        foreach ($removedTablesTarget as $removedTable) {

            $data = self::_addComment($data, "Table `" . $removedTable . "`");
            $data .= "DROP TABLE " . $removedTable;
            $data = self::finishQuery($data,';');

        }

        return $data;

    }

}