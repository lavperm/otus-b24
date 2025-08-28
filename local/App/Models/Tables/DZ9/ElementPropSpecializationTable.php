<?php
//специализация
namespace App\Models\Tables\DZ9;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\Loader;
Loader::includeModule('iblock');
class ElementPropSpecializationTable extends DataManager //Специализация
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		$isLocation = $_SERVER['HTTP_HOST'] === '192.168.0.109:80';
		$TableName = $isLocation ? 'b_iblock_element_prop_s22' : 'b_iblock_element_prop_s18';
		return $TableName;
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		$isLocation = $_SERVER['HTTP_HOST'] === '192.168.0.109:80';
		$NotesProperty = $isLocation ? 'PROPERTY_84' : 'PROPERTY_70';

		return [
			'IBLOCK_ELEMENT_ID' => (new IntegerField('IBLOCK_ELEMENT_ID',	[]))
				->configurePrimary(true),
			'NOTES' => (new TextField($NotesProperty)),

			// Добавляем связь с основной таблицей элемента
			'ELEMENT' => (new ReferenceField('ELEMENT',
				\Bitrix\Iblock\ElementTable::class,
				Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
			))->configureTitle('Элемент инфоблока'),

		];
	}
}