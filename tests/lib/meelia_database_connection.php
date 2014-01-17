<?php

class MeeliaDatabaseConnection implements PHPUnit_Extensions_Database_DB_IDatabaseConnection
{
    protected $driver;

    public function __construct($driver, $schema) {
        $this->driver = $driver;
    }

    public function close() {
    }

    public function createDataSet() {
    }

    public function createQueryTable() {
    }

    public function getConnection() {
    }

    public function getMetaData() {
    }

    public function getRowCount() {
    }

    public function getSchema() {
    }

    public function quoteSchemaObject() {
    }

    public function getTruncateCommand() {
    }

    public function allowsCascading() {
    }

    public function disablePrimaryKeys() {
    }

    public function enablePrimaryKeys() {
    }
}
