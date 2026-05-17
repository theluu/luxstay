<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = ContactMessage::latest()->paginate(50);
        return response()->json($messages);
    }

    public function markRead(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }
}
