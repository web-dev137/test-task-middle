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
        $xml = $this->checkConnect();
        if($xml instanceof ResponseInterface){
            $body = $xml->getBody();
            $xml = simplexml_load_string($body);
            $currencies = ($xml)?$xml->Valute:false;
        } else {
            $currencies = false;
        }
        return $currencies;
    }

    /**
     * Update courses and valutes table
     */
    public function updateCourses() 
    {
        $currencies = $this->parseXml();
        if($currencies) {
            foreach ($currencies as $valute) {

                $courseString = str_replace(
                    ',','.',$valute->VunitRate->__toString()
                );
                /*
                 * data for insert/update into course table
                */
                $fillCourses = [
                    'char_code' => $valute->CharCode->__toString(),//уникальное поле
                    'vunit_rate' => (double)$courseString
                ];
                /*
                 * data for insert/update into valute table
                */
                $fillValutes = [
                    'char_code' => $valute->CharCode->__toString(),//уникальное поле
                    'name_valute' => $valute->Name->__toString()
                ];
                Yii::$app->db->createCommand()->upsert(Course::tableName(),$fillCourses,$fillCourses)->execute();
                Yii::$app->db->createCommand()->upsert(Valute::tableName(),$fillValutes,$fillValutes)->execute();   
            }
            echo "success";
            return true;
        }
        echo "Can not parse";
        return  false;
    }

    /**
     * Checking whether the request is being executed
     * @param bool $hasError
     * @return ResponseInterface
     */
    private function checkConnect():ResponseInterface|bool
    {

        $client = new Client();
        try {
            $res = $client->request('GET',self::URL_RUB);
        } catch (ClientException $e) {
            $res = false;
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