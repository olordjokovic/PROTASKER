<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.form')->with('error', 'Debes iniciar sesión.');
        }

        $user = User::with('role', 'managedUsers')->find(Session::get('user_id'));

        if (!$user) {
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Usuario no encontrado.');
        }

        if ($user->isAdmin()) {
            $pageTitle = 'Panel de administrador';

            $totalProjects = Project::count();
            $pendingTasks = Task::where('status', 'pendiente')->count();
            $inProgressTasks = Task::where('status', 'en_progreso')->count();
            $completedTasks = Task::where('status', 'completada')->count();

            $dashboardMessage = 'Bienvenido a tu área de administrador, ' . $user->name;
            $dashboardDescription = 'Desde aquí puedes supervisar todos los proyectos, tareas, usuarios y gestores del sistema.';
        } elseif ($user->isGestor()) {
            $pageTitle = 'Panel de gestor';

            $managedUserIds = $user->managedUsers()
                ->pluck('users.id')
                ->push($user->id)
                ->unique()
                ->toArray();

            $totalProjects = Project::whereIn('user_id', $managedUserIds)->count();

            $pendingTasks = Task::whereIn('assigned_user_id', $managedUserIds)
                ->where('status', 'pendiente')
                ->count();

            $inProgressTasks = Task::whereIn('assigned_user_id', $managedUserIds)
                ->where('status', 'en_progreso')
                ->count();

            $completedTasks = Task::whereIn('assigned_user_id', $managedUserIds)
                ->where('status', 'completada')
                ->count();

            $dashboardMessage = 'Bienvenido a tu área de gestor, ' . $user->name;
            $dashboardDescription = 'Desde aquí puedes consultar y gestionar los proyectos y tareas de los usuarios que tienes asignados.';
        } else {
            $pageTitle = 'Panel de usuario';

            $totalProjects = Project::where('user_id', $user->id)->count();

            $pendingTasks = Task::where('assigned_user_id', $user->id)
                ->where('status', 'pendiente')
                ->count();

            $inProgressTasks = Task::where('assigned_user_id', $user->id)
                ->where('status', 'en_progreso')
                ->count();

            $completedTasks = Task::where('assigned_user_id', $user->id)
                ->where('status', 'completada')
                ->count();

            $dashboardMessage = 'Bienvenido a tu área de usuario, ' . $user->name;
            $dashboardDescription = 'Desde aquí podrás consultar tus proyectos, tareas asignadas, editar tu perfil y, si eres administrador, gestionar las entidades del sistema.';
        }

        return view('dashboard.index', compact(
            'user',
            'pageTitle',
            'totalProjects',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'dashboardMessage',
            'dashboardDescription'
        ));
    }
}