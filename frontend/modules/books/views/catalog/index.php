<?php

declare(strict_types=1);

/**
 * @var View $this
 * @var array $catalog
 */

use yii\web\View;
use yii\helpers\Html;


?>
<div class="container">

    <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="col-xs-12 mb-5">
            <?= Html::a(Yii::t('app', 'Добавить'), ['/books/catalog/add'], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <div class="col-xs-12">
        <div class="d-flex flex-wrap">
            <?php
            foreach ($catalog as $book) { ?>
                <div class="flex-fill card justify-content-between mb-5 me-3">
                    <div class="card-body">
                        <p>Название <?= $book['name'] ?></p>
                        <div>Год <?= $book['year'] ?></div>
                        <div>Автор:<br>
                            <?php
                            foreach ($book['authors'] as $author) { ?>
                                <p><?= "{$author['first_name']} {$author['middle_name']} {$author['last_name']}" ?></p>
                                <?= Html::a('Подписаться на автора', ['/books/subscribe', 'author_id' => $author['id']], ['class' => 'btn btn-primary mb-3']) ?>
                            <?php } ?>
                        </div>
                        <?php
                        echo Html::a(Yii::t('app', 'Просмотр'), ['/books/catalog/view', 'id' => $book['id']], ['class' => 'btn btn-outline-secondary me-3']);
                        if (!Yii::$app->user->isGuest && $book['created_by'] == Yii::$app->user?->identity?->id) {
                            echo Html::a(Yii::t('app', 'Редактировать'), ['/books/catalog/update', 'id' => $book['id']], ['class' => 'btn btn-secondary']);
                        }
                        ?>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
