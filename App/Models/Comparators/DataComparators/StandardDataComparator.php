<?php

class StandardDataComparator extends DataComparator {

    /** New Tables Target **/
    public function getNewDataTarget($dataOrigin,$dataTarget) {

        $newData = $this->addNewDataToArray($dataOrigin,$dataTarget);
        return $newData;
    }

    /** Removed Tables Target **/
    public function getRemovedDataTarget($dataOrigin,$dataTarget) {

        $newData = $this->addNewDataToArray($dataTarget,$dataOrigin);
        return $newData;
    }

}