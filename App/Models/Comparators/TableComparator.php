<?php

class TableComparator {

    /** New Tables Target **/
    public function getNewTablesTarget($originTables,$targetTables) {

        $newTablesTarget = array();
        foreach ($targetTables as $targetTable) {

            // If not in origin tables, let's add it
            if (!in_array($targetTable,$originTables)) {
                array_push($newTablesTarget,$targetTable);
            }

        }

        return $newTablesTarget;
    }

    /** Removed Tables Target **/
    public function getRemovedTablesTarget($originTables,$targetTables) {

        $removedTablesTarget = array();
        foreach ($originTables as $originTable) {

            // If not in origin tables, let's add it
            if (!in_array($originTable,$targetTables)) {
                array_push($removedTablesTarget,$originTable);
            }

        }

        return $removedTablesTarget;
    }

}