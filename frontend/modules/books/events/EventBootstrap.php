<?php

declare(strict_types=1);

namespace frontend\modules\books\events;

use Yii;
use Exception;
use yii\base\Event;
use yii\base\Component;
use yii\base\BootstrapInterface;
use frontend\modules\books\models\Book;
use frontend\modules\books\handlers\NewBookEventHandler;

class EventBootstrap extends Component implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (Yii::$app->hasModule('books')) {
            $this->authors($app);
        }
    }

    public function authors($app)
    {
        try {
            Event::on(Book::class, Book::EVENT_BOOK_CREATE, [NewBookEventHandler::class, 'run']);
        } catch (Exception $e) {
            Yii::error("Code: {$e->getCode()}\nMessage: {$e->getMessage()}\n");
        }
    }
}
