#!/bin/bash

# Украшатели оповещений
LBINFO="  \e[44m ЗАМЕТКА \e[0m"
LBERROR="  \e[41m ОШИБКА \e[0m"
LBSUCCESS="  \e[42m ГОТОВО \e[0m"

DOCKERERROR=$(./vendor/bin/sail status 2>&1)
if [[ "$DOCKERERROR" = "Docker is not running." ]]; then
    echo -e $(printf "$LBERROR Приложение Docker не запущено. Необходимо запустить для запуска приложения")
    exit 1
fi

echo -e $(printf "$LBINFO Запускаем Docker-контейнеры")
./vendor/bin/sail up -d

echo -e $(printf "$LBINFO Выполняем миграции")
./vendor/bin/sail artisan migrate

echo -e $(printf "$LBINFO Запускаем Vite-сервер для отдачи статики")
./vendor/bin/sail npm run dev
