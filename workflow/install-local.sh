#!/bin/bash

# Украшатели оповещений
LBINFO="  \e[44m ЗАМЕТКА \e[0m"
LBERROR="  \e[41m ОШИБКА \e[0m"
LBSUCCESS="  \e[42m ГОТОВО \e[0m"

# Переходим в корневую директорию
cd "$(dirname "$0")/.."

# 1. Создаем env-файл
if [ -f .env ]; then
    echo -e $(printf "$LBINFO Файл .env уже существует - пропускаем")
else
    cp ./workflow/.env.local .env
    echo -e $(printf "$LBSUCCESS Создали .env-файл")
fi

# 2. Устанавливаем бэкенд-библиотеки
echo -e $(printf "$LBINFO Устанавливаем PHP-библиотеки")
composer install
echo -e $(printf "$LBSUCCESS PHP-библиотеки установлены")

# 3. Запускаем докер
DOCKERERROR=$(./vendor/bin/sail status 2>&1)
if [[ "$DOCKERERROR" = "Docker is not running." ]]; then
    echo -e $(printf "$LBERROR Приложение Docker не запущено. Необходимо запустить для установки")
    exit 1
fi
echo -e $(printf "$LBINFO Запускаем Docker-контейнеры")
./vendor/bin/sail up -d
echo -e $(printf "$LBSUCCESS Docker-контейнеры запущены")

# 4. Генерируем стартовые данные
echo -e $(printf "$LBINFO Генерируем стартовые данные")
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
echo -e $(printf "$LBSUCCESS Добавлен пользователь: \e[1madmin\e[0m / \e[1madmin\e[0m")

# 5. Устанавливаем фронтенд-зависимости
echo -e $(printf "$LBINFO Устанавливаем JS-библиотеки")
./vendor/bin/sail npm install
echo -e $(printf "$LBSUCCESS JS-библиотеки установлены")

# 6. Запускаем Vite-сервер для отдачи статики
echo -e $(printf "$LBINFO Запускаем Vite-сервер для отдачи статики")
./vendor/bin/sail npm run dev
