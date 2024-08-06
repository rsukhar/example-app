<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class Navigation
{
    /**
     * @param string $configPath Путь в конфиге navigation
     *
     * @return array
     */
    public static function getLinks(string $configPath = 'default'): array
    {
        $links = config('navigation.' . $configPath, []);

        // Оставляем только те пункты, которые доступны для текущего пользователя
        $curUser = Auth::user();
        $removeExcess = function ($items) use (&$removeExcess, $curUser) {
            $result = [];
            foreach ($items as $item) {
                if (isset($item['children'])) {
                    $item['children'] = $removeExcess($item['children']);
                }
                // Проверяем возможность показа пункта меню
                if (isset($item['showIfCan']) and ($curUser === null or $curUser->cannot($item['showIfCan'][0], $item['showIfCan'][1]))) {
                    continue;
                }
                $result[] = $item;
            }

            return $result;
        };
        $links = $removeExcess($links);

        $currentParams = request()->route()->parameters();

        // Проставляем URL для пунктов, где они явно не заданы, но можно получить из роутов
        // Это также используется, чтобы сохранять текущие параметры
        $fixUrls = function (array &$links) use ($currentParams, &$fixUrls) {
            foreach ($links as &$link) {
                if ( ! isset($link['url']) and isset($link['route'])) {
                    // Отсекаю GET-параметры, т.к. не нашел, как находить их в исходном роуте,
                    // чтобы не подставлять вообще
                    $link['url'] = self::fixSlashes(
                        strtok(
                            route($link['route'], $currentParams, false),
                            '?'
                        )
                    );
                }
                if (isset($link['children']) and is_array($link['children'])) {
                    $fixUrls($link['children']);
                }
            }
        };
        $fixUrls($links);

        // Проставляем активность каждого пункта
        $currentUrl = self::fixSlashes(request()->path());
        $setActive = function (array &$links) use ($currentUrl, &$setActive) {
            $anyLinkIsActive = false;
            foreach ($links as &$link) {
                // Активность текущей ссылки
                $isActive = ($link['url'] === $currentUrl);
                // Проставляем активность вложенных ссылок, и получаем, есть ли активные
                $hasActiveChildren = (isset($link['children']) and is_array($link['children'])
                    and $setActive($link['children']));
                $link['active'] = ($hasActiveChildren or $isActive);
                // Если активен хотя бы один из элементов, пробрасываем информацию об этом наверх
                $anyLinkIsActive = ($anyLinkIsActive or $link['active']);
            }

            return $anyLinkIsActive;
        };
        $setActive($links);

        return $links;
    }

    /**
     * Исправляет пути, генерируемые Laravel routes, добавляя слеш в конце, где нужно
     *
     * @param string $url
     *
     * @return string
     */
    protected static function fixSlashes(string $url): string
    {
        return ($url === '/') ? $url : ('/' . trim($url, '/') . '/');
    }

}
