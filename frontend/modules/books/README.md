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

## Отчет

    Шаблон модуля готов за 3 часа - настроены связы, счетчки, миграции, таблицы, сущности, модели и переменные, пространства имён.
    
    Отчет реализован через переменную date, при ее отсутсвии делаем выборку за дефолтный текущий год, чтобы избежать большой нагрузки при полной выборке за все время.

    Example: /books/top?date=2013    

    Осталось настроить рассылку и добавить возможность подписки к пользователям. Управление книгами.

    2 часа ушло на создание подписки, рассылки, обработки ошибок и тесты.

    1 час на редактирование книг, добавление/удаление, самое сложное тут будет в авторах, выбрать из трекущих или добавить новых.
    
    Итого не более 8 часов ушло на создание модуля.

    
    
    
