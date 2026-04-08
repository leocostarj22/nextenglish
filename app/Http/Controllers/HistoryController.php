<?php

namespace App\Http\Controllers;

use App\Models\PracticeTurn;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'scenario' => ['sometimes', 'string', 'max:120'],
            'min_score' => ['sometimes', 'integer', 'min:0', 'max:10'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $query = PracticeTurn::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id');

        if (isset($data['scenario'])) {
            $query->where('scenario', $data['scenario']);
        }

        if (isset($data['min_score'])) {
            $query->where('score', '>=', $data['min_score']);
        }

        $perPage = $data['per_page'] ?? 20;

        return response()->json(
            $query->paginate($perPage)
        );
    }

    public function show(Request $request, PracticeTurn $turn)
    {
        if ($turn->user_id !== $request->user()->id) {
            abort(404);
        }

        return response()->json($turn);
    }

    public function destroy(Request $request, PracticeTurn $turn)
    {
        if ($turn->user_id !== $request->user()->id) {
            abort(404);
        }

        $turn->delete();

        return response()->json(['ok' => true]);
    }

    public function clear(Request $request)
    {
        PracticeTurn::query()
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['ok' => true]);
    }
}