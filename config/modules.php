<?php

return [
    'modules' => [
        'catalog' => [
            'provider' => \App\Modules\Catalog\Infrastructure\Providers\CatalogServiceProvider::class,
            'routes' => 'routes/catalog.php',
        ],
        'sales' => [
            'provider' => \App\Modules\Sales\Infrastructure\Providers\SalesServiceProvider::class,
            'routes' => 'routes/sales.php',
        ],
        'customers' => [
            'provider' => \App\Modules\Customers\Infrastructure\Providers\CustomersServiceProvider::class,
            'routes' => 'routes/customers.php',
        ],
        'backoffice' => [
            'provider' => \App\Modules\Backoffice\Infrastructure\Providers\BackofficeServiceProvider::class,
            'routes' => 'routes/backoffice.php',
        ],
        'notifications' => [
            'provider' => \App\Modules\Notifications\Infrastructure\Providers\NotificationsServiceProvider::class,
            'routes' => 'routes/notifications.php',
        ],
        'content' => [
            'provider' => \App\Modules\Content\Infrastructure\Providers\ContentServiceProvider::class,
            'routes' => 'routes/content.php',
        ],
        'support' => [
            'provider' => \App\Modules\Support\Infrastructure\Providers\SupportServiceProvider::class,
            'routes' => 'routes/support.php',
        ],
    ],
];
