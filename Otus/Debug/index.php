<?php
require ($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');
$APPLICATION->SetTitle(title: "Новая Страница");
use \App\Debug\LogTest;

LogTest::addLog('Открыта стран111ица index.php');// кастомное логирование
Bitrix\Main\Diag\Debug::startTimeLabel('TestTime');

//Bitrix\Main\Diag\Debug::writeToFile($_SERVER,$varName = '$_SERVER  '.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), $fileName = 'qwe');
//Bitrix\Main\Diag\Debug::dumpToFile($_SERVER,$varName = '$_SERVER  '. date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), $fileName = '');

$allTimezones = DateTimeZone::listIdentifiers();
print_r($allTimezones );
Bitrix\Main\Diag\Debug::endTimeLabel('TestTime');
Bitrix\Main\Diag\Debug::dump(Bitrix\Main\Diag\Debug::getTimeLabels('TestTime'));

$timeData = Bitrix\Main\Diag\Debug::getTimeLabels('TestTime');
echo "Время выполнения: " . ($timeData['TestTime']['time'])*1000 . " sec";