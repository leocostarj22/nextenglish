<?php

namespace App\Http\Controllers;

use App\Jobs\EvaluateSpeakingJob;
use App\Models\GeneratedQuestion;
use App\Models\PracticeTurn;
use App\Services\AiCoachService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use RuntimeException;

class PracticeController extends Controller
{
    public function __construct(private readonly AiCoachService $ai)
    {
    }

    public function evaluate(Request $request)
    {
        $data = $request->validate([
            'scenario' => ['required', 'string', 'max:120'],
            'question' => ['sometimes', 'string', 'max:2000'],
            'generated_questions_id' => ['sometimes', 'integer', 'min:1'],
            'native_language' => ['sometimes', 'string', 'max:40'],
            'user_input' => ['required', 'string', 'max:2000'],
        ]);

        $nativeLanguage = $data['native_language'] ?? $request->header('Accept-Language') ?? config('app.locale', 'en');

        $turn = PracticeTurn::query()->create([
            'user_id' => $request->user()->id,
            'status' => 'processing',
            'scenario' => $data['scenario'],
            'question' => $data['question'] ?? null,
            'generated_questions_id' => $data['generated_questions_id'] ?? null,
            'user_input' => $data['user_input'],
        ]);

        EvaluateSpeakingJob::dispatch(
            $turn->id,
            $data['user_input'],
            $data['scenario'],
            (string) $nativeLanguage,
        );

        return response()->json([
            'turn_id' => $turn->id,
            'status' => 'processing',
        ]);
    }

    public function questions(Request $request)
    {
        set_time_limit((int) env('AI_MAX_EXECUTION_TIME', 180));

        $data = $request->validate([
            'scenario' => ['required', 'string', 'max:120'],
        ]);

        try {
            $ai = $this->ai->generateQuestions($data['scenario']);
        } catch (ConnectionException $e) {
            report($e);

            return response()->json([
                'message' => config('app.debug')
                    ? ('Não foi possível conectar ao provedor de I.A.: ' . $e->getMessage())
                    : 'Não foi possível conectar ao provedor de I.A.',
            ], 502);
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
            if (preg_match('/^AI gateway error \((\d+)\):\s*(.*)$/s', $message, $m)) {
                $status = (int) $m[1];
                $body = trim($m[2]);
                $decoded = json_decode($body, true);

                return response()->json([
                    'message' => (string) (data_get($decoded, 'error.message') ?? data_get($decoded, 'message') ?? $body ?: $message),
                ], $status);
            }

            return response()->json([
                'message' => $message,
            ], 500);
        }

        $row = GeneratedQuestion::query()->create([
            'user_id' => $request->user()->id,
            'scenario' => $data['scenario'],
            'questions_json' => json_encode($ai['result'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'model' => $ai['model'] ?? null,
            'tokens_in' => $ai['tokens_in'] ?? null,
            'tokens_out' => $ai['tokens_out'] ?? null,
            'latency_ms' => $ai['latency_ms'] ?? null,
        ]);

        return response()->json([
            ...$ai['result'],
            'generated_questions_id' => $row->id,
        ]);
    }
}