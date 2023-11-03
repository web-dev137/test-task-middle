<?php

namespace app\models;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Yii;
use yii\db\ActiveRecord;


/**
 * @property int $id 
 * @property string $char_code
 * @property float $vunit_rate
 */
class Course extends ActiveRecord 
{
    const URL_RUB = "http://www.cbr.ru/scripts/XML_daily.asp";
    const URL_THB = "https://apigw1.bot.or.th/bot/public/Stat-ExchangeRate/v2/DAILY_AVG_EXG_RATE/?start_period=2023-10-25&end_period=2023-10-25&currency=RUB";


    const VALUTES = [
        'AUD' => 'Австралийский доллар',
        'AZN' => 'Азербайджанский манат',
        'GBP' => 'Фунт стерлингов Соединенного королевства',
        'AMD' => 'Армянских драмов',
        'BYN' => 'Белорусский рубль',
        'BGN' => 'Болгарский лев',
        'BRL' => 'Бразильский реал',
        'HUF' => 'Венгерских форинтов',
        'VND' => 'Вьетнамских донгов',
        'HKD' => 'Гонконгский доллар',
        'GEL' => 'Грузинский лари',
        'DKK' => 'Датская крона',
        'AED' => 'Дирхам ОАЭ',
        'USD' => 'Доллар США',
        'EUR' => 'Евро',
        'EGP' => 'Египетских фунтов',
        'INR' => 'Индийских рупий',
        'IDR' => 'Индонезийских рупий',
        'KZT' => 'Казахстанских тенге',
        'CAD' => 'Канадский доллар',
        'QAR' => 'Катарский риал',
        'KGS' => 'Киргизских сомов',
        'CNY' => 'Китайский юань',
        'MDL' => 'Молдавских леев',
        'NZD' => 'Новозеландский доллар',
        'NOK' => 'Норвежских крон',
        'PLN' => 'Польский злотый',
        'RON' => 'Румынский лей',
        'RUB' => 'Российский рубль',
        'XDR' => 'СДР (специальные права заимствования)',
        'SGD' => 'Сингапурский доллар',
        'TJS' => 'Таджикских сомони',
        'THB' => 'Таиландских батов',
        'TRY' => 'Турецких лир',
        'TMT' => 'Новый туркменский манат',
        'UZS' => 'Узбекских сумов',
        'UAH' => 'Украинских гривен',
        'CZK' => 'Чешских крон',
        'SEK' => 'Шведских крон',
        'CHF' => 'Швейцарский франк',
        'RSD' => 'Сербских динаров',
        'ZAR' => 'Южноафриканских рэндов',
        'KRW' => 'Вон Республики Корея',
        'JPY' => 'Японских иен'
    ];
    public function rules()
    {
        return [
            [['char_code','vunit_rate'],'required'],
            ['char_code','string','length' => 3],
            ['vunit_rate','number']
        ];
    }

    public function updateCourses() 
    {
        $client = new Client();
        $hasError = false;
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
        if(!$hasError){
            $body = $res->getBody();
            
            $xml = simplexml_load_string($body);
            
            $valutes = $xml->Valute;
            
            foreach ($valutes as $valute) {
                $course = Course::findOne(['char_code'=>$valute->CharCode->__toString()]);
                if($course) {
                    $courseString = str_replace(',','.',$valute->VunitRate->__toString());
                    $course->vunit_rate = (double)$courseString; 
                } else {
                    $courseString = str_replace(',','.',$valute->VunitRate->__toString());
                    $course = new Course([
                        'char_code' => $valute->CharCode->__toString(),
                        'vunit_rate' => (double)$courseString
                    ]);
                }
            
                if($course->validate())$course->save();
                    else Yii::info($course->errors);
            }
            echo "success";
            return true;
        }
       
    }
}