<?php

declare(strict_types=1);

namespace frontend\modules\books\controllers;

use Yii;
use Exception;
use frontend\modules\books\models\Subs;
use frontend\modules\books\models\Author;
use function Codeception\Lib\Console\message;

class SubscribeController extends BooksController
{
    public function actionIndex($author_id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $author = Author::findOne($author_id);

        if ($author) {
            $subId = Yii::$app->user->identity->id;

            if (Subs::find()->where(['author_id' => $author_id, 'user_id' => $subId])) {
                return $this->render('index', [
                    'message' => Yii::t('app', 'Вы уже подписаны'),
                ]);
            }

            $sub = new Subs();
            $sub->user_id = $subId;
            try {
                $author->link('subs', $sub);

                $message = Yii::t('app', 'Вы успешно подписались на новые книги под авторством {author}', [
                    'author' => "$author->first_name $author->last_name",
                ]);
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $message = Yii::t('app', 'Ошибка');
        }

        return $this->render('index', [
            'message' => $message,
        ]);
    }
}
