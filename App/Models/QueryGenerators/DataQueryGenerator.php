<?php

class DataQueryGenerator extends QueryGenerator {

    /**  New Fields ̣**/
    public static function generateQueryNewData($newDataTarget,$table,$target) {

        $data = "";
        $data = self::_addComment($data, "New data for table `" . $table . "`");
        $data .= "INSERT INTO `" . $table . "`";
        $data .= self::getFieldsParsed($table,$target);
        $data .= " VALUES ";

        foreach ($newDataTarget as $index=>$newData) {

            $newData = Functions::wrapElementsArray($newData,"'","'");
            $singleData = "(";
            $singleData .= implode(",",$newData);
            $singleData .= ")";
            if ($index<sizeof($newDataTarget)-1) $singleData .= ",";

            $data .= $singleData;

        }

        $data = self::finishQuery($data,';');

        return $data;
    }

    // Removed Fields
    public static function generateQueryRemovedData($removedDataTarget,$table) {

        $data = "";
        $data = self::_addComment($data, "Remove deleted data", true);
        foreach ($removedDataTarget as $row) {
            $data = "DELETE FROM " . $table . " WHERE 1";
            foreach ($row as $index=>$value) {
                $data .= " AND " . Functions::wrapElement($index,"`","`") . " = " . Functions::wrapElement($value,"'","'");
            }
            $data = self::finishQuery($data,';');
        }

        return $data;
    }

    // Options New Field Query
    private static function getFieldsParsed($table,$target) {

        $fieldComparator = new FieldComparator();
        $fields = $fieldComparator->getFields($table,$target);
        $fields = Functions::getSubarrayBasedOnIndex($fields,'Field');
        $fields = Functions::wrapElementsArray($fields,"`","`");
        return "(" . implode(",",$fields) . ")";

    }

}