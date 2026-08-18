<?php

declare(strict_types=1);

namespace frontend\modules\books\controllers;

use Yii;
use frontend\modules\books\models\Book;

class CatalogController extends BooksController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'catalog' => Book::find()->joinWith('authors')->asArray()->all(),
        ]);
    }

    public function actionAddBook()
    {
        $book = new Book();

        return $this->render('create', [
            'book' => $book
        ]);
    }
}
