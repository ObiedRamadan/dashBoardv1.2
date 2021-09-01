<?php
/* 
    * check that connection detail is already Exist
    * and connect to database
*/
include '../doc/function.php';
session_start();

if (isset($_SESSION['connInfo'])) {
    include '../back/dbOperator.php';
    $db = new dbOperator();
    $connInfo = $_SESSION['connInfo'];
    $db->newConn($connInfo['dbType'], $connInfo['host'], $connInfo['dbName'], $connInfo['userName'], $connInfo['password']);
} else {
    header('Location:../view/serverLogin.php');
}
/* 
    1- get All database
    2- if there are database  get tables else put it default
    3- if there are table  get column and record else put it default []
    4- get total pagination 
    5- get pag
    
*/
$databases = $db->getDatabases();
// if there are database set table
if ($_SESSION['connInfo']['dbName'] != '') {
    $table = $db->getTables();
} else {
    $table = [];
}
// if there are table select set column and record
if (isset($_SESSION['tableName']) && $_SESSION['tableName'] != '') {
    $db->setTableName($_SESSION['tableName']);
    $tableColums = $db->getColumns();
    $tableRecord = $db->getRecord();
} else {
    $tableColums = [];
    $tableRecord = [];
}
// if there are record set pagination
if (count($tableRecord) > 10) {
    $totalPag = ceil(count($tableRecord) / 10) - 1;
} else {
    $totalPag = 0;
}

if (isset($_SESSION['pag'])) {
    $pag = $_SESSION['pag'];
} else {
    $pag = 0;
}