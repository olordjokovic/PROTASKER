<?php

namespace App\Http\Controllers;

use App\Mail\NewInternalMessageMail;
use App\Models\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class DashboardDetailController extends Controller
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

    public function projects()
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $projects = Project::with('user')
            ->whereIn('user_id', $this->allowedUserIds($user))
            ->latest()
            ->get();

        return view('dashboard.details.projects', compact('user', 'projects'));
    }

    public function pendingTasks()
    {
        return $this->tasksByStatus('pendiente', 'Tareas pendientes');
    }

    public function progressTasks()
    {
        return $this->tasksByStatus('en_progreso', 'Tareas en progreso');
    }

    public function completedTasks()
    {
        return $this->tasksByStatus('completada', 'Tareas completadas');
    }

    private function tasksByStatus(string $status, string $title)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $tasks = Task::with(['assignedUser', 'project'])
            ->whereIn('assigned_user_id', $this->allowedUserIds($user))
            ->where('status', $status)
            ->latest()
            ->get();

        return view('dashboard.details.tasks', compact('user', 'tasks', 'title', 'status'));
    }

    public function report(string $type, int $id)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        if (!in_array($type, ['project', 'task'])) {
            abort(404);
        }

        $item = $type === 'project'
            ? Project::findOrFail($id)
            : Task::with('project')->findOrFail($id);

        return view('dashboard.details.report', compact('user', 'type', 'item'));
    }

    public function sendReport(Request $request, string $type, int $id)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $request->validate([
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:5000',
        ]);

        if (!in_array($type, ['project', 'task'])) {
            abort(404);
        }

        $item = $type === 'project'
            ? Project::findOrFail($id)
            : Task::with('project')->findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'El administrador no necesita informar problemas al administrador.');
        }

        $receiver = $user->isGestor()
            ? User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first()
            : $user->managers()->first();

        if (!$receiver) {
            $receiver = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first();
        }

        if (!$receiver) {
            return back()->with('error', 'No se encontró destinatario para el aviso.');
        }

        $context = $type === 'project'
            ? 'Proyecto: ' . $item->name
            : 'Tarea: ' . $item->title;

        $body = "Aviso relacionado con {$context}\n\n"
            . "Enviado por: {$user->name} {$user->surname}\n"
            . "Email: {$user->email}\n\n"
            . "Mensaje:\n"
            . $request->message;

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'subject' => htmlspecialchars(trim($request->subject), ENT_QUOTES, 'UTF-8'),
            'body' => htmlspecialchars(trim($body), ENT_QUOTES, 'UTF-8'),
            'read' => false,
        ]);

        Mail::to($receiver->email)->send(
            new NewInternalMessageMail($message, $user, $receiver)
        );

        return redirect()->route('messages.index')
            ->with('success', 'Aviso enviado correctamente.');
    }
}