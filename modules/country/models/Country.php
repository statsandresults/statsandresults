<?php

namespace app\modules\country\models;

use Yii;

/**
 * This is the model class for table "app_country".
 *
 * @property integer $id
 * @property string $iso2
 * @property string $short_name
 * @property string $long_name
 * @property string $iso3
 * @property string $numcode
 * @property string $un_member
 * @property string $calling_code
 * @property string $cctld
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso2', 'short_name', 'long_name', 'iso3'], 'required'],
            [['iso2'], 'string', 'max' => 2],
            [['short_name'], 'string', 'max' => 100],
            [['long_name'], 'string', 'max' => 255],
            [['iso3'], 'string', 'max' => 3],
            [['numcode'], 'string', 'max' => 6],
            [['un_member'], 'string', 'max' => 12],
            [['calling_code'], 'string', 'max' => 8],
            [['cctld'], 'string', 'max' => 5],
            [['iso2'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('country', 'ID'),
            'iso2' => Yii::t('country', 'Iso2'),
            'short_name' => Yii::t('country', 'Short Name'),
            'long_name' => Yii::t('country', 'Long Name'),
            'iso3' => Yii::t('country', 'Iso3'),
            'numcode' => Yii::t('country', 'Numcode'),
            'un_member' => Yii::t('country', 'Un Member'),
            'calling_code' => Yii::t('country', 'Calling Code'),
            'cctld' => Yii::t('country', 'Cctld'),
        ];
    }
}
