# Образец приложения Стартап-Бюро на Laravel + Vue3 + InertiaJS

## Технологии

### Используем

1. **[Laravel 10](https://laravel.com/docs/10.x)** — Бэкенд-фреймворк
2. **[Vue3](https://vuejs.org/)** — SPA фронтенд-фреймворк (используем только Composition API)
3. **[PrimeVue](https://primevue.org/)** — Библиотеку компонентов для Vue3
4. **[VueUse](https://vueuse.org/)** — Библиотеку вспомогательных методов для Vue3
5. **[InertiaJS](https://inertiajs.com/)** — Монолитный интегратор бэка и SPA-фронта.
6. **SASS**
7. **CSS Переменные**
8. **PostgreSQL**
9. **Redis**

### НЕ Используем

1. TypeScript, т.к. (а) фокусируемся на стартапы — небольшие проекты, которые нужно запустить с максимальной скоростью; (б) снижаем порог входа новых разработчиков. По мере роста стартапа TypeScript может быть добавлен по необходимости. 
2. [Vue3 Options API](https://vuejs.org/guide/introduction.html#api-styles), т.к.в рамках рекомендаций самого Vue это оптимально при 
3. [TailWind CSS](https://tailwindcss.com/), т.к.

## Локальная установка


1. Настраиваем локальный алиас для [Sail](https://laravel.com/docs/10.x/sail)

   1.1. Если используете bash

    ```bash
    echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bash_profile
    source ~/.bash_profile
    ```

   1.2. Если используете zsh
    ```bash
    echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.zshrc
    source ~/.bash_profile
    ```

2. Устанавливаем переменные окружения

```bash
cp workflow/.env.local .env  
```

3. Устанавливаем бэкенд-библиотеки

```bash
composer install
```

4. Запускаем докер

```bash
sail up -d
```

P.S. Если получаем ошибку "Порт занят", то sail down && sail up -d

5. Генерируем стартовые данные

```bash
sail artisan key:generate
```
```bash
sail artisan migrate
```
```bash
sail artisan db:seed
```

Добавится пользователь: **admin** / **admin**

6. Устанавливаем фронтенд-зависимости

```bash
sail npm install
```

7. Запускаем hmr-генерацию статики через vite:

```bash
sail npm run dev
```
P.S. Если получаем ошибку "Порт занят", то sail down && sail up -d

8. Открываем в браузере: http://0.0.0.0/

## Запуск

### Запуск приложения

```bash
# Back
sail up -d

# Первоначальная миграция
sail artisan migrate

# Front (vite compiler)
sail npm run dev
```

Далее откройте http://0.0.0.0/ в браузере.

### Ручной доступ к БД

1. Установите dbeaver.io
2. Создайте новое подключение PostgreSQL:
    * Host: 0.0.0.0:5432
    * Database: deepinsite
    * Username: sail
    * Password: password