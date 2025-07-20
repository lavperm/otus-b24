<?php
require ($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');

$APPLICATION->SetTitle(title: "Домашнее задание");
use Bitrix\Main\Diag\Debug;

const DEFAULT_PATH_FILE_LOG = '/Otus/logs/';
const DEFAULT_FILE_NAME = 'Custom.log';

$logFile = DEFAULT_PATH_FILE_LOG . DEFAULT_FILE_NAME;
$Separator = '-------Домашнее задание №2-1-------------';
$log_text = date('Y-m-d H:i:s') ;
Debug::writeToFile($log_text,$Separator, $fileName = $logFile);
//----------------------------------------------------------------


$val = 0;
$eee= 10/$val;

//throw new Exception('Тестовая ошибка',666);


