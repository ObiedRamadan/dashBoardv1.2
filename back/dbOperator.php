<?php

interface dbDigram
{
    function newConn($type, $server, $dbName, $userName, $password);
    function conn();
    function getDatabases();
    function getTables();
    function setTableName($tableName);
    function getRecord();
    function addRecord(array $coulmn, array $records);
    function updata($id, $coulmn, $value);
    function delete($id);
}
class dbOperator implements dbDigram
{
    private $dbType = 'mysql';
    private $server = 'localhost:6000';
    private $userName = 'root';
    private $password = '';
    private $dbName;
    private $tableName;
    private $cont;
    private $dbError;


    public function __construct($dbName = null)
    {
        /* the Default connection is localhost to change use newConn() */
        $this->dbName = $dbName;
    }

    public function newConn($dbType, $server, $dbName, $userName, $password)
    {
        /*  i bulid this algorthim to make it possible to change one info as other still default */

        $setting = [$dbType, $server, $dbName, $userName, $password];
        $role = [&$this->dbType, &$this->server, &$this->dbName, &$this->userName, &$this->password];
        for ($i = 0; $i < 5; $i++) {
            if ($setting[$i] == '') {
                continue;
            }
            $role[$i] = $setting[$i];
        }
        return $this->conn();
    }

    public function conn()
    {
        try {
            $this->cont = new PDO("$this->dbType:host=$this->server;dbname=$this->dbName", $this->userName, $this->password);
            return $this->cont;
        } catch (PDOException $e) {
            $this->dbError[] = 'Falid To Connection';
            $this->dbError[] = $e->getMessage();
            return $e->getMessage();
        }
    }

    public function getDatabases()
    {
        $databases = $this->cont->prepare('SHOW DATABASES');
        if ($databases->execute()) {
            return ($databases->fetchAll());
        };
        $this->dbError[] = 'Falid To Show Database';
    }

    public function getTables()
    {
        $tables = $this->cont->prepare("SHOW Full TABLES FROM $this->dbName ");
        if ($tables->execute()) {
            return $tables->fetchAll();
        }
        $this->dbError[] = 'Falid during get Tables';
    }

    public function setTableName($tName)
    {
        $tableContent = $this->cont->prepare("EXPLAIN  $tName ");
        if ($tableContent->execute()) {
            $this->tableName = $tName;
            return true;
        }
        $this->dbError[] = 'Falid during Set Table Name';
    }

    public function getColumns()
    {
        $tableContent = $this->cont->prepare("EXPLAIN  $this->tableName ");
        if ($tableContent->execute()) {
            return $tableContent->fetchAll();
        }
        $this->dbError[] = 'Falid during Set get Column';
    }

    public function getRecord()
    {
        $records = $this->cont->prepare("SELECT * FROM $this->tableName ");
        if ($records->execute()) {
            return $records->fetchAll();
        }
        $this->dbError[] = 'Falid during fetch Record';
    }

    public function addRecord(array $coulmn, array $records)
    {
        $sql = $this->buildSqlInsertQuery($coulmn);
        $addRecord = $this->cont->prepare("INSERT INTO $this->tableName $sql");
        if (!$addRecord->execute($records)) {
            $this->dbError[] = 'Falid during Add Record';
        }
    }

    public function updata($id, $coulmn, $value)
    {
        if ($this->find($id)) {
            $sql = $this->buildSqlUpdataQuery($coulmn);
            $rowNumber = $this->cont->prepare("UPDATE $this->tableName SET $sql WHERE id=? limit 1");
            $value[] = $id;
            if (!$rowNumber->execute($value)) {
                $this->dbError[] = 'Falid to Upadata this Record';
            };
        }
    }

    public function delete($id)
    {
        if ($this->find($id)) {
            $rowNumber = $this->cont->prepare("DELETE FROM $this->tableName WHERE id=? limit 1");
            if (!$rowNumber->execute([$id])) {
                $this->dbError[] = 'Falid to Delete this Record where id is ' . $id;
            }
        }
    }

    public function find($id)
    {
        $rowNumber = $this->cont->prepare("SELECT * FROM $this->tableName WHERE id=? LIMIT 1 ");
        if ($rowNumber->execute([$id])) {
            if (count($rowNumber->fetchAll()) > 0) {
                return true;
            }
            $this->dbError[] = 'Falid No Record by Id =' . $id;
        }
        $this->dbError[] = 'Falid In find table and id ';
    }

    private function buildSqlUpdataQuery($col)
    {
        $sql = "$col[0]=?";
        for ($i = 1; $i < count($col); $i++) {
            $sql = $sql . ",$col[$i]=?";
        }
        return $sql;
    }

    public function buildSqlInsertQuery($col)
    {
        $attr = "$col[0]";
        $value = "?";
        for ($i = 1; $i < count($col); $i++) {
            $attr = $attr . ",$col[$i]";
            $value = $value . ",?";
        }
        $sql = "($attr)VAlUE($value)";
        return $sql;
    }

    public function __destruct()
    {
        $_SESSION['error'] = $this->dbError;
    }
}