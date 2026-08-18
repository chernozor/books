<?php

declare(strict_types=1);

namespace frontend\modules\books\controllers;

use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use frontend\modules\books\models\Book;
use frontend\modules\books\models\Author;

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
            'catalog' => Book::find()->joinWith('authors')->asArray()->all(), // массив легче коллекций для теста, для большого количества сделаем пагинацию
        ]);
    }

    public function actionView($id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionAdd(): Response|string
    {
        $model = new Book();

        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Запрещено'));
            return $this->redirect(['index']);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'authorList' => Author::getAuthorList(),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $authorList = Author::getAuthorList();

        if (!$this->checkPerm($model)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Запрещено'));
            return $this->redirect(['index']);
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'authorList' => $authorList,
        ]);
    }

    public function actionDelete($id)
    {
        /** @var Book $book */
        $book = $this->findModel($id);
        if ($this->checkPerm($book)) {
            $book->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Удалено'));
        } else {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Запрещено'));
        }

        return $this->redirect(['index']);
    }

    protected function checkPerm(Book $book)
    {
        return Yii::$app->user?->identity->id == $book->created_by;
    }

    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
