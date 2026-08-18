<?php

declare(strict_types=1);

namespace frontend\modules\books\models\sms;

use Yii;
use Exception;
use common\models\User;
use frontend\modules\books\models\NoticeInterface;
use frontend\modules\books\exceptions\UnableToSendSmsException;

class SmsService implements NoticeInterface
{
    private string $_apiUrl;
    private string $_apiKey;

    public function __construct()
    {
        $this->_apiUrl = 'https://smspilot.ru/';
        $this->_apiKey = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';
    }

//    public function getBalance(): int
//    {
//        //mock
//        return 0;
//    }

    public function sendMessage(array $userIds, string $message)
    {
        $userPhones = User::find()->select('phone')->where(['in', 'id', $userIds])->asArray()->all();

        if ($userPhones) {
            $data = [];
            foreach ($userPhones as $count => $phone) {
                $data[] = ['id' => $count + 1, 'to' => $phone['phone'], 'text' => $message];
            }

            if ($data) {
                try {
                    $url = $this->_apiUrl . "api2.php";
                    $headers = [
                        'Content-Type: application/json; charset=utf-8',
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['apikey' => $this->_apiKey, 'from' => 'smsService', 'send' => $data]));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    return json_decode($response, true);

                } catch (Exception $e) {
                    return new UnableToSendSmsException(Yii::t('app', 'Не получилось отправить SMS'));
                }
            }
        }

        return true;
    }
}
