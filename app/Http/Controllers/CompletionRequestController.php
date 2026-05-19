<?php

namespace App\Http\Controllers;

use App\Mail\CompletionDecisionMail;
use App\Mail\CompletionRequestMail;
use App\Models\CompletionRequest;
use App\Models\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CompletionRequestController extends Controller
{
    private function currentUser(): ?User
    {
        if (!Session::has('user_id')) {
            return null;
        }

        return User::with('role')->find(Session::get('user_id'));
    }

    private function admin(): ?User
    {
        return User::where('email', 'pinillar100@outlook.es')->first();
    }

    private function item(string $type, int $id)
    {
        if ($type === 'project') {
            return Project::findOrFail($id);
        }

        if ($type === 'task') {
            return Task::findOrFail($id);
        }

        abort(404);
    }

    public function create(string $type, int $id)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $item = $this->item($type, $id);

        return view('completion.create', compact('user', 'type', 'item'));
    }

    public function store(Request $request, string $type, int $id)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $request->validate([
            'description' => 'required|string|max:5000',
            'evidence' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $item = $this->item($type, $id);

        $evidencePath = null;

        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('completion_evidence', 'public');
        }

        $completionRequest = CompletionRequest::create([
            'user_id' => $user->id,
            'item_type' => $type,
            'item_id' => $id,
            'description' => htmlspecialchars(trim($request->description), ENT_QUOTES, 'UTF-8'),
            'evidence_path' => $evidencePath,
            'status' => 'pendiente',
        ]);

        $admin = $this->admin();

        if ($admin) {
            Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $admin->id,
                'completion_request_id' => $completionRequest->id,
                'subject' => 'Solicitud de finalización pendiente',
                'body' => 'El usuario ' . $user->name . ' ha solicitado marcar como completado: ' . ($item->name ?? $item->title),
                'read' => false,
            ]);

            Mail::to($admin->email)->send(
                new CompletionRequestMail($completionRequest, $user, $item)
            );
        }

        return redirect()->route('dashboard')
            ->with('success', 'Solicitud de finalización enviada al administrador.');
    }

    public function adminIndex()
    {
        $user = $this->currentUser();

        if (!$user || !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        $requests = CompletionRequest::with('user')
            ->where('status', 'pendiente')
            ->latest()
            ->get();

        return view('completion.admin-index', compact('user', 'requests'));
    }

    public function adminShow(CompletionRequest $completionRequest)
    {
        $user = $this->currentUser();

        if (!$user || !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        if ($completionRequest->status !== 'pendiente') {
            return redirect()->route('completion.admin.index')
                ->with('error', 'Esta solicitud ya fue resuelta.');
        }

        $item = $this->item($completionRequest->item_type, $completionRequest->item_id);

        return view('completion.admin-show', compact('user', 'completionRequest', 'item'));
    }

    public function approve(Request $request, CompletionRequest $completionRequest)
    {
        return $this->resolve($request, $completionRequest, 'aprobada');
    }

    public function reject(Request $request, CompletionRequest $completionRequest)
    {
        return $this->resolve($request, $completionRequest, 'rechazada');
    }

    private function resolve(Request $request, CompletionRequest $completionRequest, string $status)
    {
        $admin = $this->currentUser();

        if (!$admin || !$admin->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Acceso no autorizado.');
        }

        if ($completionRequest->status !== 'pendiente') {
            return redirect()->route('completion.admin.index')
                ->with('error', 'Esta solicitud ya fue resuelta.');
        }

        $request->validate([
            'admin_response' => 'required|string|max:5000',
            'admin_evidence' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $adminEvidencePath = null;

        if ($request->hasFile('admin_evidence')) {
            $adminEvidencePath = $request->file('admin_evidence')->store('completion_admin_evidence', 'public');
        }

        $completionRequest->update([
            'status' => $status,
            'admin_response' => htmlspecialchars(trim($request->admin_response), ENT_QUOTES, 'UTF-8'),
            'admin_evidence_path' => $adminEvidencePath,
        ]);

        Message::where('completion_request_id', $completionRequest->id)
            ->where('receiver_id', $admin->id)
            ->delete();

        $item = $this->item($completionRequest->item_type, $completionRequest->item_id);

        if ($status === 'aprobada') {
            if ($completionRequest->item_type === 'project') {
                $item->update(['status' => 'completado']);
            } else {
                $item->update(['status' => 'completada']);
            }
        }

        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $completionRequest->user_id,
            'completion_request_id' => $completionRequest->id,
            'subject' => 'Decisión sobre solicitud de finalización',
            'body' => 'Tu solicitud ha sido ' . $status . '. Respuesta: ' . $completionRequest->admin_response,
            'read' => false,
        ]);

        Mail::to($completionRequest->user->email)->send(
            new CompletionDecisionMail($completionRequest, $admin, $item)
        );

        return redirect()->route('completion.admin.index')
            ->with('success', 'Solicitud ' . $status . ' correctamente.');
    }
}