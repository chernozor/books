# Модуль frontBooks

## Подключение модуля frontend/config/main.php

    'modules' => [
        'books' => [
            'class' => frontend\modules\books\Module::class,
        ],
    ]

## Подключение миграций console/config/main.php

    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationNamespaces' => [
                'frontend\modules\books\migrations',
            ],
        ],
    ],

## Подключение событий common/config/main.php

    'bootstrap' => [
        \frontend\modules\books\events\EventBootstrap::class,
    ],

## Генератор миграции

    docker-compose run --rm php ./yii migrate/create --migrationPath=@frontend/modules/books/migrations create_table
    
    
    
