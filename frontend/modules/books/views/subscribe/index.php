<?php

declare(strict_types=1);

/**
 * @var View $this
 * @var string $message
 */

use yii\web\View;


?>
<div class="container">
    <div class="mb-3"><?= $message ?></div>
    <?= \yii\helpers\Html::a(Yii::t('app', 'Назад'), ['/books/catalog'], ['class' => 'btn btn-primary']) ?>
</div>
