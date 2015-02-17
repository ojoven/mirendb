<?php

abstract class DataComparator {

    /** Get Data **/
    public function getData($table,$database) {
        $data = $database->get($table);
        return $data;
    }

    /** Is New Data **/
    protected function addNewDataToArray($dataFirst,$dataLast) {

        $newData = array();

        foreach ($dataLast as $elementLast) {
            $elementFound = false;
            foreach ($dataFirst as $elementFirst) {
                if ($elementFirst==$elementLast) {
                    $elementFound = true;
                    break;
                }
            }
            if (!$elementFound) {
                array_push($newData,$elementLast);
            }
        }

        return $newData;

    }

}