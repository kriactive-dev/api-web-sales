<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionBot;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return response()->json(QuestionBot::with('options')->orderBy('id', 'asc')->paginate());
    }

    public function show($id)
    {
        return response()->json(QuestionBot::with('options.nextQuestion')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:text,button',
            'is_start' => 'boolean',
            'active' => 'boolean',
        ]);

        $question = QuestionBot::create($validated);

        return response()->json($question, 201);
    }

    public function update(Request $request, $id)
    {
        $question = QuestionBot::findOrFail($id);

        $validated = $request->validate([
            'text' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:text,button',
            'is_start' => 'sometimes|boolean',
            'active' => 'sometimes|boolean',
        ]);

        $question->update($validated);

        return response()->json($question);
    }

    public function destroy($id)
    {
        QuestionBot::destroy($id);
        return response()->json(['success' => true]);
    }
}
