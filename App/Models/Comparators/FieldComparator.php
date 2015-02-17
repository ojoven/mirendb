<?php

class FieldComparator {

    /** New Tables Target **/
    public function getNewFieldsTarget($table,$origin,$target) {

        $fieldsOrigin = $this->getFields($table,$origin);
        $fieldsTarget = $this->getFields($table,$target);

        $newFields = Functions::returnNewElementsInLastArray($fieldsTarget, $fieldsOrigin, 'Field');
        return $newFields;
    }

    /** Removed Tables Target **/
    public function getRemovedFieldsTarget($table,$origin,$target) {

        $fieldsOrigin = $this->getFields($table,$origin);
        $fieldsTarget = $this->getFields($table,$target);

        $newFields = Functions::returnNewElementsInLastArray($fieldsOrigin, $fieldsTarget, 'Field');
        return $newFields;

    }

    public function getFields($table,$database) {
        $fieldsQuery = "SHOW COLUMNS FROM `" . $table . "` IN `" . $database->getDbName() . "`";
        $fields = $database->rawQuery($fieldsQuery);

        return $fields;

        /**
        print_r($fields); -->
        [Field] => id
        [Type] => int(11)
        [Null] => NO
        [Key] => PRI
        [Default] =>
        [Extra] => auto_increment
         */
    }

}