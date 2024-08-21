<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;


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
    public function addProject()
    {
        $addedProjects = collect();
        try {
            for($i = 0; $i < 5; $i++){
                $addedProjects->push(Project::create([
                    'title' => fake()->jobTitle(),
                    'owner_id' => User::inRandomOrder()->first()->id,
                    'is_active' => fake()->boolean(),
                    'assignee_id' => User::inRandomOrder()->first()->id,
                    'deadline_date' => fake()->dateTimeBetween(now(), '+3 years')
                ]));
            }
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ': ' . $e->getMessage());
            return $e->getMessage();
        }
        
        return $addedProjects;
    }

    /**
     * Получить проекты, принадлежащие админам
     * 
     * GET /dev/getAdminProjects
     */
    public function getAdminProjects()
    {
        try {
            return Project::with('owner')->whereRelation('owner', 'role', 'admin')->get();
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ': ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Получить проекты, с просроченным дедлайном,
     * отсортированные по возрастанию дедлайна
     * 
     * GET /dev/getExpired
     */
    public function getExpired()
    {
        try {
            return Project::where('deadline_date', '<', date('Y-m-d'))
                            ->orderBy('deadline_date')
                            ->get();
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ': ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Изменить данные рандомного проекта
     * 
     * GET /dev/updateRandom
     */
    public function updateRandom()
    {
        try {
            $updatedProject = tap(
                Project::inRandomOrder()
                ->first()
            )->update([
                'title' => fake()->jobTitle(),
                'owner_id' => User::inRandomOrder()->first()->id,
                'is_active' => fake()->boolean(),
                'assignee_id' => User::inRandomOrder()->first()->id,
                'deadline_date' => fake()->dateTimeBetween(now(), '+3 years')
            ]);
        } catch (Throwable $e) {
            Log::error(__METHOD__ . ': ' . $e->getMessage());
            return $e->getMessage();
        }
        
        return $updatedProject;
    }

    /**
     * Три последних проекта:
     * - если пользователь авторизован - проекты пользователя
     * - если не авторизован - проекты любых пользователей
     * 
     * GET /dev/getMyLatestThree
     */
    public function getMyLatestThree(Request $request): Collection
    {
        return Project::when(auth('api')->check(), function (Builder $builder) {
                $builder->where('owner_id', Auth::id());
               })->latest()
               ->limit(3)
               ->get();
    }

    /**
     * Список с именами пользователей и количеством их проектов
     * 
     * GET /dev/userProjects
     */
    public function userProjects(): Collection
    {
        return User::select('username')
                    ->withCount('ownedProjects as projects_count')
                    ->get();
    }

    /**
     * Количество проектов с просроченным дедлайном
     * 
     * GET /dev/getExpiredProjectsCount
     */
    public function getExpiredProjectsCount(): JsonResponse
    {
        return response()->json(['expired_projects_count' => Project::expired()->count()]);
    }
}
