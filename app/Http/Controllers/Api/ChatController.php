<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Models\Conversation;

class ChatController extends Controller
{
    public function getMessageByConversationId(Conversation $conversation)
    {
        $this->validateAccesConversation($conversation);
        $messages = Chat::conversation($conversation)->setParticipant(Auth::user())->getMessages();

        return response()->json([
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'text' => 'required|string'
        ]);

        $this->validateAccesConversation($conversation);

        Chat::message($request->text)
        ->from(Auth::user())
        ->to($conversation)
        ->send();

        return response()->json(['message' => 'ok']);
    }

    protected function validateAccesConversation($conversation)
    {
        $participants = $conversation->getParticipants();
        $isParticipant = $participants->where('id', Auth::id())->first();
        abort_if(empty($isParticipant), 403, 'Access denied.');
    }
}
