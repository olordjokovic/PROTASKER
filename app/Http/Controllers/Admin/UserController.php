<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use App\Mail\AdminCreatedUserMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
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
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        return $user;
    }

    public function index(): View|RedirectResponse
    {
        $currentUser = $this->ensureAdmin();
        if ($currentUser instanceof RedirectResponse) return $currentUser;

        $users = User::with('role')
    ->where('email', '!=', 'pinillar100@outlook.es')
    ->latest()
    ->get();

        return view('admin.users.index', [
            'user' => $currentUser,
            'users' => $users,
        ]);
    }

    public function create(): View|RedirectResponse
    {
        $currentUser = $this->ensureAdmin();
        if ($currentUser instanceof RedirectResponse) return $currentUser;

        $roles = Role::all();

        return view('admin.users.create', [
        'roles' => $roles,
        'user' => $currentUser,
    ]);
    }

   public function store(Request $request): RedirectResponse
{
    $currentUser = $this->ensureAdmin();
    if ($currentUser instanceof RedirectResponse) return $currentUser;

    $request->validate([
        'role_id' => 'required|exists:roles,id',
        'name' => 'required|string|max:100',
        'surname' => 'required|string|max:150',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $adminRole = Role::where('name', 'admin')->first();

    /*
      Solo la cuenta principal puede ser admin
    */
    if (
        $adminRole &&
        (int) $request->role_id === (int) $adminRole->id &&
        $request->email !== 'pinillar100@outlook.es'
    ) {
        return back()->with(
            'error',
            'Solo la cuenta principal puede ser administradora.'
        );
    }

    /*
      Evitar múltiples admins
    */
    if ($adminRole && (int) $request->role_id === (int) $adminRole->id) {

        $adminExists = User::where('role_id', $adminRole->id)->exists();

        if ($adminExists) {
            return back()->with(
                'error',
                'Ya existe un administrador. No puede haber más de uno.'
            );
        }
    }

    $plainPassword = $request->password;

    $newUser = User::create([
        'role_id' => $request->role_id,
        'name' => htmlspecialchars(trim($request->name), ENT_QUOTES, 'UTF-8'),
        'surname' => htmlspecialchars(trim($request->surname), ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars(trim($request->email), ENT_QUOTES, 'UTF-8'),
        'password' => hash('sha256', $plainPassword),
        'email_verified_at' => now(),
    ]);

    Mail::to($newUser->email)->send(
        new AdminCreatedUserMail($newUser, $plainPassword)
    );

    return redirect()->route('admin.users.index')
        ->with('success', 'Usuario creado correctamente. Se ha enviado un correo con sus datos de acceso.');
}

    public function edit(User $user): View|RedirectResponse
    {
        $currentUser = $this->ensureAdmin();

        if ($currentUser instanceof \Illuminate\Http\RedirectResponse) {
            return $currentUser;
        }

        $roles = \App\Models\Role::all();

        return view('admin.users.edit', [
            'user' => $currentUser,
            'userToEdit' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
{
    $currentUser = $this->ensureAdmin();
    if ($currentUser instanceof RedirectResponse) return $currentUser;

    $request->validate([
        'role_id' => 'required|exists:roles,id',
        'name' => 'required|string|max:100',
        'surname' => 'required|string|max:150',
        'email' => 'required|email|unique:users,email,' . $user->id,
    ]);

    $adminRole = Role::where('name', 'admin')->first();

    /*
      Bloquear cambios del administrador principal
    */
    if ($user->isMainAdmin()) {

        if ($request->email !== 'pinillar100@outlook.es') {
            return back()->with(
                'error',
                'No puedes cambiar el correo del administrador principal.'
            );
        }

        if ($adminRole && (int) $request->role_id !== (int) $adminRole->id) {
            return back()->with(
                'error',
                'No puedes cambiar el rol del administrador principal.'
            );
        }
    }

    
    if (
        $adminRole &&
        (int) $request->role_id === (int) $adminRole->id &&
        $request->email !== 'pinillar100@outlook.es'
    ) {
        return back()->with(
            'error',
            'Solo la cuenta principal puede ser administradora.'
        );
    }

    
    if ($adminRole && (int) $request->role_id === (int) $adminRole->id) {
        $adminExists = User::where('role_id', $adminRole->id)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($adminExists) {
            return back()->with(
                'error',
                'Ya existe un administrador. No puede haber más de uno.'
            );
        }
    }

    if ($user->id === $currentUser->id && $request->role_id != $user->role_id) {
        return back()->with('error', 'No puedes cambiar tu propio rol.');
    }

    $user->update([
        'role_id' => $request->role_id,
        'name' => htmlspecialchars(trim($request->name), ENT_QUOTES, 'UTF-8'),
        'surname' => htmlspecialchars(trim($request->surname), ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars(trim($request->email), ENT_QUOTES, 'UTF-8'),
    ]);

    return redirect()->route('admin.users.index')
        ->with('success', 'Usuario actualizado correctamente.');
}

    public function destroy(User $user): RedirectResponse
{
    $currentUser = $this->ensureAdmin();
    if ($currentUser instanceof RedirectResponse) return $currentUser;

    
    if ($user->isMainAdmin()) {
        return back()->with(
            'error',
            'No puedes eliminar el administrador principal.'
        );
    }

    
    if ($user->id === $currentUser->id) {
        return back()->with(
            'error',
            'No puedes eliminar tu propia cuenta desde aquí.'
        );
    }

   
    if ($user->isGestor()) {
        $user->managedUsers()->detach();
    }

    
    $user->managers()->detach();

    $user->delete();

    return redirect()->route('admin.users.index')
        ->with('success', 'Usuario eliminado correctamente.');
}

    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index');
    }
}