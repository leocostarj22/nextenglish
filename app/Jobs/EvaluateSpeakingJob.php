<?php

namespace App\Jobs;

use App\Models\PracticeTurn;
use App\Services\AiCoachService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class EvaluateSpeakingJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private int $turnId,
        private string $userInput,
        private string $scenario,
        private string $nativeLanguage,
    ) {}

    public function handle(AiCoachService $ai): void
    {
        $turn = PracticeTurn::findOrFail($this->turnId);

        try {
            $result = $ai->evaluateSpeaking(
                $this->userInput,
                $this->scenario,
                $this->nativeLanguage,
            );

            $turn->update([
                'status' => 'completed',
                'ai_original_json' => $result['raw'],
                'corrected' => $result['result']['corrected'] ?? '',
                'improved' => $result['result']['improved'] ?? '',
                'explanation' => $result['result']['explanation'] ?? '',
                'pronunciation_tip' => $result['result']['pronunciation_tip'] ?? '',
                'score' => $result['result']['score'] ?? 0,
                'model' => $result['model'] ?? null,
                'tokens_in' => $result['tokens_in'] ?? null,
                'tokens_out' => $result['tokens_out'] ?? null,
                'latency_ms' => $result['latency_ms'] ?? null,
            ]);
        } catch (\Throwable $e) {
            $turn->update(['status' => 'failed']);
            throw $e;
        }
    }
}
