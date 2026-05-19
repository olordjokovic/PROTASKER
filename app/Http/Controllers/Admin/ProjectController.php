<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProjectController extends Controller
{
    private function currentUser(): ?User
    {
        if (!Session::has('user_id')) {
            return null;
        }

        return User::with(['role', 'managedUsers'])->find(Session::get('user_id'));
    }

    private function ensureAdminOrGestor(): User|RedirectResponse
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        if (!$user->isAdmin() && !$user->isGestor()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        return $user;
    }

    private function allowedUserIds(User $user): array
    {
        if ($user->isAdmin()) {
            return User::pluck('id')->toArray();
        }

        return $user->managedUsers()->pluck('users.id')->toArray();
    }

    private function canManageProject(User $user, Project $project): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return in_array($project->user_id, $this->allowedUserIds($user));
    }

    public function index(): View|RedirectResponse
    {
        $user = $this->ensureAdminOrGestor();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        if ($user->isAdmin()) {
            $projects = Project::with('user')->latest()->get();
        } else {
            $projects = Project::with('user')
                ->whereIn('user_id', $this->allowedUserIds($user))
                ->latest()
                ->get();
        }

        return view('admin.projects.index', compact('projects', 'user'));
    }

    public function create(): View|RedirectResponse
    {
        $user = $this->ensureAdminOrGestor();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $users = User::whereIn('id', $this->allowedUserIds($user))
            ->where('email', '!=', 'pinillar100@outlook.es')
            ->orderBy('name')
            ->get();

        return view('admin.projects.create', compact('users', 'user'));
    }

    public function store(Request $request): RedirectResponse
{
    $user = $this->ensureAdminOrGestor();

    if ($user instanceof RedirectResponse) {
        return $user;
    }

    $request->validate([
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date|after_or_equal:today|before_or_equal:2099-12-31',
        'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:2099-12-31',
    ]);

    if (!in_array((int) $request->user_id, $this->allowedUserIds($user))) {
        return back()->with('error', 'No puedes asignar proyectos a este usuario.');
    }

    $selectedUser = User::find($request->user_id);

    if ($selectedUser && $selectedUser->email === 'pinillar100@outlook.es') {
        return back()->with('error', 'No puedes asignar proyectos al administrador principal.');
    }

    Project::create([
        'user_id' => $request->user_id,
        'name' => htmlspecialchars(trim($request->name), ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
        'status' => 'pendiente',
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);

    return redirect()->route('admin.projects.index')
        ->with('success', 'Proyecto creado correctamente.');
}

    public function edit(Project $project): View|RedirectResponse
    {
        $user = $this->ensureAdminOrGestor();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        if (!$this->canManageProject($user, $project)) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'No tienes permisos para editar este proyecto.');
        }

        $users = User::whereIn('id', $this->allowedUserIds($user))
            ->orderBy('name')
            ->get();

        return view('admin.projects.edit', compact('project', 'users', 'user'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $user = $this->ensureAdminOrGestor();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        if (!$this->canManageProject($user, $project)) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'No tienes permisos para editar este proyecto.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pendiente,completado',
            'start_date' => 'required|date|before_or_equal:2099-12-31',
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:2099-12-31',
        ]);

        if (!in_array((int) $request->user_id, $this->allowedUserIds($user))) {
            return back()->with('error', 'No puedes asignar proyectos a este usuario.');
        }

        $project->update([
            'user_id' => $request->user_id,
            'name' => htmlspecialchars(trim($request->name), ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $user = $this->ensureAdminOrGestor();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        if ($user->isGestor() && !$this->canManageProject($user, $project)) {
            return redirect()->route('admin.projects.index')
                ->with('error', 'No tienes permisos para eliminar este proyecto.');
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    public function show(Project $project): RedirectResponse
    {
        return redirect()->route('admin.projects.index');
    }
}