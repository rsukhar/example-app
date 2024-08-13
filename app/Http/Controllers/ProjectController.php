<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private $users = [
        0 => [
            'id' => 1,
            'username' => 'antoxa95',
            'email' => 'seminanton2@gmail.com',
            'first_name' => 'Anton'
        ],
        1 => [
            'id' => 2,
            'username' => 'petr93',
            'email' => 'petrivanon@gmail.com',
            'first_name' => 'Petr'
        ],
        2 => [
            'id' => 3,
            'username' => 'phpKiller',
            'email' => 'egorov777@gmail.com',
            'first_name' => 'Sergey'
        ]
    ];
    private $projects = [
        0 => [
            'title' => 'project1',
            'author_id' => 2,
            'assignee_id' => 10,
            'deadline_date' => '2024-12-31'
        ],
        1 => [
            'title' => 'New Year Project',
            'author_id' => 2,
            'assignee_id' => 26,
            'deadline_date' => '2025-01-01'
        ]
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alert = [
            'title' => 'Успешно',
            'message' => 'Получен список проектов',
            'type' => 'success'
        ];
        return view('blade_pages.project.index', ['projects' => $this->projects, 'alert' => $alert]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blade_pages.project.create', ['projects' => $this->projects, 'users' => $this->users]);
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
        if (key_exists($id, $this->projects)) {
            return view('blade_pages.project.show', ['project' => $this->projects[$id]]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (key_exists($id, $this->projects)) {
            $projectToEdit = $this->projects[$id];
            return view('blade_pages.project.edit', ['id' => $id, 'projectToEdit' => $projectToEdit, 'users' => $this->users]);
        }
        abort(404);
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
