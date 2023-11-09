<?php

namespace app\models;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Yii;
use yii\db\ActiveRecord;


/**
 * @property string $char_code
 * @property float $vunit_rate
 * 
 * @property Valute $valute
 */
class Course extends ActiveRecord 
{
    const URL_RUB = "http://www.cbr.ru/scripts/XML_daily.asp";
    const URL_THB = "https://apigw1.bot.or.th/bot/public/Stat-ExchangeRate/v2/DAILY_AVG_EXG_RATE/?start_period=2023-10-25&end_period=2023-10-25&currency=RUB";

    public static function tableName()
    {
        return '{{%course}}';
    }
    public function rules()
    {
        return [
            [['char_code','vunit_rate'],'required'],
            ['char_code','string','length' => 3],
            ['vunit_rate','number'],
            [['char_code'], 'exist', 'skipOnError' => true, 
            'targetClass' => Valute::class, 
            'targetAttribute' => ['char_code' => 'valute.char_code']
            ],
        ];
    }

    public function getValute() 
    {
        return $this->hasOne(Valute::class,['char_code'=>'char_code']);
    }

    /**
     * Parse for table course and valute
     * @return \SimpleXMLElement|bool
     */
    private function parseXml():\SimpleXMLElement|bool
    {
        $xml = $this->checkConnect($hasError);
        if(!$hasError){
            $body = $xml->getBody();
            $xml = simplexml_load_string($body);
            $valutes = ($xml)?$xml->Valute:false;
        } else {
            $valutes = false;
        }
        return $valutes;
    }

    /**
     * Update courses and valutes table
     */
    public function updateCourses() 
    {
        $valutes = $this->parseXml();
        $fillCourses = []; //data for insert/update into course table
        $fillValutes = []; //data for insert/update into valute table
        if($valutes) {
            foreach ($valutes as $valute) {

                $courseString = str_replace(
                    ',','.',$valute->VunitRate->__toString()
                );
                $fillCourses = [
                    'char_code' => $valute->CharCode->__toString(),//уникальное поле
                    'vunit_rate' => (double)$courseString
                ];
                $fillValutes = [
                    'char_code' => $valute->CharCode->__toString(),//уникальное поле
                    'name_valute' => $valute->Name->__toString()
                ];
                Yii::$app->db->createCommand()->upsert(Course::tableName(),$fillCourses,$fillCourses)->execute();
                Yii::$app->db->createCommand()->upsert(Valute::tableName(),$fillValutes,$fillValutes)->execute();   
            }
            echo "success";
        }
       echo "Can not parse";
    }

    /**
     * Checking whether the request is being executed
     * @param bool $hasError
     * @return ResponseInterface
     */
    private function checkConnect(&$hasError):ResponseInterface
    {
        $hasError = false;
        $client = new Client();
        try {
            $res = $client->request('GET',self::URL_RUB);
        } catch (ClientException $e) {
            $hasError = true;
            $response = $e->getResponse();
            switch ($response->getStatusCode()) {
                case 403:
                   echo "Forbidden";
                   break;
                case 404:
                    echo "Not Found";
                    break;
                case 500:    
                    echo "Inner error";
                    break;
                default:
                echo "Unknown error";
                break;
            }
           
        }
        return $res;
    }
}