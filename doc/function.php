<?php
function printArr($arr)
{
    echo "<pre>";
    if (is_array($arr)) {
        echo "[" . count($arr) . "] ";
    }
    print_r($arr);
    echo "</pre><br>";
}

function br()
{
    echo "<br>";
}
function hr()
{
    echo "<br>";
    echo "<hr>";
}

function addAssocArr(array $arr1, array $arr2)
{
    $arr = [];
    foreach ($arr1 as $value) {
        $arr[] = $value;
    }
    foreach ($arr2 as $value) {
        $arr[] = $value;
    }
    return $arr;
};
function addIndexArr($arr1, $arr2)
{
}
function addAssocArrWithoutRepeatName(array $arr1, array $arr2)
{
    $arr = addAssocArr($arr1, $arr2);
    $data = $arr;
    foreach ($arr1 as $key1 => $value1) {
        foreach ($arr2 as $key => $value2) {
            if ($value1['name'] == $value2['name']) {
                // array_splice($data, $key1, 1);
                unset($data[$key1]);
            }
        }
    }
    return $data;
}
// used for validation to remove every slashs in string
function removeslashes($string)
{
    $string = implode("", explode("\\", $string));
    return stripslashes($string);
}
// repeat the string number of time
function repeatStr($str, $number)
{
    $val=$str;
    for ($i = 1; $i < $number; $i++) {
        $val = $val . ',' . $str;
    }
    return $val;
}
