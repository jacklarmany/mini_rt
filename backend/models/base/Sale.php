<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\models\base;

use Yii;

/**
 * This is the base-model class for table "sale".
 *
 * @property integer $id
 * @property integer $table_id
 * @property integer $menu_id
 * @property integer $qty
 * @property string $price
 * @property string $amount
 * @property string $date
 * @property string $time
 * @property integer $user_id
 * @property integer $bill_no
 *
 * @property \backend\models\Menu $menu
 * @property \backend\models\Tables $table
 * @property string $aliasModel
 */
abstract class Sale extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_id', 'menu_id', 'qty', 'user_id', 'bill_no'], 'integer'],
            [['price', 'amount'], 'number'],
            [['date', 'time'], 'safe'],
            [['time'], 'required'],
            [['table_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Tables::className(), 'targetAttribute' => ['table_id' => 'id']],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Menu::className(), 'targetAttribute' => ['menu_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_id' => 'Table ID',
            'menu_id' => 'Menu ID',
            'qty' => 'Qty',
            'price' => 'Price',
            'amount' => 'Amount',
            'date' => 'Date',
            'time' => 'Time',
            'user_id' => 'User ID',
            'bill_no' => 'Bill No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(\backend\models\Menu::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTable()
    {
        return $this->hasOne(\backend\models\Tables::className(), ['id' => 'table_id']);
    }




}
