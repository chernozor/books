<?php

declare(strict_types=1);

namespace frontend\modules\books\models;

interface NoticeInterface
{
//    public function getBalance();

    public function sendMessage(array $userIds, string $message);
}
