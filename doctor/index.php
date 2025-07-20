<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global $APPLICATION */
$APPLICATION->SetTitle('Врачи');
$APPLICATION->SetAdditionalCSS('/doctor/style.css');
use Bitrix\Iblock\IblockTable;

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
	. "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";

dump($_GET);
dump($_POST);
// Action = 1 Просмотр
// Action = 2 Редактировать   (не сделано)
// Action = 3 Добавить
// Action = 4 Удалить

if (isset($_GET['idDoctor']) && isset($_GET['Action'])  ) {
	//Если параметры пареданны выводим конкретного врача
	$docId = htmlspecialchars((int)$_GET['idDoctor']);
	$ActionDoctor = (int)$_GET['Action'];
}

if (isset($_POST['firstname']) && isset($_POST['doctor_name']) &&
	isset($_POST['doctor_otcestvo']) && isset($_POST['specilizaciy']) && isset($_POST['procedures'])
) {
	//Если параметры пареданны создаем врача
	$iblockCodeName = 'DOCTORS'; // символьный код инфоблока
$dId = IblockTable::getList([
	'filter' => ['CODE' => $iblockCodeName],
	'select' => ['ID'],
])->fetch();

if ($dId) {
	$iblockId = $dId['ID'];
} else {
	echo 'Инфоблок ' . $iblockCodeName . ' не найден! <br>';
	return;
}

	$arElementProps = [
		'DOCTOR_FAMILE_NAME' => $_POST['firstname'],
		'DOCTOR_NAME' => $_POST['doctor_name'],
		'DOCTOR_OTCESTVO' => $_POST['doctor_otcestvo'],
		'SPECIALIZATION_ONE' => $_POST['specilizaciy'],
		'PROCEDURES_MULTI' => $_POST['procedures']
	];
	$arIblockFields = [
		'IBLOCK_ID' => $iblockId,
		'NAME' => $_POST['firstname'],
		'PROPERTY_VALUES' => $arElementProps,
	];
	$objIblockElement = new \CIBlockElement();
	$objIblockElement->Add($arIblockFields);
}


//Добавить врача
if ( $ActionDoctor==3 ) {

	$procedures = \Bitrix\Iblock\Elements\ElementProcedureTable::getList([ // получение списка процедур у врачей
		'select' => ['ID','NAME',],
		'filter' => ['ACTIVE' => 'Y',],
	])
		->fetchCollection();

	$specilizaciy = \Bitrix\Iblock\Elements\ElementSpecializationTable::getList([ // получение списка процедур у врачей
		'select' => ['ID','NAME',],
		'filter' => ['ACTIVE' => 'Y',],
	])
		->fetchCollection();


	?>
	<h2>Добавить доктора</h2>
<form action="input.php" method="POST">
<p>Фамилия:<br>
<input type="text" name="firstname" /></p>
	<p>Имя:<br>
		<input type="text" name="doctor_name" /></p>
	<p>Отчество:<br>
		<input type="text" name="doctor_otcestvo" /></p>
	<p>Выберите специлизацию:<br>
	<select name="specilizaciy" size="1">
		<?php foreach ($specilizaciy as $spec) :	?>
			<option value=<?= $spec->getId() ?>><?= $spec->getName() ?></option>
		<?php endforeach; ?>
	</select>

<p>Выберите процедуры: <br>
<select name="procedures[]" size="10" multiple="multiple">
	<?php foreach ($procedures as $procedur) :	?>
    <option value=<?= $procedur->getId() ?>><?= $procedur->getName() ?></option>
	<?php endforeach; ?>
</select></p>

<input type="submit" value="Создать">
</form>
<?php

}


//Удалить врача
if ($docId && $ActionDoctor==4 ) {
	echo 'Удаление';
	 \Bitrix\Iblock\Elements\ElementDoctorsTable::delete($docId);
	header("Refresh: 1; url=" . $baseUrl);
	exit();
}

//Выводим всех врачей
if (!$docId && !$ActionDoctor  ) {
	$doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([ // получение списка процедур у врачей
		'select' => [
			'ID',
			'NAME',
			'PROCEDURES_MULTI.ELEMENT.NAME',
			//'SPECIALIZATION_MULTI.ELEMENT.NAME',
			//'SPECIALIZATION_MULTI.ELEMENT.NOTES',
			'SPECIALIZATION_ONE.ELEMENT.NAME'
		], 'count_total' => true,
		'filter' => [
			//'ID' => $docId,
			'ACTIVE' => 'Y',
		],
	])
		->fetchCollection();
	?>

	<div  class="two-columns-60-40" >
		<div class="cards-list">
			<a href=<?=$baseUrl . '?idDoctor=0' . '&Action=3' ?> class="add-buttons button">Добавить врача</a>

		</div>

		<div class="cards-list">
			<?php
			foreach ($doctors as $doctor) :
				?>
				<a href=<?=$baseUrl . '?idDoctor='. $doctor->getId() . '&Action=0' ?> class="link-container">
					<div class="card">
						<h1><?= htmlspecialchars($doctor->getName()) ?></h1>
						<p><?= htmlspecialchars($doctor->get('SPECIALIZATION_ONE')->getElement()->getName()) ?></p>
					</div>
				</a>
			<?php endforeach; ?>

		</div>
	</div>
	<?php

}


//Выводим конкретного врача
if ($docId && !$ActionDoctor  ) {
	$doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([ // получение списка процедур у врачей
		'select' => [
			'ID',
			'NAME',
			'DOCTOR_NAME','DOCTOR_OTCESTVO',
			'PROCEDURES_MULTI.ELEMENT.NAME',
			//'SPECIALIZATION_MULTI.ELEMENT.NAME',
			//'SPECIALIZATION_MULTI.ELEMENT.NOTES',
			'SPECIALIZATION_ONE.ELEMENT.NAME'
		], 'count_total' => true,
		'filter' => [
			'ID' => $docId,
			'ACTIVE' => 'Y',
		],
	])
		->fetchCollection();


	?>

	<div  class="two-columns-60-40" >
		<div class="cards-list">
			<a href=<?=$baseUrl . '?idDoctor='. $docId . '&Action=4' ?> class="add-buttons button">Удалить врача</a>
			<a href=<?=$baseUrl . '?idDoctor='. $docId . '&Action=2' ?> class="add-buttons button">Редактировать врача</a>
		</div>

		<div >
			<?php foreach ($doctors as $doctor) : ?>
				<a href=<?=$baseUrl  ?> class="link-container">
					<div >
						<h1><?= htmlspecialchars($doctor->getName(). ' ' .
								$doctor->get('DOCTOR_NAME')->getValue() . ' ' .
								$doctor->get('DOCTOR_OTCESTVO')->getValue()
							) ?></h1>
						<p><?= htmlspecialchars($doctor->get('SPECIALIZATION_ONE')->getElement()->getName()) ?></p>

						<?php foreach($doctor->get('PROCEDURES_MULTI')->getAll() as $prItem) :
						?>

							<h2><?= ($prItem->getId().' - '.$prItem->getElement()->getName()) ?></h2>
						<?php endforeach; ?>
					</div>
				</a>
			<?php endforeach; ?>

		</div>
	</div>
	<?php

}


/*
	$doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([ // получение списка процедур у врачей
		'select' => [
			'ID',
			'NAME',
			'PROCEDURES_MULTI.ELEMENT.NAME',
			'SPECIALIZATION_MULTI.ELEMENT.NAME',
			'SPECIALIZATION_MULTI.ELEMENT.NOTES',
			'SPECIALIZATION_ONE.ELEMENT.NAME'
		], 'count_total' => true,
		'filter' => [
			'ID' => $docId,
			'ACTIVE' => 'Y',
		],
	])
		->fetchCollection();*/


	//Если параметры не переданны выводим всех врачей





/*$iblockCodeName = 'DOCTORS'; // символьный код инфоблока
$dId = IblockTable::getList([
	'filter' => ['CODE' => $iblockCodeName],
	'select' => ['ID'],
])->fetch();

if ($dId) {
	$docId = $dId['ID'];
} else {
	echo 'Инфоблок ' . $iblockCodeName . ' не найден! <br>';
	return;
}*/

//$docId=101;





/*//количество элементов в списке
$countElement = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([
	'select' => ['ID'],	'count_total' => true,	]);
echo 'Элементов ' . $countElement->getCount() . '<br>';*/

//dump($doctors);
//echo count($doctors);

/*foreach ($doctors as $doctor) {
    pr($doctor->getName().' - - -');
    foreach($doctor->get('PROCEDURES_MULTI')->getAll() as $prItem) {
        // получаем значение у процедуры 
        //if($prItem->getElement()->getDescription()!== null){
            pr($prItem->getId().' - '.$prItem->getElement()->getName());
        }
	pr('Специлизация');
	foreach($doctor->get('SPECIALIZATION_MULTI')->getAll() as $prItem) {
		// получаем значение у процедуры
		//if($prItem->getElement()->getDescription()!== null){
		pr($prItem->getId().' - '.$prItem->getElement()->getName() . '<br>' . $prItem->getElement()->get('NOTES')->getValue());
		//dump($prItem);
	}
}*/




?>

