<?php

declare(strict_types=1);

namespace frontend\modules\books\handlers;

use Yii;
use Exception;
use yii\base\Event;
use yii\web\Application;
use frontend\modules\books\models\Book;
use frontend\modules\books\NoticeService;
use frontend\modules\books\models\Author;

class NewBookEventHandler
{
    static public function run(Event $event)
    {
        if (Yii::$app instanceof Application) {
            /** @var Book $book */
            $book = $event->sender;

            try {
                NoticeService::sendMessage(NoticeService::NOTICE_SMS, $book->subs, self::addMessage($book));
            } catch (Exception $e) {
                //todo mock
            }
        }
    }

    static private function addMessage(Book $book): string
    {
        $authors = '';
        /** @var $author Author */
        foreach ($book->authors as $author) {
            $authors .= "$author->first_name $author->last_name,";
        }
        return Yii::t('app', 'Под авторством: {author} новое поступление: {book}', [
            'book' => $book->name,
            'author' => $authors,
        ]);
    }
}

