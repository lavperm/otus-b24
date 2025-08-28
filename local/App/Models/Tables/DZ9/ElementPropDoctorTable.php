<?php

namespace App\Models\Tables\DZ9;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

class ElementPropDoctorTable extends DataManager
{
	private static $NameTableOne;
	private static $NameTableMulti;

	/*private static $IbDoctorTableName=[];
	const DOCTORS_NAME_CODE = 'DOCTORS';
	private static function getDoctorTableName()
	{

		if (!empty(self::$IbDoctorTableName)) {
			return self::$IbDoctorTableName;
		}
			$idIbDoctor = \Bitrix\Iblock\IblockTable::getList(
				['filter' => ['CODE' => self::DOCTORS_NAME_CODE]]
			)->fetch()['ID'];

			if ( !$idIbDoctor ) {
				throw new \Exception('Инфоблок с кодом "' . self::DOCTORS_NAME_CODE . '" не найден');
			}
			self::$IbDoctorTableName =[
				'One' => 'b_iblock_element_prop_s'.$idIbDoctor,
				'Multi' => 'b_iblock_element_prop_m'.$idIbDoctor];

			$connect = Application::getInstance()->getConnection();
				if (!$connect->isTableExists(self::$IbDoctorTableName['One'])) {
					throw new \Exception('Таблица " ' . self::$IbDoctorTableName['One']. ' " не найден');
				}
				if (!$connect->isTableExists(self::$IbDoctorTableName['Multi'])) {
					throw new \Exception('Таблица " ' . self::$IbDoctorTableName['Multi']. ' " не найден');
				}

	return self::$IbDoctorTableName ;
	}
*/

	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		$isLocation = $_SERVER['HTTP_HOST'] === '192.168.0.109:80';
		self::$NameTableOne = $isLocation ? 'b_iblock_element_prop_s20' : 'b_iblock_element_prop_s16';
		self::$NameTableMulti = $isLocation ? 'b_iblock_element_prop_m20' : 'b_iblock_element_prop_m16';
		return self::$NameTableOne;
		//return self::getDoctorTableName()['One']; //'b_iblock_element_prop_s20';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		$isLocation = $_SERVER['HTTP_HOST'] === '192.168.0.109:80';

		$lastNameProperty = $isLocation ? 'PROPERTY_79' : 'PROPERTY_64';
		$firstNameProperty = $isLocation ? 'PROPERTY_80' : 'PROPERTY_65';
		$middleNameProperty = $isLocation ? 'PROPERTY_81' : 'PROPERTY_66';
		$ProceduresMultiProperty = $isLocation ? 'PROPERTY_82' : 'PROPERTY_67';
		$SpecializationMultiProperty = $isLocation ? 'PROPERTY_83' : 'PROPERTY_68';
		$SpecializationOneProperty = $isLocation ? 'PROPERTY_86' : 'PROPERTY_69';
		$QualificationProperty = $isLocation ? 'PROPERTY_89' : 'PROPERTY_73';

		return [
			'IBLOCK_ELEMENT_ID' => (new IntegerField('IBLOCK_ELEMENT_ID',
				[]
			))->configureTitle('ID')
				->configurePrimary(true)
			,
			'$LAST_NAME' => (new TextField($lastNameProperty,
				[]
			))->configureTitle('Фамилия')
			,
			'FIRST_NAME' => (new TextField($firstNameProperty,
				[]
			))->configureTitle('Имя')
			,
			'MIDDLE_NAME' => (new TextField($middleNameProperty,
				[]
			))->configureTitle('Отчество')
			,
			'PROCEDURES_MULTI' => (new TextField($ProceduresMultiProperty,
				[]
			))->configureTitle('Процедуры (мн)')
			,
			'SPECIALIZATION_MULTI' => (new TextField($SpecializationMultiProperty,
				[]
			))->configureTitle('Специлизация (мн)')
			,
			'SPECIALIZATION_ONE' => (new IntegerField($SpecializationOneProperty,
				[]
			))->configureTitle('Специлизация')
			,
			'QUALIFICATION' => (new IntegerField($QualificationProperty,
				[]
			))->configureTitle('Квалификация'),

			// Добавляем связь с основной таблицей элемента
			//Для вывода полей инфоблока
			'ELEMENT' => (new ReferenceField('ELEMENT',
				\Bitrix\Iblock\ElementTable::class,
				Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
			))->configureTitle('Элемент инфоблока'),

			// Добавляем связь с таблицей специализации
			//Связываем свойство PROPERTY_86 с элементом инфоблока СПЕЦИАЛИЗАЦИИ
			(new Reference("SPEC_ONE",ElementPropSpecializationTable::class,
				Join::on('this.SPECIALIZATION_ONE', 'ref.IBLOCK_ELEMENT_ID',))),

			// Связь для множественных свойств (Доктор -> Специализации ElementPropS22Table  - свойство 83)
			(new ManyToMany('SPEC_MULTI', ElementPropSpecializationTable::class))
				->configureTableName(self::$NameTableMulti)
				->configureLocalPrimary('IBLOCK_ELEMENT_ID','IBLOCK_ELEMENT_ID')
				->configureRemotePrimary('IBLOCK_ELEMENT_ID','VALUE'),

			// Связь с таблицей кваливикация OneToMany
			(new Reference("QUALIF",QualificationTable::class,
				Join::on('this.QUALIFICATION', 'ref.ID',))),



		];
	}
}