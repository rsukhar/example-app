<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'Вывести список проектов';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'Выводести форму для создания проекта';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return 'Сохранить новый проект в базе данных';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'Показать проект с id = ' . $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return 'Вывести форму для редактирования проекта с id = ' . $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'Сохранить изменения в проекте с id = ' . $id;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return 'Удалить проект с id = ' . $id;
    }
}
