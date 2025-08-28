<?php

namespace App\Models\Tables\DZ9;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

class QualificationTable extends DataManager //Квалификация
{
	public static function getTableName(){
		return 'l_qualification';
	}

	public static function getMap(){
		return [
			(new IntegerField('ID'))
				->configurePrimary()
				->configureAutocomplete()
			,
			'QUALIFICATION' => (new TextField('QUALIFICATION',
				[]
			))->configureTitle('Квалификация')
			,
			'WORKING_EXPERIENCE' => (new IntegerField('WORKING_EXPERIENCE',
				[]
			))->configureTitle('Стаж работы')
			,
			//  получить по квалификации -> докторов  (new OneToMany)
			(new OneToMany('EXPERIENCE',ElementPropDoctorTable::class, 'QUALIF'))
			->configureJoinType('INNER'),


		];
	}
}