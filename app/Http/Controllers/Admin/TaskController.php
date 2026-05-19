<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class TaskController extends Controller
{
    private function ensureAdminOrGestor(): ?RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        $user = User::with('role')->find(Session::get('user_id'));

        if (!$user || (!$user->isAdmin() && !$user->isGestor())) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        return null;
    }

    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if ($user->isAdmin()) {
            $tasks = Task::with(['assignedUser'])->latest()->get();
        } else {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            $tasks = Task::with(['assignedUser'])
                ->whereIn('assigned_user_id', $managedUserIds)
                ->latest()
                ->get();
        }

        return view('admin.tasks.index', compact('tasks', 'user'));
    }

    public function create(): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if ($user->isAdmin()) {
            $users = User::where('email', '!=', 'pinillar100@outlook.es')
                ->orderBy('name')
                ->get();
        } else {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            $users = User::whereIn('id', $managedUserIds)
                ->where('email', '!=', 'pinillar100@outlook.es')
                ->orderBy('name')
                ->get();
        }

        return view('admin.tasks.create', compact('users', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'priority' => 'required|in:baja,media,alta',
            'due_date' => 'required|date|after_or_equal:today|before_or_equal:2099-12-31',
        ]);

        $selectedUser = User::find($request->assigned_user_id);

        if ($selectedUser && $selectedUser->email === 'pinillar100@outlook.es') {
            return back()->with('error', 'No puedes asignar tareas al administrador principal.');
        }

        if ($user->isGestor()) {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            if (!in_array((int) $request->assigned_user_id, $managedUserIds)) {
                return back()->with('error', 'No puedes asignar tareas a este usuario.');
            }
        }

        Task::create([
            'assigned_user_id' => $request->assigned_user_id,
            'title' => htmlspecialchars(trim($request->title), ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
            'status' => 'pendiente',
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    public function edit(Task $task): View|RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if ($user->isGestor()) {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            if (!in_array((int) $task->assigned_user_id, $managedUserIds)) {
                return redirect()->route('admin.tasks.index')
                    ->with('error', 'No tienes permisos para editar esta tarea.');
            }
        }

        $users = User::where('email', '!=', 'pinillar100@outlook.es')
            ->orderBy('name')
            ->get();

        return view('admin.tasks.edit', compact('task', 'users', 'user'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if ($user->isGestor()) {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            if (!in_array((int) $task->assigned_user_id, $managedUserIds)) {
                return redirect()->route('admin.tasks.index')
                    ->with('error', 'No tienes permisos para editar esta tarea.');
            }
        }

        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'status' => 'required|in:pendiente,completada',
            'priority' => 'required|in:baja,media,alta',
            'due_date' => 'required|date|after_or_equal:today|before_or_equal:2099-12-31',
        ]);

        $task->update([
            'assigned_user_id' => $task->assigned_user_id,
            'title' => htmlspecialchars(trim($request->title), ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        if ($redirect = $this->ensureAdminOrGestor()) return $redirect;

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if ($user->isGestor()) {
            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->toArray();

            if (!in_array((int) $task->assigned_user_id, $managedUserIds)) {
                return redirect()->route('admin.tasks.index')
                    ->with('error', 'No tienes permisos para eliminar esta tarea.');
            }
        }

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea eliminada correctamente.');
    }

    public function show(Task $task): RedirectResponse
    {
        return redirect()->route('admin.tasks.index');
    }
}