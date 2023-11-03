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
        ];
    }


    
    public function convert():bool|float
    {
        if ($this->fromCourse
            && $this->toCourse 
            && $this->fromCourse != $this->toCourse
        ) 
        {
            if($this->fromCourse == "RUB"){
                $inRub = $this->val;
                $from = 1;
            } else {
                $from = Course::findOne(['char_code'=>$this->fromCourse]);
                $inRub = $from->vunit_rate*$this->val;
            }
                
            if ($from && $this->toCourse=="RUB") 
            {
                return round($inRub,2);
            } else {
                $to = Course::findOne(['char_code'=>$this->toCourse]);
            
                if ($to) {
                    $res = ($to->vunit_rate > 1)?$inRub / $to->vunit_rate : $inRub / round(1/$to->vunit_rate,2);
                    return round($res,2);
                } 
                
            }
            return false;
        
        }
        return false;
    }
    
}