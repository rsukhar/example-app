<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Middleware\AuthorizeMiddleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(AuthorizeMiddleware::class, except: ['store', 'update'])
        ];
    }
    
    /**
     * Список проектов
     * 
     * GET /projects
     */
    public function index()
    {
        $projects = Project::all();
        return view('blade_pages.project.index', ['projects' => $projects]);
    }

    /**
     * Форма для создания проектов
     * 
     * GET /projects/create
     */
    public function create()
    {
        $users = User::all();
        return view('blade_pages.project.create', ['users' => $users]);
    }

    /**
     * Сохранить новый проект в БД
     * 
     * POST /projects
     */
    public function store(StoreProjectRequest $request)
    {
        // В контроллер будет передаваться id пользователя, 
        // который создает проект
        $author_id = 1;

        $validated = $request->validated();

        $project = new Project();
        $project->fill($validated);
        $project->author_id = $author_id;
        $project->save();

        return redirect()->route('projects.index', ['access' => 'yes']);
    }

    /**
     * Получить проект по id
     * 
     * GET /projects/{id}
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);
        return view('blade_pages.project.show', ['project' => $project]);
    }

    /**
     * Форма редактирования проекта
     * 
     * GET /projects/{id}/edit
     */
    public function edit(string $id)
    {
        $users = User::all();
        $project = Project::findOrFail($id);
        return view('blade_pages.project.edit', ['id' => $project->id, 'projectToEdit' => $project, 'users' => $users]);
    }

    /**
     * Сохранить изменения в проекте
     * 
     * PUT /projects/{id}
     */
    public function update(UpdateProjectRequest $request, int $id)
    {
        $validated = $request->validated();
        $project = Project::findOrFail($id);
        $project->fill($request->all());
        $project->save();
        return redirect()->route('projects.index', ['access' => 'yes']);
    }

    /**
     * Удалить проект
     * 
     * DELETE /projects/{id}
     */
    public function destroy(string $id)
    {
        return 'Удалить проект с id = ' . $id;
    }
}
