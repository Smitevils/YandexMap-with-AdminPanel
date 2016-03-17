<?php

/* функия кодировки */
function json_fix_cyr($json_str) {
$cyr_chars = array (
'\u0430' => 'а', '\u0410' => 'А',
'\u0431' => 'б', '\u0411' => 'Б',
'\u0432' => 'в', '\u0412' => 'В',
'\u0433' => 'г', '\u0413' => 'Г',
'\u0434' => 'д', '\u0414' => 'Д',
'\u0435' => 'е', '\u0415' => 'Е',
'\u0451' => 'ё', '\u0401' => 'Ё',
'\u0436' => 'ж', '\u0416' => 'Ж',
'\u0437' => 'з', '\u0417' => 'З',
'\u0438' => 'и', '\u0418' => 'И',
'\u0439' => 'й', '\u0419' => 'Й',
'\u043a' => 'к', '\u041a' => 'К',
'\u043b' => 'л', '\u041b' => 'Л',
'\u043c' => 'м', '\u041c' => 'М',
'\u043d' => 'н', '\u041d' => 'Н',
'\u043e' => 'о', '\u041e' => 'О',
'\u043f' => 'п', '\u041f' => 'П',
'\u0440' => 'р', '\u0420' => 'Р',
'\u0441' => 'с', '\u0421' => 'С',
'\u0442' => 'т', '\u0422' => 'Т',
'\u0443' => 'у', '\u0423' => 'У',
'\u0444' => 'ф', '\u0424' => 'Ф',
'\u0445' => 'х', '\u0425' => 'Х',
'\u0446' => 'ц', '\u0426' => 'Ц',
'\u0447' => 'ч', '\u0427' => 'Ч',
'\u0448' => 'ш', '\u0428' => 'Ш',
'\u0449' => 'щ', '\u0429' => 'Щ',
'\u044a' => 'ъ', '\u042a' => 'Ъ',
'\u044b' => 'ы', '\u042b' => 'Ы',
'\u044c' => 'ь', '\u042c' => 'Ь',
'\u044d' => 'э', '\u042d' => 'Э',
'\u044e' => 'ю', '\u042e' => 'Ю',
'\u044f' => 'я', '\u042f' => 'Я',

'\r' => '',
'\n' => '<br />',
'\t' => ''
);

foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
$json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
}
return $json_str;
}
/* функия кодировки */

$host="localhost";
$user="root";
$password="";
$db="my_db";

// $host="localhost";
// $user="srv39201_maps";
// $password="trem45";
// $db="srv39201_maps";

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

mysql_query("CREATE TABLE IF NOT EXISTS `clients`(
`id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` TEXT(1000) NOT NULL,
        PRIMARY KEY(`id`)
    )") or die(mysql_error());

$query = "SELECT * FROM `cities`";
$res = mysql_query($query);

$query2 = "SELECT * FROM `markets`";
$res2 = mysql_query($query2);

$query3 = "SELECT id FROM `cities`";
$res3 = mysql_query($query3);

$query4 = "SELECT id FROM `markets`";
$res4 = mysql_query($query4);

$query5 = "SELECT city FROM `cities`";
$res5 = mysql_query($query5);

$query6 = "SELECT * FROM `cities`";
$res6 = mysql_query($query6);

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
        break;
    case 'sample6':
        echo "<select>";
        while($row = mysql_fetch_array($res4))
            {
                echo "<option value='".$row['id']."'>".$row['id']."</option>";
            }
        echo "</select>";
        break;
    case 'sample7':
        $currentSelectDeliteMarket = $_REQUEST['currentSelectDeliteMarket'];
        mysql_query(" DELETE FROM `markets` WHERE `id`= $currentSelectDeliteMarket ");
        break;
    case 'sample8':
        mysql_query("INSERT INTO `markets` (`city`, `name`, `text`, `link`, `cordinatesx`, `cordinatesy`) VALUES ('".$_REQUEST['city']."','".$_REQUEST['market']."','".$_REQUEST['text']."','".$_REQUEST['link']."','".$_REQUEST['x']."','".$_REQUEST['y']."')");
        break;
    case 'citieslist':
        $array = array();
        while($row = mysql_fetch_array($res5))
            {
                array_push($array, $row['city']);
            }
        echo json_encode($array);
        break;
    case 'citiesNumberlist':
        $array = array();
        while($row = mysql_fetch_array($res6))
            {
                array_push($array, $row['id']);
            }
        echo json_encode($array);
        break;
    case 'currentCity':
        class map
        {
            public $idMap = 'a default value';
            public $cordinates = 'a default value';
            public $zoom = 'a default value';
            public $markets = 'a default value';
        }
        /* класс координат карты */
        class mapCordinates
        {
            public $x = 'a default value';
            public $y = 'a default value';
        }
        /* класс маркеров */
        class marketPoints
        {
            public $posX = '55.79';
            public $posY = '37.64';
            public $marketName = 'marketName';
            public $marketText = 'marketText';
            public $marketLink = 'marketLink';
        }
        // объект класса маркеров
        $marketPointsMap = new marketPoints;
        // массив для хранения обьектов маркеров
        $marketPointsMapArray = array();
        // получаем имя города по которому будем отбирать маркеры
        $findNameTargetCity = $_REQUEST['targetCity'];
        $query = "SELECT * FROM `cities` WHERE `id` = $findNameTargetCity";
        $res = mysql_query($query);
        //array_push($marketPointsMapArray, $marketPointsMap);
            while($row = mysql_fetch_array($res))
            {
                $targetCityPoints = $row['city'];
                $targetCityPoints = trim($targetCityPoints);
                //echo $targetCityPoints;
            }
            $query = "SELECT * FROM `markets` WHERE `city` = '$targetCityPoints'"; /*$targetCityPoints*/
            $res = mysql_query($query)  /*|| die(mysql_error())*/;
            while($row = mysql_fetch_array($res))
            {
                $marketPointsMap = new marketPoints;
                $marketPointsMap->posX = $row['cordinatesx'];
                $marketPointsMap->posY = $row['cordinatesy'];
                $marketPointsMap->marketName = $row['name'];
                $marketPointsMap->marketText = $row['text'];
                $marketPointsMap->marketLink = $row['link'];
                array_push($marketPointsMapArray, $marketPointsMap);
            }
        //array_push($marketPointsMapArray, $marketPointsMap);
        //array_push($marketPointsMapArray, $row['city']);

        $coordinatesMap = new mapCordinates;
        $targetCity = $_REQUEST['targetCity'];
        $coordinatesMapCity = mysql_query("SELECT * FROM `cities` WHERE `id`= $targetCity");

        //$coordinatesMap->x = $coordinatesMapCity['city'];
        /* /класс координат карты */
        $objectMap = new map;
        $objectMap->idMap = $_REQUEST['targetCity'];
        $objectMap->cordinates = $coordinatesMap;
        $objectMap->markets = $marketPointsMapArray;

        while($row = mysql_fetch_array($coordinatesMapCity))
        {
            $coordinatesMap->x = $row['cordinatesx'];
            $coordinatesMap->y = $row['cordinatesy'];
            $objectMap->zoom = $row['zoom'];
        }
        //$objectMap->zoom = 22;
        //echo json_encode($objectMap);
        echo json_fix_cyr(json_encode($objectMap)); // ["собака","кошка"]
        break;
    case 'buildCitiesTable':
        $query7 = "SELECT * FROM `cities` ORDER BY `city`";
        $res7 = mysql_query($query7);
            while($row = mysql_fetch_array($res7))
            {
                echo "<tr>";
                echo "<td>";
                echo $row['id'];
                echo "</td>";
                echo "<td>";
                echo $row['city'];
                echo "</td>";
                echo "<td>";
                echo $row['cordinatesx'];
                echo "</td>";
                echo "<td>";
                echo $row['cordinatesy'];
                echo "</td>";
                echo "<td>";
                echo $row['zoom'];
                echo "</td>";
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-pencil ymwap__edit\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-trash ymwap__delite\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        break;
    case 'removeCity':
    $cityID = $_REQUEST['cityID'];
        $cityID = $_REQUEST['cityID'];
        mysql_query(" DELETE FROM `cities` WHERE `id`= $cityID");
        break;
    case 'changeCity':
        $cityID = $_REQUEST['cityID'];
        $cityName = $_REQUEST['cityName'];
        $x = $_REQUEST['x'];
        $y = $_REQUEST['y'];
        $zoom = $_REQUEST['zoom'];

        $query8 = "UPDATE `cities` SET `city`= '".$_REQUEST['cityName']."',`cordinatesx`= '".$_REQUEST['x']."',`cordinatesy`= '".$_REQUEST['y']."',`zoom`= '".$_REQUEST['zoom']."' WHERE `id`= $cityID";
        $res8 = mysql_query($query8);
            // while($row = mysql_fetch_array($res8))
            // {
            //     echo $cityID;
            // }
        break;
    case 'addCity':
        $cityName = $_REQUEST['cityName'];
        $x = $_REQUEST['x'];
        $y = $_REQUEST['y'];
        $zoom = $_REQUEST['zoom'];
        mysql_query("INSERT INTO `cities` (`city`, `cordinatesx`, `cordinatesy`, `zoom`) VALUES ('".$_REQUEST['cityName']."', '".$_REQUEST['x']."','".$_REQUEST['y']."','".$_REQUEST['zoom']."')");
        //mysql_query("INSERT INTO `cities` (`city`, `cordinatesx`, `cordinatesy`) VALUES ('".$_REQUEST['cityName']."', '".$_REQUEST['x']."')");
        echo $cityName." ".$x." ".$y." ".$zoom;
        break;
    case 'buildPointsTable':
        $query = "SELECT * FROM `markets` ORDER BY `city`";
        $res = mysql_query($query);
            while($row = mysql_fetch_array($res))
            {
                echo "<tr>";
                echo "<td>";
                echo $row['id'];
                echo "</td>";
                echo "<td>";
                echo $row['city'];
                echo "</td>";
                echo "<td>";
                echo $row['name'];
                echo "</td>";
                echo "<td>";
                echo $row['text'];
                echo "</td>";
                echo "<td>";
                echo $row['link'];
                echo "</td>";
                echo "<td>";
                echo $row['cordinatesx'];
                echo "</td>";
                echo "<td>";
                echo $row['cordinatesy'];
                echo "</td>";
                echo "<td>";
                // client
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-pencil ymwap__edit\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-trash ymwap__delite\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        break;
    case 'changePoints':
        //$query = "UPDATE `markets` SET `city`= '".$_REQUEST['cityName']."',`pointName`= '".$_REQUEST['pointName']."',`pointText`= '".$_REQUEST['pointText']."',`pointLink`= '".$_REQUEST['pointLink']."',`cordinatesx`= '".$_REQUEST['x']."',`cordinatesy`= '".$_REQUEST['y']."' WHERE `id`= '".$_REQUEST['cityID']."'";
        $query = "UPDATE `markets` SET `city`= '".$_REQUEST['cityName']."',`name`= '".$_REQUEST['pointName']."',`text`= '".$_REQUEST['pointText']."',`link`= '".$_REQUEST['pointLink']."',`cordinatesx`= '".$_REQUEST['x']."',`cordinatesy`= '".$_REQUEST['y']."' WHERE `id`= '".$_REQUEST['cityID']."'";
        $res = mysql_query($query);
            // while($row = mysql_fetch_array($res8))
            // {
            //     echo $cityID;
            // }
            echo $_REQUEST['cityName'];
        break;
    case 'removePoint':
        $cityID = $_REQUEST['cityID'];
        mysql_query(" DELETE FROM `markets` WHERE `id`= $cityID");
        break;
    case 'addPoint':
        mysql_query("INSERT INTO `markets` (`city`, `name`, `text`, `link`, `cordinatesx`, `cordinatesy`) VALUES ('".$_REQUEST['cityName']."', '".$_REQUEST['pointName']."', '".$_REQUEST['pointText']."', '".$_REQUEST['pointLink']."', '".$_REQUEST['x']."','".$_REQUEST['y']."')");
        //mysql_query("INSERT INTO `markets` (`city`, `name`, `text`, `link`, `cordinatesx`, `cordinatesy`) VALUES ('".$_REQUEST['cityName']."', '".$_REQUEST['pointName']."', '".$_REQUEST['pointText']."', '".$_REQUEST['pointLink']."', '".$_REQUEST['x']."','".$_REQUEST['y']."')");
        break;
    case 'buildClientsTable':
        $query = "SELECT * FROM `clients` ORDER BY `name`";
        $res = mysql_query($query);
            while($row = mysql_fetch_array($res))
            {
                echo "<tr>";
                echo "<td>";
                echo $row['id'];
                echo "</td>";
                echo "<td>";
                echo $row['name'];
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-pencil ymwap__edit\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "<td>";
                    echo "<div class=\"ymwap-admin__table__btn-event\">";
                        echo "<i class=\"fa fa-trash ymwap__delite\"></i>";
                    echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        break;
    case 'addClient':
        mysql_query("INSERT INTO `clients` (`name`) VALUES ('".$_REQUEST['clientName']."')");
        //mysql_query("INSERT INTO `markets` (`city`, `name`, `text`, `link`, `cordinatesx`, `cordinatesy`) VALUES ('".$_REQUEST['cityName']."', '".$_REQUEST['pointName']."', '".$_REQUEST['pointText']."', '".$_REQUEST['pointLink']."', '".$_REQUEST['x']."','".$_REQUEST['y']."')");
        break;
    case 'removeClient':
        $clientID = $_REQUEST['clientID'];
        mysql_query(" DELETE FROM `clients` WHERE `id`= $clientID");
        break;
    case 'changeClient':
        //$query = "UPDATE `markets` SET `city`= '".$_REQUEST['cityName']."',`pointName`= '".$_REQUEST['pointName']."',`pointText`= '".$_REQUEST['pointText']."',`pointLink`= '".$_REQUEST['pointLink']."',`cordinatesx`= '".$_REQUEST['x']."',`cordinatesy`= '".$_REQUEST['y']."' WHERE `id`= '".$_REQUEST['cityID']."'";
        $query = "UPDATE `clients` SET `name`= '".$_REQUEST['clientName']."' WHERE `id`= '".$_REQUEST['clientID']."' ";
        $res = mysql_query($query);
            // while($row = mysql_fetch_array($res8))
            // {
            //     echo $cityID;
            // }
            echo $_REQUEST['clientName'];
        break;
}

// формируем json строку со списком городов



?>
