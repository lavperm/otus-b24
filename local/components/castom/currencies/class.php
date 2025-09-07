<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Iblock;
use Models\Currencies\CurrenciesTable;

class CurrenciesComponent extends \CBitrixComponent
{
	protected $request;

	public function executeComponent()
	{
		$this->$request = Application::getInstance()->getContext()->getRequest();

		$res= CurrenciesTable::query()
			->addSelect("CURRENCY")
			->addSelect("AMOUNT")
			->addFilter('=CURRENCY' ,$this->arParams['CURRENCY'])
			->fetch();

		$this->arParams['VALUE_CURRENCY'] = $res['AMOUNT'];
		$this->includeComponentTemplate();
	}



}
