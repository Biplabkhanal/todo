<?php

namespace App\Http\Controllers;

use App\Events\MessageDeliveredEvent;
use App\Events\MessageSentEvent;
use App\Events\MessageTypingEvent;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat', compact('users'));
    }

    public function fetchMessages($recipientId)
    {
        $userId = Auth::id();

        $messages = Message::where(function ($query) use ($userId, $recipientId) {
            $query->where('sender_id', $userId)
                ->where('recipient_id', $recipientId);
        })
            ->orWhere(function ($query) use ($userId, $recipientId) {
                $query->where('sender_id', $recipientId)
                    ->where('recipient_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->message
        ]);

        MessageSentEvent::dispatch($message);
        $this->sendStatus($message);
        return response()->json($message);
    }

    public function typingStatus(Request $request)
    {
        $senderId = Auth::id();
        $recipientId = $request->recipient_id;
        $isTyping = $request->is_typing;
        MessageTypingEvent::dispatch($senderId, $recipientId, $isTyping);
        return response()->json(['status' => 'Typing event broadcasted']);
    }

    public static function sendStatus($message)
    {
        $message->update(['status' => 'delivered']);
        MessageDeliveredEvent::dispatch($message->id, 'delivered', auth()->id());
        return;
    }
}
