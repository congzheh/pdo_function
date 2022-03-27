<?php

define("tableclass", "table");
define("buttonclass", "");

//PDO连接
function pdo_connect()
{
    include('./db_login.php');                  //引入数据库连接配置,请自行配置$dsn $userName $password
    $_opts_values = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => 2, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    $pdo = @new PDO($dsn, $userName, $password, $_opts_values); //创建一个连接对象
    return $pdo;                                //返回PDO连接信息

}

///一、有条件查询

//PDO查询返回行数
function pdo_search_num($database, $array_get)
{


    $column = pdo_get_column($array_get);
    $condition = pdo_get_condition($array_get);

    $num = count($column);
    $sqlcondition = "";
    for ($i = 0; $i < $num; $i++) {

        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $sql = "SELECT * FROM `" . $database . "` where  " . $sqlcondition . "";
    //echo $sql;
    pdo_connect()->exec('set names utf8');
    $results = pdo_connect()->query($sql)->rowCount();
    return $results;
    $results = null;
}


//PDO查询返回单值 array内只有一项
function pdo_search_value($database, $array_t, $array_c)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);
    $column = pdo_get_column($array_c);
    $condition = pdo_get_condition($array_c);
    $num_column = count($column);
    $sqlcondition = "";
    for ($i = 0; $i < $num_column; $i++) {
        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` where  " . $sqlcondition . " ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $value) {
        $output = $value[$t_column[0]];
    }
    return $output;
    $res = null;
    $stmt = null;
}

//PDO查询返回表格
function pdo_search_table($database, $array_t, $array_c)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);
    $column = pdo_get_column($array_c);
    $condition = pdo_get_condition($array_c);
    $num_column = count($column);
    $sqlcondition = "";
    for ($i = 0; $i < $num_column; $i++) {
        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` where  " . $sqlcondition . " ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "<table class='" . tableclass . "' >" . "<thead>" . $tablename . "</thead><tbody>";
    foreach ($res as $value) {
        $output .= "<tr>";
        for ($i = 0; $i < $num_t_column; $i++) {
            $t = "";
            $t = $value[$t_column[$i]];
            $output .= "<td>{$t}</td>";
        }
        $output .= "</tr>";
    }
    $output .= "</tbody></table>";
    return $output;
    $res = null;
    $stmt = null;
}

//PDO查询返回表格_带按钮
function pdo_search_table_b($database, $array_t, $array_c, $array_b)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);
    $column = pdo_get_column($array_c);
    $condition = pdo_get_condition($array_c);
    $num_column = count($column);

    $button_name = pdo_get_column($array_b);
    $button_value = pdo_get_condition($array_b);
    $num_button = count($button_name);

    $sqlcondition = "";
    for ($i = 0; $i < $num_column; $i++) {
        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` where  " . $sqlcondition . " ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "<table class='" . tableclass . "' >" . "<thead>" . $tablename . "<th>操作</th></thead><tbody>";
    foreach ($res as $value) {
        $output .= "<tr>";
        for ($i = 0; $i < $num_t_column; $i++) {
            $t = "";
            $t = $value[$t_column[$i]];
            $output .= "<td>{$t}</td>";
        }
        $b = "";
        for ($i = 0; $i < $num_button; $i++) {

            $b = $b . "<a class='" . $button_value[$i][2] . "' href='./" . $button_value[$i][3] . "?" . $button_value[$i][1] . "=" . $value[$button_value[$i][0]] . "'>" . $button_name[$i] . "</a> ";
        }
        $output .= "<td>{$b}</td>";
        $output .= "</tr>";
    }

    $output .= "</tbody></table>";
    return $output;
    $res = null;
    $stmt = null;
}
function pdo_search_table_bm($database, $array_t, $array_c, $array_b)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);
    $column = pdo_get_column($array_c);
    $condition = pdo_get_condition($array_c);
    $num_column = count($column);

    $button_name = pdo_get_column($array_b);
    $button_value = pdo_get_condition($array_b);
    $num_button = count($button_name);

    $sqlcondition = "";
    for ($i = 0; $i < $num_column; $i++) {
        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` where  " . $sqlcondition . " ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "<table class='" . tableclass . "' >" . "<thead>" . $tablename . "<th>操作</th></thead><tbody>";
    foreach ($res as $value) {
        $output .= "<tr>";
        for ($i = 0; $i < $num_t_column; $i++) {
            $t = "";
            $t = $value[$t_column[$i]];
            $output .= "<td>{$t}</td>";
        }
        $b = "";
        for ($i = 0; $i < $num_button; $i++) {

            $b = $b . "<a class='" . $button_value[$i][2] . "' href='./" . $button_value[$i][3] . "?" . $button_value[$i][1] . "=" . $value[$button_value[$i][0]] . "'>" . $button_name[$i] . "</a> ";
        }
        $output .= "<td>{$b}</td>";
        $output .= "</tr>";
    }

    $output .= "</tbody></table>";
    return $output;
    $res = null;
    $stmt = null;
}

///二、无条件查询

//PDO查询返回行数
function pdo_search_num_nc($database)
{

    $sql = "SELECT * FROM `" . $database . "` ";
    pdo_connect()->exec('set names utf8');
    $results = pdo_connect()->query($sql)->rowCount();
    return $results;
    $results = null;
}


//PDO查询返回单值 array内只有一项
function pdo_search_value_nc($database, $array_t)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);


    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "`";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($res as $value) {
        $output = $value[$t_column[0]];
    }
    return $output;
    $stmt = null;
    $res = null;
}

//PDO查询返回表格
function pdo_search_table_nc($database, $array_t)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);


    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "<table class='" . tableclass . "' >" . "<thead>" . $tablename . "</thead><tbody>";
    foreach ($res as $value) {
        $output .= "<tr>";
        for ($i = 0; $i < $num_t_column; $i++) {
            $t = "";
            $t = $value[$t_column[$i]];
            $output .= "<td>{$t}</td>";
        }
        $output .= "</tr>";
    }
    $output .= "</tr></tbody>";
    return $output;
    $stmt = null;
    $res = null;
}

//PDO查询返回表格_带按钮
function pdo_search_table_b_nc($database, $array_t, $array_b)
{
    $t_column = pdo_get_condition($array_t);
    $t_name = pdo_get_column($array_t);


    $button_name = pdo_get_column($array_b);
    $button_value = pdo_get_condition($array_b);
    $num_button = count($button_name);


    $num_t_column = count($t_column);
    $sqlselect = "";
    $tablename = "";
    for ($i = 0; $i < $num_t_column; $i++) {
        if ($i == 0) {
            $sqlselect .= $t_column[$i];
        } else {
            $sqlselect .= "," . $t_column[$i];
        }
        $tablename .= "<th>" . $t_name[$i] . "</th>";
    }
    $sql = "SELECT " . $sqlselect . " FROM `" . $database . "` ";
    pdo_connect()->exec('set names utf8');
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = "<table class='" . tableclass . "' >" . "<thead>" . $tablename . "<th>操作</th></thead><tbody>";
    foreach ($res as $value) {
        $output .= "<tr>";
        for ($i = 0; $i < $num_t_column; $i++) {
            $t = "";
            $t = $value[$t_column[$i]];
            $output .= "<td>{$t}</td>";
        }
        $b = "";
        for ($i = 0; $i < $num_button; $i++) {

            $b = $b . "<a class='" . $button_value[$i][2] . "' href='./" . $button_value[$i][3] . "?" . $button_value[$i][1] . "=" . $value[$button_value[$i][0]] . "'>" . $button_name[$i] . "</a> ";
        }
        $output .= "<td>{$b}</td>";
        $output .= "</tr>";
    }

    $output .= "</tbody></table>";
    return $output;
    $stmt = null;
    $res = null;
}

///三、注入
//注入操作1
function pdo_insert($database, $array)
{
    $t_name = pdo_get_condition($array);
    $t_column = pdo_get_column($array);
    $num_t = count($t_column);
    $c = "(";
    $v = "(";
    for ($i = 0; $i < $num_t; $i++) {
        if ($i == 0) {
            $c .= "`" . $t_column[$i] . "`";
            $v .= "'" . $t_name[$i] . "'";
        } elseif ($i == $num_t - 1) {
            $c .= ",`" . $t_column[$i] . "`)";
            $v .= ",'" . $t_name[$i] . "')";
        } else {
            $c .= ",`" . $t_column[$i] . "`";
            $v .= ",'" . $t_name[$i] . "'";
        }
    }
    $sql = "INSERT INTO `" . $database . "` " . $c . " VALUES " . $v . ";";
    //echo $sql;
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $stmt = null;
}
//注入操作2
function pdo_insert_a($database, $a, $b)
{

    $sql = "INSERT INTO `" . $database . "` " . $a . " VALUES " . $b . ";";
    //echo $sql;
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $stmt = null;
}
///四、更新
//更新操作
function pdo_updata($database, $array, $conditiong)
{
    $t_name = pdo_get_condition($array);
    $t_column = pdo_get_column($array);
    $column = pdo_get_column($conditiong);
    $condition = pdo_get_condition($conditiong);
    $num_column = count($column);
    $sqlcondition = "";
    for ($i = 0; $i < $num_column; $i++) {
        if ($i == 0) {
            $sqlcondition .= $column[$i] . "  ='" . $condition[$i] . "'";
        } else {
            $sqlcondition .= " and " . $column[$i] . "  ='" . $condition[$i] . "'";
        }
    }
    $num_t = count($t_column);
    $c = "";

    for ($i = 0; $i < $num_t; $i++) {
        if ($i == 0) {
            $c .= "`" . $t_column[$i] . "`=" . "'" . $t_name[$i] . "'";
        } else {
            $c .= ",`" . $t_column[$i] . "`" . "='" . $t_name[$i] . "'";
        }
    }
    $sql = "UPDATE `$database` SET " . $c . " WHERE " . $sqlcondition . ";
    ";
    //echo $sql;
    $stmt = pdo_connect()->prepare($sql);
    $stmt->execute();
    $stmt = null;
}

///末、查询转换
function pdo_get_column($arr)
{
    return array_keys($arr);
}

function pdo_get_condition($arr)
{
    $array_k = array_keys($arr);
    $num = count(array_keys($arr));
    $arr_new = [];
    for ($i = 0; $i < $num; $i++) {
        $arr_new[$i] = $arr[$array_k[$i]];
    }
    return $arr_new;
}
