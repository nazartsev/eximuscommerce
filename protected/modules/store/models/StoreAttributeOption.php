<?php

/**
 * Store options for dropdown and multiple select
 * This is the model class for table "StoreAttributeOptions".
 *
 * The followings are the available columns in table 'StoreAttributeOptions':
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 * @property integer $position
 */
class StoreAttributeOption extends BaseModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'StoreAttributeOption';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'eavData'=>array(self::HAS_MANY, 'StoreProductAttributeEAV', 'value'),
			'products'=>array(self::HAS_MANY, 'StoreProduct', array('entity'),'through'=>'eavData'),
			'productsCount'=>array(self::STAT, 'StoreProductAttributeEAV', 'value'),
//			'productsCount'=>array(self::STAT, 'StoreProductAttributeEAV', 'value'),
		);
	}

	public function afterDelete()
	{
		return parent::afterDelete();
	}
}