<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ManagerUserController extends Controller
{
    private function currentUser(): ?User
    {
        if (!Session::has('user_id')) {
            return null;
        }

        return User::with('role')->find(Session::get('user_id'));
    }

    private function ensureAdmin(): User|RedirectResponse
    {
        $currentUser = $this->currentUser();

        if (!$currentUser) {
            return redirect()->route('login.form')
                ->with('error', 'Debes iniciar sesión.');
        }

        if (!$currentUser->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos.');
        }

        return $currentUser;
    }

    public function index(): View|RedirectResponse
    {
        $currentUser = $this->ensureAdmin();

        if ($currentUser instanceof RedirectResponse) {
            return $currentUser;
        }

        $managers = User::with(['role', 'managedUsers'])
            ->whereHas('role', function ($query) {
                $query->where('name', 'gestor');
            })
            ->orderBy('name')
            ->get();

        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', 'usuario');
            })
            ->orderBy('name')
            ->get();

        return view('admin.managers.index', [
            'user' => $currentUser,
            'managers' => $managers,
            'users' => $users,
        ]);
    }

    public function syncUsers(Request $request, User $manager): RedirectResponse
    {
        $currentUser = $this->ensureAdmin();

        if ($currentUser instanceof RedirectResponse) {
            return $currentUser;
        }

        $manager->load('role');

        if (!$manager->isGestor()) {
            return back()->with('error', 'El usuario seleccionado no es gestor.');
        }

        $validated = $request->validate([
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $userIds = $validated['user_ids'] ?? [];

        $validUserIds = User::whereIn('id', $userIds)
            ->whereHas('role', function ($query) {
                $query->where('name', 'usuario');
            })
            ->pluck('id')
            ->toArray();

        if (in_array($manager->id, $validUserIds)) {
            return back()->with('error', 'Un gestor no puede asignarse a sí mismo.');
        }

        $manager->managedUsers()->sync($validUserIds);

        return back()->with('success', 'Usuarios asignados correctamente al gestor.');
    }
}