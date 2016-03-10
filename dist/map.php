<?php
$host="localhost";
$user="root";
$password="";
$db="my_db";

if(!mysql_connect("$host", "$user", "$password")) {
    exit(mysql_error());
} else {
    //echo "Подключение к серверу возможно...";
}

if (!mysql_select_db($db)) { // если базы данных нет
    $r = mysql_query("CREATE DATABASE $db"); // создаем новую
    echo "Создана новая БД...";
    if(!$r) {
        exit(mysql_error());
    }
} else {
    //echo "Подключение к БД возможно...";
}
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $db)
    or die("Ошибка " . mysqli_error($link));

mysql_query("CREATE TABLE IF NOT EXISTS `cities`(
`id` INT(11) NOT NULL AUTO_INCREMENT,
        `city` TEXT(1000) NOT NULL,
        `cordinatesx` TEXT(1000) NOT NULL,
        `cordinatesy` TEXT(1000) NOT NULL,
        `zoom` TEXT(1000) NOT NULL,
        PRIMARY KEY(`id`)
    )") or die(mysql_error());

mysql_query("CREATE TABLE IF NOT EXISTS `markets`(
`id` INT(11) NOT NULL AUTO_INCREMENT,
        `city` TEXT(1000) NOT NULL,
        `name` TEXT(1000) NOT NULL,
        `text` TEXT(1000) NOT NULL,
        `link` TEXT(1000) NOT NULL,
        `cordinatesx` TEXT(1000) NOT NULL,
        `cordinatesy` TEXT(1000) NOT NULL,
        PRIMARY KEY(`id`)
    )") or die(mysql_error());

$query = "SELECT * FROM `cities`";
$res = mysql_query($query);

$query2 = "SELECT * FROM `markets`";
$res2 = mysql_query($query2);

$query3 = "SELECT id FROM `cities`";
$res3 = mysql_query($query3);

switch ($_REQUEST['action']) {
    case 'sample1':
        echo "<h1>Таблица городов</h1>";
        echo "<table class='ymwap__table'>";
            echo "<tr>";
                echo "<td>Номер города</td>";
                echo "<td>Название города</td>";
                echo "<td>x</td>";
                echo "<td>y</td>";
                echo "<td>zoom</td>";
            echo "</tr>";
            while($row = mysql_fetch_array($res))
            {
                echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['city']."</td>";
                    echo "<td>".$row['cordinatesx']."</td>";
                    echo "<td>".$row['cordinatesy']."</td>";
                    echo "<td>".$row['zoom']."</td>";
                echo "</tr>";
            }
        echo "</table>";
        break;
    case 'sample2':
        echo "<h1>Таблица точек</h1>";
        echo "<table class='ymwap__table'>";
            echo "<tr>";
                echo "<td>ID точки</td>";
                echo "<td>Название города</td>";
                echo "<td>Название точки</td>";
                echo "<td>Текст</td>";
                echo "<td>Ссылка</td>";
                echo "<td>x</td>";
                echo "<td>y</td>";
            echo "</tr>";
            while($row = mysql_fetch_array($res2))
            {
                echo "<tr>";
                    echo "<td>".$row['id']."</td>";
                    echo "<td>".$row['city']."</td>";
                    echo "<td>".$row['name']."</td>";
                    echo "<td>".$row['text']."</td>";
                    echo "<td>".$row['link']."</td>";
                    echo "<td>".$row['cordinatesx']."</td>";
                    echo "<td>".$row['cordinatesy']."</td>";
                echo "</tr>";
            }
        echo "</table>";
        break;
    case 'sample3':
        echo "<select>";
        while($row = mysql_fetch_array($res3))
            {
                echo "<option value='".$row['id']."'>".$row['id']."</option>";
            }
        echo "</select>";
        break;
    case 'sample4':
        $currentSelectDeliteCity = $_REQUEST['currentSelectDeliteCity'];
        //echo $currentSelectDeliteCity;
        mysql_query(" DELETE FROM `cities` WHERE `id`= $currentSelectDeliteCity ");
        break;
    case 'sample5':
        //echo "aefsdggsd";
        mysql_query("INSERT INTO `cities` (`city`, `cordinatesx`, `cordinatesy`, `zoom`) VALUES ('".$_REQUEST['nameCity']."','".$_REQUEST['x']."','".$_REQUEST['y']."','".$_REQUEST['zoom']."')");
}

?>
