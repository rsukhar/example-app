<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения',

    'custom' => [
        'title' => [
            'required' => 'Проекту нужно название'
        ],
        'deadline_date' => [
            'required' => 'Назначьте крайний срок сдачи проекта!'
        ]
    ],

    'attributes' => [
        'title' => '«Название»',
        'deadline_date' => '«Дедлайн»'
    ]
];