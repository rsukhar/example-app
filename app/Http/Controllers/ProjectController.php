<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;

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

        $projects = Project::all();
        return view('blade_pages.project.index', ['projects' => $projects, 'alert' => $alert]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('blade_pages.project.create', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // В контроллер будет передаваться id пользователя, 
        // который создает проект
        $author_id = 1;

        $project = new Project();
        $project->fill($request->all());
        $project->author_id = $author_id;
        $project->save();

        return redirect('projects');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        if ($project !== null) {
            return view('blade_pages.project.show', ['project' => $project]);
        }
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($projectToEdit !== null) {
            $id = $projectToEdit->id;
            return view('blade_pages.project.edit', ['id' => $id, 'projectToEdit' => $projectToEdit, 'users' => $this->users]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $project = Project::findOrFail($id);
        $project->fill($request->all());
        $project->save();
        return redirect('projects');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return 'Удалить проект с id = ' . $id;
    }
}
