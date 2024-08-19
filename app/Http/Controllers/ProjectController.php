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
    public function store(StoreProjectRequest $request, Project $project)
    {
        $project::create($request->all() + ['author_id' => auth()->id()]);
        return redirect()->route('projects.index', ['access' => 'yes']);
    }

    /**
     * Получить проект по id
     * 
     * GET /projects/{id}
     */
    public function show(Project $project)
    {
        return view('blade_pages.project.show', ['project' => $project]);
    }

    /**
     * Форма редактирования проекта
     * 
     * GET /projects/{id}/edit
     */
    public function edit(Project $project)
    {
        $users = User::all();
        return view('blade_pages.project.edit', ['project' => $project, 'users' => $users]);
    }

    /**
     * Сохранить изменения в проекте
     * 
     * PUT /projects/{id}
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('projects.index', ['access' => 'yes']);
    }

    /**
     * Удалить проект
     * 
     * DELETE /projects/{id}
     */
    public function destroy(Project $project)
    {
        return 'Удалить проект с id = ' . $project->id;
    }
}
