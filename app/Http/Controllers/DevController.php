<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;


class DevController extends Controller
{
    public function index(Request $request, string $action = null)
    {
        if ($action === null) {
            $result = '<p>Available actions:</p><ul>';
            foreach (array_diff(get_class_methods($this), get_class_methods(Controller::class)) as $method) {
                if ($method !== 'index') {
                    $result .= '<li><a href="/dev/' . $method . '">' . $method . '</a></li>';
                }
            }

            return $result . '</ul>';
        }

        if (method_exists($this, $action)) {
            return $this->{$action}($request);
        }

        return null;
    }

    public function getDummyConfig()
    {
        $config = config('services.dummy_json');
        return $config;
    }

    /**
     * Добавить 5 проектов со случайно заполненными полями
     * 
     * GET /dev/addProject
     */
    public function addProject(): bool
    {
        try {
            for($i = 0; $i < 5; $i++){
                Project::create([
                    'title' => fake()->jobTitle(),
                    'author_id' => fake()->randomElement(User::pluck('id')),
                    'assignee_id' => fake()->randomElement(User::pluck('id')),
                    'deadline_date' => fake()->dateTimeBetween(now(), '+3 years')
                ]);
            }
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ": " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    /**
     * Получить проекты, принадлежащие админам
     * 
     * GET /dev/getAdminProjects
     */
    public function getAdminProjects()
    {
        $projects = collect();
        try {
            foreach(Project::all() as $project){
                if($project->owner->role === "admin") {
                    $projects->push([ 
                        'project' => $project,
                        'owner' => $project->owner
                    ]);
                }
            }
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ": " . $e->getMessage());
            return false;
        }

        return $projects;
    }

    /**
     * Получить проекты, с просроченным дедлайном,
     * отсортированные по возрастанию дедлайна
     * 
     * GET /dev/getExpired
     */
    public function getExpired(): Collection
    {
        try {
            $projects = Project::query()
                    ->where('deadline_date', "<", now())
                    ->orderBy('deadline_date')
                    ->get();
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ": " . $e->getMessage());
            return false;
        }

        return $projects;
    }

    /**
     * Изменить данные рандомного проекта
     * 
     * GET /dev/updateRandom
     */
    public function updateRandom()
    {
        try {
            $project = Project::inRandomOrder()
            ->first()
            ->update([
                'title' => fake()->jobTitle(),
                'author_id' => fake()->randomElement(User::pluck('id')),
                'assignee_id' => fake()->randomElement(User::pluck('id')),
                'deadline_date' => fake()->dateTimeBetween(now(), '+3 years')
            ]);
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ": " . $e->getMessage());
            return false;
        }
        
        return true;
    }
}
