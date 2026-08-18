<?php

declare(strict_types=1);

namespace frontend\modules\books\controllers;

use Yii;
use frontend\modules\books\models\Author;

class TopController extends BooksController
{
    public function actionIndex()
    {
        $date = (int)Yii::$app->request->get('date');

        return $this->render('index', [
            'report' => Author::getTop($date),
            'date' => $date ? $date : 'текущий',
        ]);
    }
}
