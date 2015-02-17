<?php

class FieldsQueryGenerator extends QueryGenerator {

    /**  New Fields ̣**/
    public static function generateQueryNewFields($newFieldsTarget,$table) {

        $data = "";
        $data = self::_addComment($data, "Add new fields", true);
        foreach ($newFieldsTarget as $newField) {

            /**
            Some Examples:
            ALTER TABLE `table_to_keep` ADD `field_to_add` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT 'predefined' COMMENT 'Comment', ADD UNIQUE (`field_to_add`);
            ALTER TABLE `table_to_keep` ADD `field_to_add_2` INT NOT NULL AFTER `id` ;
            ALTER TABLE `table_to_keep` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
            ALTER TABLE `table_to_keep` ADD `field_to_add_3` VARCHAR( 16 ) NOT NULL DEFAULT 'predefined' FIRST ;
             */

            $data = self::_addComment($data, "Field `" . $newField['Field'] . "` from table `" . $table . "`");

            // Query
            $data .= "ALTER TABLE `" . $table . "` ADD `" . $newField['Field'] . "`";

            $data = self::addTypeToQuery($data,$newField);
            $data = self::addNullToQuery($data,$newField);
            $data = self::addDefaultToQuery($data,$newField);
            $data = self::addExtraToQuery($data,$newField);
            $data = self::addKeyToQuery($data,$newField);
            // TODO: Comments, Character set

            $data = self::finishQuery($data,';');
        }

        return $data;
    }

    // Removed Fields
    public static function generateQueryRemovedFields($removedFieldsTarget,$table) {

        $data = "";
        $data = self::_addComment($data, "Remove deleted fields", true);
        foreach ($removedFieldsTarget as $removedField) {
            $data = self::_addComment($data, "Field `" . $removedField['Field'] . "` from table `" . $table . "`");
            $data .= "ALTER TABLE " . $table . " DROP COLUMN " . $removedField['Field'];
            $data = self::finishQuery($data,';');
        }

        return $data;
    }

    // Options New Field Query
    private static function addTypeToQuery($data,$field) {
        return $data . " " . $field['Type'];
    }

    private static function addNullToQuery($data,$field) {
        if ($field['Null']=="NO") {
            return $data . " NOT NULL";
        } else {
            return $data . " NULL";
        }
    }

    private static function addDefaultToQuery($data,$field) {
        if ($field['Default']!="") {
            return $data . " DEFAULT '" . $field['Default'] . "'";
        }
        return $data;
    }

    private static function addKeyToQuery($data,$field) {
        if ($field['Key']=="PRI") { // PRIMARY
            return $data . " PRIMARY KEY";
        } elseif ($field['Key']!="UNI") { // UNIQUE
            return $data . ", ADD UNIQUE (`" . $field['Field'] . "`)";
        } elseif ($field['Key']!="MUL") { // INDEX
            return $data . ", ADD INDEX (`" . $field['Field'] . "`)";
        }
        // TODO: Support for FULLTEXT index - MyISAM tables
        return $data;
    }

    private static function addExtraToQuery($data,$field) {
        if ($field['Extra']=="auto_increment") {
            return $data . " AUTO_INCREMENT";
        }
        return $data;
    }


}