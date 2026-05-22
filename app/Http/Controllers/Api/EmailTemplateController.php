<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(): JsonResponse
    {
        $templates = EmailTemplate::orderBy('name')
            ->get(['key', 'name', 'subject', 'variables', 'updated_at']);
        return response()->json(['data' => $templates]);
    }

    public function show(string $key): JsonResponse
    {
        $template = EmailTemplate::findOrFail($key);
        return response()->json(['data' => $template]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $template = EmailTemplate::findOrFail($key);

        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $template->update($data);
        return response()->json(['data' => $template]);
    }
}
