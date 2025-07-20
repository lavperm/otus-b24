<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle('Вывод связанных полей');

use Models\Lists\CarsPropertyValuesTable as CarsTable;


// вывод данных по списку записей из инфоблока Автомобили
/*$cars = CarsTable::getList([
		'select'=>[
          'ID'=>'IBLOCK_ELEMENT_ID',
          'NAME'=>'ELEMENT.NAME',
 		  'MANUFACTURER_ID'=>'MANUFACTURER_ID'

      ]
  ])->fetchAll();

 pr($cars);*/

$cars = CarsTable::query()
    ->setSelect([
        '*',
        'NAME' => 'ELEMENT.NAME',
        'MANUFACTURER_NAME' => 'MANUFACTURER.ELEMENT.NAME',
        'CITY_NAME' => 'CITY.ELEMENT.NAME',
        'COUNTRY' => 'MANUFACTURER.COUNTRU',
    ])
    ->setOrder([]) //['COUNTRY' => 'desc']
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'MANUFACTURER',
            \Models\Lists\CarManufacturerPropertyValuesTable::getEntity(),
            ['=this.MANUFACTURER_ID' => 'ref.IBLOCK_ELEMENT_ID'],

        )
    )

    ->fetchAll();

pr($cars);


// добавление данных  записей в инфоблок Автомобили
/*$dbResult = CarsTable::add([
        'NAME'=>'TEST',
        'MANUFACTURER_ID'=>33,
        'CITY_ID'=>30,
        'MODEL'=>'X5',
        'ENGINE_VOLUME'=>'4',
        'PRODUCTION_DATE'=>date('d.m.Y'),
]);
pr($dbResult);*/

