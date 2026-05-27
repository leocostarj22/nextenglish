<?php

namespace App\Http\Controllers;

use App\Models\PlacementTestQuestion;
use App\Models\UserStats;
use Illuminate\Http\Request;

class PlacementTestController extends Controller
{
    private const CEFR_ORDER = ['A1' => 1, 'A2' => 2, 'B1' => 3, 'B2' => 4, 'C1' => 5, 'C2' => 6];

    public function index()
    {
        $questions = PlacementTestQuestion::orderBy('cefr_level')->orderBy('order')->get()
            ->map(fn ($q) => [
                'id' => $q->id,
                'cefr_level' => $q->cefr_level,
                'order' => $q->order,
                'question' => $q->question,
                'options' => $q->options,
            ]);

        return response()->json(['questions' => $questions]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'string'],
        ]);

        $questions = PlacementTestQuestion::whereIn('id', array_keys($data['answers']))->get()->keyBy('id');

        $scoresByLevel = [];
        $totalByLevel = [];

        foreach ($data['answers'] as $questionId => $answer) {
            $question = $questions[$questionId] ?? null;
            if (! $question) {
                continue;
            }

            $level = $question->cefr_level;
            $totalByLevel[$level] = ($totalByLevel[$level] ?? 0) + 1;
            $isCorrect = mb_strtolower(trim($answer)) === mb_strtolower(trim($question->correct_answer));
            $scoresByLevel[$level] = ($scoresByLevel[$level] ?? 0) + ($isCorrect ? 1 : 0);
        }

        $recommended = $this->calculateRecommendedLevel($scoresByLevel, $totalByLevel);

        return response()->json([
            'recommended_level' => $recommended,
            'score_breakdown' => collect(array_keys(self::CEFR_ORDER))->map(fn ($lvl) => [
                'level' => $lvl,
                'correct' => $scoresByLevel[$lvl] ?? 0,
                'total' => $totalByLevel[$lvl] ?? 0,
            ])->values(),
        ]);
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'level' => ['required', 'string', 'in:A1,A2,B1,B2,C1,C2'],
        ]);

        $userId = $request->user()->id;
        $stats = UserStats::firstOrCreate(
            ['user_id' => $userId],
            ['total_xp' => 0, 'current_streak' => 0, 'longest_streak' => 0, 'current_cefr_level' => 'A1']
        );

        $stats->update([
            'placement_level' => $data['level'],
            'current_cefr_level' => $data['level'],
        ]);

        return response()->json(['ok' => true, 'placement_level' => $data['level']]);
    }

    private function calculateRecommendedLevel(array $scoresByLevel, array $totalByLevel): string
    {
        foreach (array_keys(self::CEFR_ORDER) as $level) {
            $total = $totalByLevel[$level] ?? 0;
            $correct = $scoresByLevel[$level] ?? 0;

            if ($total === 0) {
                continue;
            }

            $passThreshold = ceil($total * 0.66);
            if ($correct < $passThreshold) {
                return $level;
            }
        }

        return 'C2';
    }
}
