<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        $user = User::find(Session::get('user_id'));

        if (!$user) {
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Usuario no encontrado.');
        }

        return view('profile.show', compact('user'));
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::find(Session::get('user_id'));

        if (!$user) {
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Usuario no encontrado.');
        }

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $path = $request->file('profile_photo')->store('profiles', 'public');

        $user->profile_photo = $path;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Foto actualizada correctamente.');
    }

    public function removePhoto(): RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        $user = User::find(Session::get('user_id'));

        if (!$user) {
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Usuario no encontrado.');
        }

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->profile_photo = null;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Foto eliminada correctamente.');
    }

    public function destroy(): RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')
                ->with('error', 'Debes iniciar sesión.');
        }

        $user = User::find(Session::get('user_id'));

        if (!$user) {
            Session::flush();

            return redirect()->route('login.form')
                ->with('error', 'Usuario no encontrado.');
        }

        if ($user->email === 'pinillar100@outlook.es') {
            return back()->with(
                'error',
                'No puedes eliminar la cuenta del administrador principal.'
            );
        }

        $pendingProjects = Project::where('user_id', $user->id)
            ->where('status', 'pendiente')
            ->exists();

        $pendingTasks = Task::where('assigned_user_id', $user->id)
            ->where('status', 'pendiente')
            ->exists();

        if ($pendingProjects || $pendingTasks) {
            return back()->with(
                'error',
                'No puedes eliminar tu cuenta porque todavía tienes proyectos o tareas pendientes.'
            );
        }

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Session::flush();


 
Project::where('user_id', $user->id)->delete();

Task::where('assigned_user_id', $user->id)->delete();


$user->managedUsers()->detach();
$user->managers()->detach();

        $user->delete();

        return redirect()->route('login.form')
            ->with('success', 'Cuenta eliminada correctamente.');
    }
}