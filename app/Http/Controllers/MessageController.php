<?php

namespace App\Http\Controllers;

use App\Mail\NewInternalMessageMail;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller
{
    private function currentUser(): ?User
    {
        if (!Session::has('user_id')) {
            return null;
        }

        return User::find(Session::get('user_id'));
    }

    public function index()
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $messages = Message::with('sender')
            ->where('receiver_id', $user->id)
            ->latest()
            ->get();

        return view('messages.index', [
            'user' => $user,
            'messages' => $messages,
        ]);
    }

    public function create()
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        /*
          Usuarios/gestores solo pueden escribir al admin
        */
        if (!$user->isAdmin()) {

            $receivers = User::whereHas('role', function ($q) {
                $q->where('name', 'admin');
            })->get();

        } else {

            /*
              El admin puede escribir a todos
            */
            $receivers = User::where('id', '!=', $user->id)->get();
        }

        return view('messages.create', [
            'user' => $user,
            'receivers' => $receivers,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();

        if (!$user) {
            return redirect()->route('login.form');
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:5000',
        ]);

        $receiver = User::find($request->receiver_id);

        /*
          Restricción:
          usuarios/gestores SOLO al admin
        */
        if (
            !$user->isAdmin() &&
            !$receiver->isAdmin()
        ) {
            return back()->with(
                'error',
                'Solo puedes enviar mensajes al administrador.'
            );
        }

       $message = Message::create([
    'sender_id' => $user->id,
    'receiver_id' => $receiver->id,
    'subject' => htmlspecialchars(trim($request->subject), ENT_QUOTES, 'UTF-8'),
    'body' => htmlspecialchars(trim($request->message), ENT_QUOTES, 'UTF-8'),
    'read' => false,
]);

        /*
          Envío email
        */
        Mail::to($receiver->email)->send(
            new NewInternalMessageMail(
                $message,
                $user,
                $receiver
            )
        );

        return redirect()
            ->route('messages.index')
            ->with('success', 'Mensaje enviado correctamente.');
    }

   public function show(Message $message)
{
    $user = $this->currentUser();

    if (!$user) {
        return redirect()->route('login.form');
    }

    if ($message->receiver_id !== $user->id) {
        abort(403);
    }

    if (!$message->read) {
        $message->update([
            'read' => true,
        ]);
    }

    if (
        $user->isAdmin() &&
        $message->completion_request_id
    ) {
        return redirect()->route(
            'completion.admin.show',
            $message->completion_request_id
        );
    }

    if ($message->completion_request_id) {
    $completionRequest = \App\Models\CompletionRequest::find($message->completion_request_id);

    if ($completionRequest) {
        $item = null;

        if ($completionRequest->item_type === 'project') {
            $item = \App\Models\Project::find($completionRequest->item_id);
        }

        if ($completionRequest->item_type === 'task') {
            $item = \App\Models\Task::find($completionRequest->item_id);
        }

        return view('completion.user-show', [
            'user' => $user,
            'completionRequest' => $completionRequest,
            'item' => $item,
        ]);
    }
}

    return view('messages.show', [
        'user' => $user,
        'message' => $message,
    ]);
}

    public function reply(Message $message)
{
    $user = $this->currentUser();

    if (!$user) {
        return redirect()->route('login.form');
    }

    if ($message->receiver_id !== $user->id) {
        abort(403);
    }

    return view('messages.reply', [
        'user' => $user,
        'message' => $message,
        'receiver' => $message->sender,
    ]);
}

public function storeReply(Request $request, Message $message)
{
    $user = $this->currentUser();

    if (!$user) {
        return redirect()->route('login.form');
    }

    if ($message->receiver_id !== $user->id) {
        abort(403);
    }

    $request->validate([
        'message' => 'required|string|max:5000',
    ]);

    $receiver = $message->sender;

    $reply = Message::create([
        'sender_id' => $user->id,
        'receiver_id' => $receiver->id,
        'subject' => 'RE: ' . $message->subject,
        'body' => htmlspecialchars(trim($request->message), ENT_QUOTES, 'UTF-8'),
        'read' => false,
    ]);

    Mail::to($receiver->email)->send(
        new NewInternalMessageMail($reply, $user, $receiver)
    );

    return redirect()
        ->route('messages.index')
        ->with('success', 'Respuesta enviada correctamente.');
}
}