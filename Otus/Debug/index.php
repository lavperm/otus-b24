<?php
require ($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');
$APPLICATION->SetTitle(title: "Новая Страница");
//require  '/home/c/ck55016/public_html/local/App/Debug/Log.php' ;
/*
print_r('<pre>');
print_r('$_SERVER: ');
print_r($_SERVER);
print_r("</pre>");*/

/*print_r('<pre>');
print_r('$_SERVER: ');
print_r(var_dump($_SERVER));
print_r("</pre>");*/

//dump($_SERVER);
// sage($_SERVER);  //КАК подключить


//\App\Debug\Log::addLog('Открыта страница index.php');// кастомное логирование
Bitrix\Main\Diag\Debug::startTimeLabel('TestTime');

//Bitrix\Main\Diag\Debug::writeToFile($_SERVER,$varName = '$_SERVER  '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), $fileName = 'qwe');
//Bitrix\Main\Diag\Debug::dumpToFile($_SERVER,$varName = '$_SERVER  '. date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), $fileName = '');

$allTimezones = DateTimeZone::listIdentifiers();
print_r($allTimezones );
Bitrix\Main\Diag\Debug::endTimeLabel('TestTime');
Bitrix\Main\Diag\Debug::dump(Bitrix\Main\Diag\Debug::getTimeLabels('TestTime'));

$timeData = Bitrix\Main\Diag\Debug::getTimeLabels('TestTime');
echo "Время выполнения: " . ($timeData['TestTime']['time'])*1000 . " sec";