<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var frontend\modules\books\models\Book $model
 * @var array $authorList
 */

$this->title = Yii::t('app', 'Update Book: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$model->authorList = ArrayHelper::getColumn($model->authors, 'id');
?>
<div class="book-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="my-5">
        <?= html::a(Yii::t('app', 'Удалить'), ['/books/catalog/delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data' => [
            'confirm' => Yii::t('app', 'Удалить?'),
            'method' => 'post',
        ],]) ?>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'authorList' => $authorList,
    ]) ?>

</div>
