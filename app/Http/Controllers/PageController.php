<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    /**
     * Вывод главной страницы
     *
     * GET /
     */
    public function showHome(): Response
    {
        return Inertia::render('Page/ShowHome');
    }
}
