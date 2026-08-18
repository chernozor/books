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
                                <?= Html::a('Подписаться на новые книги', ['/books/subscribe', 'author_id' => $author['id']], ['class' => 'btn btn-primary mb-3']) ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
