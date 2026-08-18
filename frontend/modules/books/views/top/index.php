<?php

declare(strict_types=1);

/**
 * @var View $this
 * @var array $report
 * @var string|int $date
 */

use yii\web\View;


?>
<div class="container">
    <div class="col-xs-12">
        Топ10 авторов за <?= $date ?> год:
    </div>
    <div class="col-xs-12">
        <?php
        foreach ($report as $top) {
            echo "{$top['id']} {$top['last_name']}  => {$top['bookCount']}<br/>";
        } ?>
    </div>
</div>
