<?php

namespace app\models;
use yii\db\ActiveRecord;

/**
 * @property string $char_code
 * @property string $name_valute
 * 
 * @property Course $course
 */
class Valute extends ActiveRecord {
    public static function tableName()
    {
        return '{{%valute}}';
    }
    public function rules() 
    {
        return [
            [['char_code','name_valute'],'required'],
            ['char_code','string','length'=>3],
            ['name_valute','string','length'=>150],
            [['char_code'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['char_code' => 'course.char_code']],
        ];
    }

    public function getCourse() 
    {
        return $this->hasOne(Course::class,['char_code'=>'char_code']);
    }
}