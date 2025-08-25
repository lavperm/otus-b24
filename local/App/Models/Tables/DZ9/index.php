<?php

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use App\Models\Tables\DZ9\ElementPropS20Table;  //Доктора
use App\Models\Tables\DZ9\QualificationTable; //Квалификация
// Получаем пользовательский список докторов и множественные свойства специализации
// и  одиночную специализация. + поле Name
// Получаем квалификацию из кастомной таблицы

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$entities =[
	QualificationTable::class,
	ElementPropS20Table::class,
];
foreach ($entities as $entity) {
	if (class_exists($entity)) {
		echo 'Класс загружен ' . $entity . '<br>';
		if(! Application::getConnection()->isTableExists($entity::getTableName())) {
			echo 'Таблица не найдена ' . $entity::getTableName() . '<br>';
			Base::getInstance($entity)->createDbTable();
		}
	} else {
		echo 'Класс не найден ' . $entity . '<br>';
	}
}

//Удаление таблицы
/*$connect = Application::getInstance()->getConnection();
if ($connect->isTableExists(DoktorQualificationTable::class::getTableName())) {
	$connect->dropTable(DoktorQualificationTable::class::getTableName());
	echo 'Таблица удаленна <br>';
}*/

/*$array = [
	['QUALIFICATION'=>'Вторая категория' , 'WORKING_EXPERIENCE'=>'3'],
	['QUALIFICATION'=>'Первая категория' , 'WORKING_EXPERIENCE'=>'5'],
	['QUALIFICATION'=>'Высшая категория' , 'WORKING_EXPERIENCE'=>'7'],
];
dump($entities[0]);

$result = $entities[0]::addMulti($array);
if ($result->isSuccess())
{
	echo "Тестовые данные созданы!";
}*/


$results =  ElementPropS20Table::query()
	->addSelect('*')
	->addSelect('SPEC_ONE.ELEMENT')
	->addSelect('SPEC_MULTI')
	->addSelect('QUALIF')
	//->addSelect('ELEMENT')
	//->where('IBLOCK_ELEMENT_ID', 106)
	//->where('QUALIF.WORKING_EXPERIENCE', 8)
	//->where('QUALIF.QUALIFICATION', 'Высшая категория')
	->fetchCollection();


$doctorsData =[];
FOREACH ($results as $result) {
	$sp_multi = [];
	//dump($result->get('SPEC_MULTI')->getAll());
foreach ($result->get('SPEC_MULTI')->getAll() as $item) {
	$name_= $item->getElement()?$item->getElement()->get('NAME'):'default_name';

	$sp_multi[]= [$item->getElement()?->get('NAME'),$item->get('PROPERTY_84')];

}
	$doctorsData[$result->get('IBLOCK_ELEMENT_ID')]=[
		'ФИО '=> $result->get('PROPERTY_79').' '.$result->get('PROPERTY_80').' '.$result->get('PROPERTY_81'),
		'Специализация одиночная ' => $result->get('SPEC_ONE')?->getElement()->get('NAME'),
		'Описание ' => $result->get('SPEC_ONE')?->get("PROPERTY_84"),
		'Специализация множественная ' => $sp_multi,
		'Квалификация' => $result->get('QUALIF')?->get('QUALIFICATION'),
		'Стаж мин. для переатистации' => $result->get('QUALIF')?->get('WORKING_EXPERIENCE'),
	];
}
dump($doctorsData);
// через запрос к таблице квалификация получаем докторов
$results_ =  QualificationTable::query()
	->addSelect('*')
	//->addSelect('EXPERIENCE')
	->addSelect('EXPERIENCE.SPEC_ONE')
	->addSelect('EXPERIENCE.SPEC_ONE.ELEMENT.*')
	->fetchCollection();

$experience = [];
foreach ($results_ as $result) {
$doc=[];

	foreach ($result->get('EXPERIENCE')->getAll() as $item) {
		$doc[$item->get('IBLOCK_ELEMENT_ID')] = [
			'ФИО '=> $item->get('PROPERTY_79').' '.$item->get('PROPERTY_80').' '.$item->get('PROPERTY_81'),
			'Специализация одиночная ' => $item->get('SPEC_ONE')?->getElement()->get('NAME'),
			'Описание ' => $item->get('SPEC_ONE')?->get("PROPERTY_84"),
			];
	}
	$experience[$result->get('QUALIFICATION')]= [$doc,
	];
}
dump($experience);