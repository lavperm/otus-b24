<?php

namespace App\Models\Tables\DZ9;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

class ElementPropS20Table extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_s20';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'IBLOCK_ELEMENT_ID' => (new IntegerField('IBLOCK_ELEMENT_ID',
				[]
			))->configureTitle('ID')
				->configurePrimary(true)
			,
			'PROPERTY_79' => (new TextField('PROPERTY_79',
				[]
			))->configureTitle('Фамилия')
			,
			'PROPERTY_80' => (new TextField('PROPERTY_80',
				[]
			))->configureTitle('Имя')
			,
			'PROPERTY_81' => (new TextField('PROPERTY_81',
				[]
			))->configureTitle('Отчество')
			,
			'PROPERTY_82' => (new TextField('PROPERTY_82',
				[]
			))->configureTitle('Процедуры (мн)')
			,
			'PROPERTY_83' => (new TextField('PROPERTY_83',
				[]
			))->configureTitle('Специлизация (мн)')
			,
			'PROPERTY_86' => (new IntegerField('PROPERTY_86',
				[]
			))->configureTitle('Специлизация')
			,
			'PROPERTY_89' => (new IntegerField('PROPERTY_89',
				[]
			))->configureTitle('Квалификация'),

			// Добавляем связь с основной таблицей элемента
			//Для вывода полей инфоблока
			/*'ELEMENT' => (new ReferenceField('ELEMENT',
				\Bitrix\Iblock\ElementTable::class,
				Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
			))->configureTitle('Элемент инфоблока'),*/

			// Добавляем связь с таблицей специализации
			//Связываем свойство PROPERTY_86 с элементом инфоблока 22
			(new Reference("SPEC_ONE",ElementPropS22Table::class,
				Join::on('this.PROPERTY_86', 'ref.IBLOCK_ELEMENT_ID',))),

			// Связь для множественных свойств (Доктор -> Специализации ElementPropS22Table  - свойство 83)
			(new ManyToMany('SPEC_MULTI', ElementPropS22Table::class))
				->configureTableName('b_iblock_element_prop_m20')
				->configureLocalPrimary('IBLOCK_ELEMENT_ID','IBLOCK_ELEMENT_ID')
				->configureRemotePrimary('IBLOCK_ELEMENT_ID','VALUE'),

			// Связь с таблицей кваливикация OneToMany
			(new Reference("QUALIF",QualificationTable::class,
				Join::on('this.PROPERTY_89', 'ref.ID',))),



		];
	}
}