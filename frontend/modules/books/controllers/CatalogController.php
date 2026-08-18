<?php

declare(strict_types=1);

namespace frontend\modules\books\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use frontend\modules\books\models\Book;

class CatalogController extends BooksController
{
    public function actionIndex()
    {
        //todo
        /*
         $dataProvider = new ArrayDataProvider([
            'query' => Book::find(),
            'pagination' => [
                'pageSize' => 25
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        */

        return $this->render('index', [
            'catalog' => Book::find()->joinWith('authors')->asArray()->all(), // массив легче коллекций
        ]);
    }

    public function actionAddBook(): string
    {
        $book = new Book();

        return $this->render('create', [
            'book' => $book,
        ]);
    }

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(): Response|string
    {
        $model = new Book();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
