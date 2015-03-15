<?php

trait AppExporter {

    public function export() {

        /** Initial connections / configurations, they will depend on the behaviour **/
        $behaviour = BehaviourFactory::getBehaviour($this->config['global']['behaviour']);
        $behaviour->initialize($this);

        // Dynamically we may need to skip from comparing data
        // For example, the first revision we create, it will be just a sql dump
        if (!$this->skip) {

            /** Generate the queries for table level differences **/
            $this->sqlDiffTables();
            // TODO: Still missing the queries for when we change a table's features like character set, myIsam to InnoDb, etc.

            /** Generate the queries for field schema level differences **/
            $this->sqlDiffFields();
            // TODO: Still missing the queries for when we change a field's features: from varchar to text, null to is not null

            /** Generate the queries for data level differences **/
            if ($this->config['global']['data']) {
                $dataComparator = new OptimizedDataComparator();
                $this->sqlDiffData($dataComparator);
            }

        }

        /** Finalize **/
        $behaviour->finalize($this);

    }

    /** Tables **/
    public function sqlDiffTables() {

        $tableComparator = new TableComparator();

        // Removed Tables
        $this->removedTablesTarget = $tableComparator->getRemovedTablesTarget($this->originTables,$this->targetTables);
        if ($this->removedTablesTarget) {
            $data = TablesQueryGenerator::generateQueryRemovedTables($this->removedTablesTarget);
            Result::addToResult($data);
            $this->log(count($this->removedTablesTarget) . " table(s) removed: " . implode(', ',$this->removedTablesTarget));
        }

        // New Tables
        $this->newTablesTarget = $tableComparator->getNewTablesTarget($this->originTables,$this->targetTables);
        if ($this->newTablesTarget) {
            $data = TablesQueryGenerator::generateQueryNewTables($this->newTablesTarget,$this->target,$this->config);
            Result::addToResult($data);
            $this->log(count($this->newTablesTarget) . " table(s) added: " . implode(', ',$this->newTablesTarget));
        }

    }

    /** Fields **/
    public function sqlDiffFields() {

        $fieldComparator = new FieldComparator();

        foreach ($this->targetTables as $table) {

            // If it's not a new table, but an existing one
            if (in_array($table,$this->originTables)) {

                // Removed Fields
                $removedFieldsTableTarget = $fieldComparator->getRemovedFieldsTarget($table,$this->origin,$this->target);
                if ($removedFieldsTableTarget) {
                    $data = FieldsQueryGenerator::generateQueryRemovedFields($removedFieldsTableTarget,$table);
                    Result::addToResult($data);
                    // We add, too, the table to an array for future data diff comparison
                    array_push($this->tablesWithRemovedFields,$table);
                    $this->log(count($removedFieldsTableTarget) . " column(s) removed from " . $table . ": " . implode(', ',$removedFieldsTableTarget));
                }

                // New Fields
                $newFieldsTableTarget = $fieldComparator->getNewFieldsTarget($table,$this->origin,$this->target);
                if ($newFieldsTableTarget) {
                    $data = FieldsQueryGenerator::generateQueryNewFields($newFieldsTableTarget,$table);
                    Result::addToResult($data);
                    // We add, too, the table to an array for future data diff comparison
                    array_push($this->tablesWithNewFields,$table);
                    $this->log(count($removedFieldsTableTarget) . " column(s) added to " . $table . ": " . implode(', ',$newFieldsTableTarget));
                }

            }

        }

    }

    /** Data **/
    public function sqlDiffData($dataComparator) {

        foreach ($this->targetTables as $table) {

            // If it's not a new table, but an existing one BUT no new / removed fields
            if (in_array($table,$this->originTables) && (!in_array($table,$this->tablesWithNewFields)) && (!in_array($table,$this->tablesWithRemovedFields))) {

                // First, we retrieve the data for the table
                $dataOrigin = $dataComparator->getData($table,$this->origin);
                $dataTarget = $dataComparator->getData($table,$this->target);

                // Removed Data
                $removedDataTableTarget = $dataComparator->getRemovedDataTarget($dataOrigin,$dataTarget);
                if ($removedDataTableTarget) {
                    $data = DataQueryGenerator::generateQueryRemovedData($removedDataTableTarget,$table);
                    Result::addToResult($data);
                    $this->log(count($removedDataTableTarget) . " rows(s) removed from " . $table);
                }

                // New Data
                $newDataTableTarget = $dataComparator->getNewDataTarget($dataOrigin,$dataTarget);
                if ($newDataTableTarget) {
                    $data = DataQueryGenerator::generateQueryNewData($newDataTableTarget,$table,$this->target);
                    Result::addToResult($data);
                    $this->log(count($newDataTableTarget) . " rows(s) added to " . $table);
                }

            }

        }

    }

}