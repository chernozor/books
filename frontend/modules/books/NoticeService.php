<?php

declare(strict_types=1);

namespace frontend\modules\books;

use Yii;

class NoticeService
{
    const NOTICE_EMAIL = 'emailNotice';
    const NOTICE_MAX = 'maxNotice';
    const NOTICE_SMS = 'smsNotice';

    public static function getNoticeListName()
    {
        return [
            self::NOTICE_EMAIL => Yii::t('app', 'Email'),
            self::NOTICE_MAX => Yii::t('app', 'MAX'),
            self::NOTICE_SMS => Yii::t('app', 'SMS'),
        ];
    }

//    public static function getBalance($noticeSystem)
//    {
//        $balance = Yii::$app->$noticeSystem->getBalance();
//        if ($balance) {
//            return $balance;
//        } else {
//            Yii::$app->session->setFlash('error', Yii::t('app', 'Что-то пошло не так'));
//            return false;
//        }
//    }

    public static function sendMessage($noticeSystem, array $userIds, string $message)
    {
        $sent = Yii::$app->$noticeSystem->sendMessage($userIds, $message);

        return $sent ? true : false;
    }
}
