<?php

/**
 * Delivery method sform
 */
return array(
	'id'=>'deliveryUpdateForm',
	'elements'=>array(
		'tab1'=>array(
			'type'=>'form',
			'title'=>'',
			'elements'=>array(
				'name'=>array(
					'type'=>'text',
				),
				'active'=>array(
					'type'=>'dropdownlist',
					'items'=>array(
						1=>Yii::t('StoreModule.admin', 'Да'),
						0=>Yii::t('StoreModule.admin', 'Нет')
					),
				),
				'description'=>array(
					'type'=>'textarea',
				),
				'position'=>array(
					'type'=>'text',
					'hint'=>Yii::t('StoreModule.admin', 'Оставьте пустым для установки максимального значения')
				),
			),
		),
	),
);