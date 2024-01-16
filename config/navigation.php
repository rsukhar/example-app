<?php

return [
    // Выпадающий список админского меню
    'admin' => [
        [
            'label' => 'Пользователи',
            'route' => 'users.index',
            'showIfCan' => ['viewAll', \App\Models\User::class],
        ],
        [
            'label' => 'Страницы',
            'route' => 'pages.index',
            'showIfCan' => ['viewAll', \App\Models\Page::class],
        ],
        [
            'label' => 'Интересы',
            'route' => 'interests.index',
            'showIfCan' => ['viewAll', \App\Models\Interest::class],
        ],
        [
            'label' => 'Сайты Интересов',
            'route' => 'interest_sites.index',
            'showIfCan' => ['viewAll', \App\Models\InterestSite::class],
        ],
        [
            'label' => 'Страницы Интересов',
            'route' => 'interest_urls.index',
            'showIfCan' => ['viewAll', \App\Models\InterestUrl::class],
        ],
        [
            'label' => 'Отчеты',
            'url' => '/reports/kpi/',
            'showIfCan' => ['viewAll', \App\Models\Subscription::class],
        ],
        [
            'label' => 'Настройки',
            'url' => '/settings/parsing/',
            'showIfCan' => ['editGroup', [\App\Models\Setting::class, 'parsing']],
        ],
    ],
    // Второй уровень ссылок проекта
    'project' => [
        [
            'label' => 'Обзор',
            'route' => 'projects.show',
        ],
        [
            'label' => 'Статистика',
            'route' => 'projects.stats',
        ],
        [
            'label' => 'Интересы',
            'route' => 'projects.interests',
        ],
        'edit' => [
            'label' => 'Настройки',
            'route' => 'projects.edit-general',
            'children' => [
                [
                    'label' => 'Общие настройки',
                    'route' => 'projects.edit-general',
                ],
                [
                    'label' => 'Интересы',
                    'route' => 'projects.edit-interests',
                ],
                [
                    'label' => 'Проверка профилей',
                    'route' => 'projects.edit-checks',
                ],
            ],
        ],
    ],
    // Второй уровень ссылок аккаунта
    'user' => [
        [
            'label' => 'Аккаунт',
            'route' => 'users.show',
        ],
        [
            'label' => 'Статистика',
            'route' => 'users.stats',
        ],
        [
            'label' => 'Тарифы и платежи',
            'route' => 'users.billing',
        ],
    ],
    'settings' => [
        [
            'label' => 'Парсинг',
            'url' => '/settings/parsing/',
            'showIfCan' => ['editGroup', [\App\Models\Setting::class, 'parsing']],
        ],
        [
            'label' => 'Профили',
            'url' => '/settings/projects/',
            'showIfCan' => ['editGroup', [\App\Models\Setting::class, 'projects']],
        ],
        [
            'label' => 'Финансы',
            'url' => '/settings/finance/',
            'showIfCan' => ['editGroup', [\App\Models\Setting::class, 'finance']],
        ],
        [
            'label' => 'Чатбот',
            'url' => '/settings/chatbot/',
            'showIfCan' => ['editGroup', [\App\Models\Setting::class, 'chatbot']],
        ],
        [
            'label' => 'Прокси',
            'url' => '/proxies/',
            'showIfCan' => ['viewAll', \App\Models\Proxy::class],
        ],
    ],
    // Второй уровень отчетов
    'reports' => [
        [
            'label' => 'KPI',
            'url' => '/reports/kpi/',
        ],
        [
            'label' => 'Подписки',
            'url' => '/reports/subscriptions/',
        ],
    ],
];
