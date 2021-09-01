<?php
include '../back/dbOperator.php';
include '../doc/function.php';
session_start();
class systemController
{
    private $db;
    public function __construct()
    {
        /* 
            * init the db and git instance from dp operator
            * set tableName if exsist
        */
        $this->db = new dbOperator();
        $connInfo = $_SESSION['connInfo'];
        $this->db->newConn($connInfo['dbType'], $connInfo['host'], $connInfo['dbName'], $connInfo['userName'], $connInfo['password']);
        if (isset($_SESSION['tableName'])) {
            $this->db->setTableName($_SESSION['tableName']);
        }
    }
    public function connection(array $connInfo)
    {
        /* 
            * drop session to sure that no pug will happen
            * set new connection for server
        */
        $this->db = $this->db->newConn($connInfo['dbType'], $connInfo['host'], $connInfo['dbName'], $connInfo['userName'], $connInfo['password']);
        if (is_string($this->db)) {
            header("location:../view/serverLogin.php");
            return 0;
        }
        $_SESSION['connInfo'] = $connInfo;
        header("location:../view/dashboard.php");
    }

    public function checkDbExist($dbName)
    {
        /* 
            * delete old table name
            * check if connection done succss in this database name add it into session 
            * else but dbName in session as empty string to stop other opertion 
            * set Error
        */
        if (isset($_SESSION['tableName'])) {
            unset($_SESSION['tableName']);
        }
        $connInfo = $_SESSION['connInfo'];
        $this->db = $this->db->newConn($connInfo['dbType'], $connInfo['host'], $dbName, $connInfo['userName'], $connInfo['password']);

        if (!is_string($this->db)) {
            $_SESSION['connInfo']['dbName'] = $dbName;
            unset($_SESSION['tableName']);
            return true;
        }
        $_SESSION['connInfo']['dbName'] = '';
        $_SESSION["error"][] = 'Please Select Exist Database';
    }

    public function setTableName($tName)
    {
        /* 
        * save table name in session but after check that there table by this name
        * response from setTable Name method is true or null
        * paginagtion setDefault 1
        */
        $_SESSION['pag']=0;
        if ($this->db->setTableName($tName)) {
            $_SESSION['tableName'] = $tName;
            return 0;
        }
        $_SESSION['tableName'] = '';
    }

    public function getCols()
    {
        return $this->db->getColumns();
    }

    public function addRow($columns, $records)
    {
        $this->db->addRecord($columns, $records);
    }

    public function updataRecord($id, $columns, $records)
    {
        $this->db->updata($id, $columns, $records);
    }

    public function deleteRecord($id)
    {
        $this->db->delete($id);
    }
}



if (isset($_POST['serverCont'])) {
    $connInfo = [
        'dbType' => $_POST['dbType'],
        'host' => $_POST['host'],
        'dbName' => $_POST['dbName'],
        'userName' => $_POST['userName'],
        'password' => $_POST['password']
    ];
    $checkConn = new systemController();
    $checkConn->connection($connInfo);
}

if (isset($_POST['setDb'])) {
    $checkDbConn = new systemController();
    $checkDbConn->checkDbExist($_POST['databaseName']);
    header('Location:../view/dashboard.php');
}

if (isset($_POST['logout'])) {
    session_unset();
    header('Location:../../view/serverLogin.php');
}

if (isset($_POST['setTableName'])) {
    $setTable = new systemController();
    $setTable->setTableName($_POST['tableName']);
    header('Location:../view/dashboard.php');
}

if (isset($_POST['add'])) {
    $sysControl = new systemController();
    $tableColumns = $sysControl->getCols();
    for ($i = 1; $i < count($tableColumns); $i++) {
        $value = $_POST[$tableColumns[$i]['Field']];
        if ($value == null) {
            continue;
        }
        $columns[] = $tableColumns[$i]['Field'];
        $records[] = $value;
    }
    $sysControl->addRow($columns, $records);
    header('Location:../view/dashboard.php');
}

if (isset($_POST['saveUpdata'])) {
    $sysControl = new systemController();
    $tableColumns = $sysControl->getCols();
    for ($i = 1; $i < count($tableColumns); $i++) {
        $value = $_POST[$tableColumns[$i]['Field']];
        if ($value == null) {
            continue;
        }
        $columns[] = $tableColumns[$i]['Field'];
        $records[] = $value;
    }
    $id = $_POST['id'];
    $sysControl->updataRecord($id, $columns, $records);
    header('Location:../view/dashboard.php');
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sysControl = new systemController();
    $sysControl->deleteRecord($id);
    header('Location:../view/dashboard.php');
}

if (isset($_POST['setPag'])) {
    $pag = $_POST['pag'];
    $_SESSION['pag'] = $pag;
    header('Location:../view/dashboard.php');
}
