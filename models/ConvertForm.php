<?php

namespace app\models;


use GuzzleHttp\Client;
use Yii;

use yii\base\Model;
use yii\htmlclient\XmlParser;
use keltstr\simplehtmldom\SimpleHTMLDom;


class ConvertForm extends Model
{
    public $fromCourse;
    public $toCourse;
    public $val;

    /**
     * @return array 
     */
    public function rules() 
    {
        return [
            [['fromCourse','toCourse','val'], 'required'],
            ['fromCourse', 'string','length' => 3],
            ['toCourse', 'string','length' => 3],
            ['val','number']
        ];
    }

    public function convert():bool|float
    {
        if ($this->fromCourse
            && $this->toCourse 
            && $this->fromCourse != $this->toCourse
        ) {
            $from = false;
            $to = false;
            $this->setFromTo($from,$to);
            if($from && $to) {
                $res = $this->val * $from/$to; //formula for convert valutes
                return round($res,2);
            }
        }
        return false;
    }

    /**
     * Setting variables "from" and "to"
     * @param float|bool $from
     * @param float|bool $to
     */
    private function setFromTo(&$from,&$to)
    {
        /**
         * if we convert value from rubls or to
         * then we shoould set value for rubls 1 or val value for 
         * correct work of formula
         */
        if($this->fromCourse == "RUB" || $this->toCourse == "RUB"){
            if($this->fromCourse == "RUB"){
                $from = $this->val;
                $to = Course::findOne(['char_code'=>$this->toCourse]);
                $to = ($to)?$to->vunit_rate:false;
            } else {
                $to = 1;
                $from = Course::findOne(['char_code'=>$this->fromCourse]);
                $from = ($from)?$from->vunit_rate:false;
            }
        } else {
            $from = Course::findOne(['char_code'=>$this->fromCourse]);
            $to = Course::findOne(['char_code'=>$this->toCourse]);
            $from = ($from)?$from->vunit_rate:false;
            $to = ($to)?$to->vunit_rate:false;
        }
    }
    
}