<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EventCalendarController extends Controller
{
    private function currentUser(): ?User
    {
        if (!Session::has('user_id')) {
            return null;
        }

        return User::with(['role', 'managedUsers'])->find(Session::get('user_id'));
    }

    private function allowedUserIds(User $user): array
    {
        if ($user->isAdmin()) {
            return User::pluck('id')->toArray();
        }

        if ($user->isGestor()) {
            return $user->managedUsers()
                ->pluck('users.id')
                ->push($user->id)
                ->unique()
                ->toArray();
        }

        return [$user->id];
    }

    public function index()
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form')
                ->with('error', 'Debes iniciar sesión.');
        }

        return view('calendar.index', compact('user'));
    }

    public function events(Request $request)
    {
        $user = $this->currentUser();

        if (!$user) {
            return response()->json([]);
        }

        $allowedUserIds = $this->allowedUserIds($user);

        $events = [];

        $projects = Project::with('user')
            ->whereIn('user_id', $allowedUserIds)
            ->where(function ($query) {
                $query->whereNotNull('start_date')
                    ->orWhereNotNull('end_date');
            })
            ->get();

        foreach ($projects as $project) {
            $date = $project->end_date ?: $project->start_date;

            if ($date) {
                $events[] = [
                    'title' => 'Proyecto: ' . $project->name,
                    'start' => $date,
                    'color' => '#0d6efd',
                    'extendedProps' => [
                        'tipo' => 'Proyecto',
                        'nombre' => $project->name,
                        'descripcion' => $project->description ?: 'Sin descripción',
                        'estado' => $project->status,
                        'usuario' => $project->user->name ?? 'Sin usuario',
                        'email' => $project->user->email ?? 'Sin correo',
                        'fecha' => $date,
                    ],
                ];
            }
        }

        $tasks = Task::with(['assignedUser', 'project'])
            ->whereIn('assigned_user_id', $allowedUserIds)
            ->whereNotNull('due_date')
            ->get();

        foreach ($tasks as $task) {
            $events[] = [
                'title' => 'Tarea: ' . $task->title,
                'start' => $task->due_date,
                'color' => $this->taskColor($task->status),
                'extendedProps' => [
                    'tipo' => 'Tarea',
                    'nombre' => $task->title,
                    'descripcion' => $task->description ?: 'Sin descripción',
                    'estado' => $task->status,
                    'prioridad' => $task->priority ?? 'normal',
                    'usuario' => $task->assignedUser->name ?? 'Sin asignar',
                    'email' => $task->assignedUser->email ?? 'Sin correo',
                    'proyecto' => $task->project->name ?? 'Sin proyecto',
                    'fecha' => $task->due_date,
                ],
            ];
        }

        return response()->json($events);
    }

    private function taskColor(?string $status): string
    {
        return match ($status) {
            'pendiente' => '#ffc107',
            'completada' => '#198754',
            default => '#6c757d',
        };
    }
}