<?php
//специализация
namespace App\Models\Tables\DZ9;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;

class ElementPropS22Table extends DataManager //Специализация
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_s22';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'IBLOCK_ELEMENT_ID' => (new IntegerField('IBLOCK_ELEMENT_ID',	[]))
				->configurePrimary(true),
			'PROPERTY_84' => (new TextField('PROPERTY_84')),

			// Добавляем связь с основной таблицей элемента
			'ELEMENT' => (new ReferenceField('ELEMENT',
				\Bitrix\Iblock\ElementTable::class,
				Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
			))->configureTitle('Элемент инфоблока'),

		];
	}
}