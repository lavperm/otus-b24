<?php
global $APPLICATION;

require ($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');
$APPLICATION->SetTitle('Курс волют ДЗ №5');

?>

<?php
$APPLICATION->IncludeComponent(
	"castom:currencies",
	".default",
	[

		'CURRENCY' => 'RUB',

	],
	false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

