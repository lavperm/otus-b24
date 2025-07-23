<?php
require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle('Список врачей');
Bitrix\Main\Loader::includeModule('iblock');


use App\Models\Doctors\DoctorPropertyValuesTable as DoctorsTable;
use Bitrix\Main\Entity\ReferenceField as refField;

$doctors = DoctorsTable::query()
	->setSelect([
		'*',
		'PROC.ELEMENT',  //Процедуры множественные
		'SPEC.ELEMENT',  // Одиночная специализация
		'SPEC_M.ELEMENT' // множественные специализации
])

	->registerRuntimeField(
		null,
		new Bitrix\Main\Entity\ReferenceField(
			'PROC',
			\App\Models\Doctors\DoctorProcedurePropertyValueTable::class,
			['=this.PROCEDURES_MULTI'=> 'ref.IBLOCK_ELEMENT_ID'],
			['join_type' => 'LEFT']
		)
	)
	->registerRuntimeField(
		null,
		new refField(
			'SPEC',
			\App\Models\Doctors\DoctorSpecializationPropertyValuesTable::class,
			['=this.SPECIALIZATION_ONE'=> 'ref.IBLOCK_ELEMENT_ID'],

		)
	)
	->registerRuntimeField(
		null,
		new refField(
			'SPEC_M',
			\App\Models\Doctors\DoctorSpecializationPropertyValuesTable::class,
			['=this.SPECIALIZATION_MULTI'=> 'ref.IBLOCK_ELEMENT_ID'],
			['join_type' => 'LEFT']
		)
	)

//->fetchAll();
//pr($doctors);
	->fetchCollection();


foreach ($doctors as $doctor) {

	$value = $doctor->get('SPEC_M');

	if ($value instanceof \Bitrix\Main\ORM\Objectify\Collection) { //проверяет, принадлежит ли объект к определенному классу, является ли он экземпляром этого класса или его наследником
		echo ' Множественное значение!';
		foreach ($value->getAll() as $item) {
			// Обработка каждого значения
		}
	} else {
		echo ' Одиночное значение! ';
	}



	echo '<div class="doctor">';
	echo '<h3>' . $doctor->get('DOCTOR_FAMILE_NAME') . ' '
		. $doctor->get('DOCTOR_NAME') . ' '
		. $doctor->get('DOCTOR_OTCESTVO') . '</h3>';

	// Одиночная процедура
	if ($procedure = $doctor->get('PROC')) {
		echo '<p>Процедура: ' . $procedure->getElement()->getName() . ' (ID: ' . $procedure->getElement()->getId() . ')</p>';
	}

	// Одиночная специализация
	if ($specSingle = $doctor->get('SPEC')) {
		echo '<p>Основная специализация: ' . $specSingle->getElement()->getName() . ' (ID: ' . $specSingle->getElement()->getId() . ')</p>';
	}

	// Множественная специализация
	if ($specSingle = $doctor->get('SPEC_M')) {
		echo '<p> Множественная специализация: выводим как одиночную ' . $specSingle->getElement()->getName() . ' (ID: ' . $specSingle->getElement()->getId() . ')</p>';
	}

	//dump($doctor);
	// Для множественного поля (SPEC_M)
	$specializations = [];
	foreach ($doctor->get('SPEC_M') as $specM) {
		dump($specM);
		$specializations[] = $specM->getElement()?->getName();

	}

	dump($specializations);




dump($doctor->get('SPEC_M')->getElement()->getAll());
	// Множественные специализации
	if ($specMRelation = $doctor->get('SPEC_M')) {
		$specializations = $specMRelation->getAll();

		if (!empty($specializations)) {
			echo '<p>Дополнительные специализации:';
			echo '<ul>';
			foreach ($specializations as $spec) {
				$element = $spec->getElement();
				echo '<li>' . $element->getName() . ' (ID: ' . $element->getId() . '</li>';
			}
			echo '</ul></p>';
		}
	}

	echo '</div><hr>';
}
