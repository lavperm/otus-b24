<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle('Вывод связанных полей');

use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
Loader::includeModule('iblock');

if (!Loader::includeModule('iblock')) {
	die('Модуль «Инфоблоки» не установлен!');
}

echo 'Работает  ' . __DIR__ . PHP_EOL;
$iblockId = 16;
$iblockElementId = 42;

// Old API 
/*$arFilter = ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y'];
$arSelect = ['ID', 'NAME', 'CODE', 'PROPERTY_MODEL', 'PROPERTY_SITY_ID','PROPERTY_MANUFACTURER_ID','PROPERTY_PRODUCTION_DATE'];

$res = CIBlockElement::GetList([], $arFilter, false, [], $arSelect);

while($arFields = $res->fetch()){
	//dump($arFields);
    pr($arFields);
}*/


// создание новой записи в инфоблоке через CIBlockElement
$arElementProps = [
    'MODEL' => 'Collrey',
	'CITY_ID' => 32,
	'MANUFACTURER_ID' => 39   //MANUFACTERER_ID

];
$arIblockFields = [
    'IBLOCK_ID' => $iblockId,
    'NAME' => 'New element',
    'PROPERTY_VALUES' => $arElementProps,

];
$objIblockElement = new \CIBlockElement();
$objIblockElement->Add($arIblockFields);



// ORM

// ORM с использованием wakeUp
// wakeUp - метод позволяет сохранить в переменную элемент инфоблока с которым можно работать как с объектом ORM

// с параметром получает IBLOCK_ID
//get by id 
/*$iblock = Iblock::wakeUp($iblockId);
$element = $iblock->getEntityDataClass()::getByPrimary(  // get props
	$iblockElementId, 
	['select' => ['NAME','MODEL' ,'CITY_ID','CITY_ID.ELEMENT.NAME','CITY_ID.ELEMENT.ENGLISH']])
->fetchObject();

$name = $element->get('NAME');
echo PHP_EOL . 'NAME: ';
pr($name);

$model = $element->get('MODEL')->getValue();
echo 'MODEL: ';
pr($model);

$model = $element->get('CITY_ID')->getValue();
echo 'SITY_ID: ';

pr($model . ' ' . $element->get('CITY_ID')->getElement()->getName()
	. ' ' . $element->get('CITY_ID')->getElement()->get('ENGLISH')->getValue());*/







// ORM с использованием Element{код инфоблока}Table
// Element{код инфоблока}Table - метод позволяет сохранить в переменную коллекцию объектов (элементов инфоблока)
// get list
/*$elements = \Bitrix\Iblock\Elements\ElementCarTable::getList([ // car - cимвольный код API инфоблока
    'select' => ['MODEL','CITY_ID','CITY_ID.ELEMENT.NAME','CITY_ID.ELEMENT.ENGLISH'], // имя свойства
])->fetchCollection();

foreach ($elements as $element) {
    pr('MODEL - '.$element->getModel()->getValue()); // получение значения свойства MODEL
    pr('CITY_ID - '.$element->getCity_id()->getValue()); // получение значения свойства MODEL
    pr('CITY_name - '.$element->getCity_id()->getElement()->getName()); // получение значения свойства MODEL
    pr('CITY_us - '.$element->getCity_id()->getElement()->get('ENGLISH')->getValue()); // получение значения свойства MODEL

	//pr($element->sysGetEntity()); // Покажет структуру сущности
	//pr($element->collectValues()); // Покажет все значения

}*/



// query - метод позволяет строить более гибкие и сложные запросы для выборки данных данных через ORM
// получение через метод query списка элементов
/*$elements = \Bitrix\Iblock\Elements\ElementCarTable::query() // car - cимвольный код API инфоблока
    ->addSelect('NAME')
    ->addSelect('MODEL') // имя свойства 
    ->addSelect('ID')
	->addSelect('CITY_ID')
	->addSelect('MANUFACTURER_ID')
	->addSelect('CITY_ID.ELEMENT.NAME') // название связанного элемента
	->addSelect('CITY_ID.ELEMENT.ENGLISH') // свойство связанного элемента
    ->fetchCollection();

foreach ($elements as $key => $item) {
	pr($item->getName().' '.$item->getModel()->getValue() .
		' ' . $item->getCity_id()->getElement()->getName().
		' ' . $item->getCity_id()->getElement()->get('ENGLISH')->getValue()); // получение значения свойства MODEL

	 $value = $item->getModel()->getValue();
    if($value == 'Collrey'){
            $item->setModel('Collrey TEST'); // изменение значения свойства MODEL
            $item->save(); // сохранение данных
    }
}*/


// получение через query списка элементов
/*$elements = \Bitrix\Iblock\Elements\ElementCarTable::query() // car - cимвольный код API инфоблока
->addSelect('NAME')
	->addSelect('MODEL') // имя свойства
	->addSelect('ID')
	->fetchCollection();

foreach ($elements as $key => $item) {
	pr($item->getName().' '.$item->getModel()->getValue()); // получение значения свойства MODEL
	 $value = $item->getModel()->getValue();
	 if($value == 'Q7'){
	         $item->setModel('Q7 TEST'); // изменение значения свойства MODEL
	         $item->save(); // сохранение данных
	 }*/








// Получить свойства инфоблока
/*$dbIblockProps = \Bitrix\Iblock\PropertyTable::getList(array(
    'select' => array('*'),
    'filter' => array('IBLOCK_ID' =>$iblockId)
));
while ($arIblockProps = $dbIblockProps->fetch()){ 
    pr($arIblockProps);
}*/

// Получить список элементов инфоблока
/*$dbItems = \Bitrix\Iblock\ElementTable::getList(array(
    'select' => array('ID', 'NAME', 'IBLOCK_ID'),
    'filter' => array('IBLOCK_ID' => $iblockId)
));
$items = [];
//dump($dbItems);
while ($arItem = $dbItems->fetch()){
	//dump($arItem);
    $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID']
    );
    while($arProperty = $dbProperty->Fetch()){  
        $arItem['PROPERTIES'][] = $arProperty;
    }

    $items [] = $arItem;
}
pr($items);*/


// получение списка городов у элемента инфоблока Страна
$countryId = 73; // идентификатор процедуры из инфоблока Процедуры
$country = \Bitrix\Iblock\Elements\ElementCountryTable::getByPrimary($countryId, array(
    'select' => ['*', 'CITIES.ELEMENT.NAME', 'CITIES.ELEMENT.ENGLISH']
))->fetchObject();

foreach($country->getCities()->getAll() as $prItem) {
	dump($prItem);
    pr($prItem->getElement()->get('ENGLISH')->getValue().' '.$prItem->getElement()->getName());
}


// получение списка городов у элемента инфоблока car
/*$countryId = 40; // идентификатор процедуры из инфоблока Процедуры
$country = \Bitrix\Iblock\Elements\ElementCarTable::getByPrimary($countryId, array(
	'select' => ['*', 'CITY_ID.ELEMENT.NAME', 'CITY_ID.ELEMENT.ENGLISH']
))->fetchObject();
dump($country->getCity_id()->getElement()->getName());
pr($country->getCity_id()->getElement()->get('ENGLISH')->getValue().
	' '.$country->getCity_id()->getElement()->getName());*/










// получение списка городов у элемента инфоблока Страна
/*$countryId = $idddd; // идентификатор процедуры из инфоблока Процедуры
$country = \Bitrix\Iblock\Elements\ElementCityTable::getByPrimary($countryId, array(
	'select' => [ '*']
))->fetchObject();

dump($country->getName());
//dump($country);
foreach($country->getCity() as $prItem) {
	pr($prItem->getElement()->get('ENGLISH')->getValue().' '.$prItem->getElement()->getName());
}*/





//// // редактирование записей в БД
// \Bitrix\Main\Loader::IncludeModule("iblock");
// // делаем запрос на изменение поля NAME в записи с ID 36
// $res = \Bitrix\Iblock\Elements\ElementcarTable::update(36, array(
//     'NAME' => 'TEST 777',
// ));
//
//
//// удаление записи из БД
// $res = \Bitrix\Iblock\Elements\ElementcarTable::delete(44);
// pr($res);
