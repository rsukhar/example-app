<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('blade_pages.project.index', ['projects' => $projects]);
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
        $users = User::all();
        $projectToEdit = Project::findOrFail($id);
        if ($projectToEdit !== null) {
            $id = $projectToEdit->id;
            return view('blade_pages.project.edit', ['id' => $id, 'projectToEdit' => $projectToEdit, 'users' => $users]);
        }
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, int $id)
    {
        $validated = $request->validated();

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
