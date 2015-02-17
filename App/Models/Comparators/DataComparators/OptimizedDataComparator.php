<?php

class OptimizedDataComparator extends DataComparator {

    /** New Tables Target **/
    public function getNewDataTarget($dataOrigin,$dataTarget) {

        // First comparator, total data, much faster
        if ($dataOrigin==$dataTarget) {
            return array();
        }

        // Not totally equal, let's compare row by row
        $newData = $this->addNewDataToArray($dataOrigin,$dataTarget);
        return $newData;
    }

    /** Removed Tables Target **/
    public function getRemovedDataTarget($dataOrigin,$dataTarget) {

        // First comparator, total data, much faster
        if ($dataOrigin == $dataTarget) {
            return array();
        }

        // Not totally equal, let's compare row by row

        $removedData = $this->addNewDataToArray($dataTarget,$dataOrigin);
        return $removedData;
    }

}