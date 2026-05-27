<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AiCoachService
{
    public function evaluateSpeaking(string $userInput, string $scenario, string $nativeLanguage = 'pt-PT'): array
    {
        $model = (string) config('services.openai.model', 'gpt-4o-mini');
        $key = (string) config('services.openai.key');
        $baseUrl = (string) config('services.openai.base_url', 'https://api.openai.com/v1');
        $referer = (string) (config('services.openai.referer') ?? '');
        $appTitle = (string) (config('services.openai.app_title') ?? '');
        $sslVerify = config('services.openai.ssl_verify', true);
        $caBundle = (string) (config('services.openai.ca_bundle') ?? '');

        $baseUrl = rtrim($baseUrl, '/');
        $isOllama = str_contains($baseUrl, 'localhost:11434') || str_contains($baseUrl, '127.0.0.1:11434');

        if (! $isOllama && $key === '') {
            throw new RuntimeException('OPENAI_API_KEY não configurada.');
        }

        $system = "You are an English coach specialized in helping software developers improve their speaking skills for real-world scenarios such as job interviews, meetings, and technical discussions.\n\nYour goal is to:\n- Improve the user's sentence\n- Explain mistakes clearly and simply\n- Suggest more natural, professional alternatives\n- Focus on real spoken English (not overly formal or robotic)\n\nAlways assume the user is a non-native English speaker working in tech.\n\nBe concise, practical, and supportive.\n\nAvoid complex linguistic terminology. Keep explanations simple.\n\nLanguage requirements:\n- The fields corrected and improved must be in English.\n- The fields explanation and pronunciation_tip must be written in the user's native language: {$nativeLanguage}.\n\nAlways respond with valid JSON only.";

        $user = "User said (spoken English):\n\n\"{$userInput}\"\n\nContext:\n- The user is a software developer\n- The situation is: {$scenario}\n\nTasks:\n1. Rewrite the sentence correctly\n2. Suggest a more natural/professional version\n3. Explain the mistakes (simple explanation)\n4. Give a pronunciation tip (if applicable)\n5. Give a score from 0 to 10 for fluency and clarity\n\nRespond in this JSON format:\n\n{\n  \"original\": \"...\",\n  \"corrected\": \"...\",\n  \"improved\": \"...\",\n  \"explanation\": \"...\",\n  \"pronunciation_tip\": \"...\",\n  \"score\": 0\n}";

        $provider = (string) config('services.ai.provider', 'openai');

        if ($provider === 'anthropic') {
            $anthropicKey = (string) config('services.anthropic.key');
            $anthropicModel = (string) config('services.anthropic.model', 'claude-3-5-sonnet-20241022');
            $anthropicBaseUrl = rtrim((string) config('services.anthropic.base_url', 'https://api.anthropic.com'), '/');
            $anthropicVersion = (string) config('services.anthropic.version', '2023-06-01');
            $maxTokens = (int) config('services.anthropic.max_tokens_evaluate', 900);

            if ($anthropicKey === '') {
                throw new RuntimeException('ANTHROPIC_API_KEY não configurada.');
            }

            $payload = [
                'model' => $anthropicModel,
                'max_tokens' => $maxTokens,
                'temperature' => 0.2,
                'system' => $system,
                'messages' => [
                    ['role' => 'user', 'content' => $user],
                ],
            ];

            $start = hrtime(true);

            $request = Http::asJson()
                ->withHeaders([
                    'x-api-key' => $anthropicKey,
                    'anthropic-version' => $anthropicVersion,
                ])
                ->timeout(60)
                ->retry(2, 200)
                ->acceptJson();

            if ($sslVerify === false || $sslVerify === 'false' || $sslVerify === 0 || $sslVerify === '0') {
                $request = $request->withoutVerifying();
            } elseif ($caBundle !== '') {
                $request = $request->withOptions(['verify' => $caBundle]);
            }

            $response = $request->post("{$anthropicBaseUrl}/v1/messages", $payload);

            $latencyMs = (int) ((hrtime(true) - $start) / 1_000_000);

            if (! $response->successful()) {
                $body = $response->body();
                throw new RuntimeException("AI gateway error ({$response->status()}): {$body}");
            }

            $json = $response->json();
            $blocks = data_get($json, 'content', []);
            $text = '';

            if (is_array($blocks)) {
                foreach ($blocks as $block) {
                    if (is_array($block) && ($block['type'] ?? null) === 'text' && is_string($block['text'] ?? null)) {
                        $text = $block['text'];
                        break;
                    }
                }
            }

            $decoded = $text !== '' ? json_decode($text, true) : null;
            if (! is_array($decoded)) {
                $decoded = $this->extractJsonObject($text);
            }

            $normalized = $this->normalizeEvaluation($decoded ?? [], $userInput);

            return [
                'result' => $normalized,
                'raw' => $text !== '' ? $text : null,
                'model' => (string) (data_get($json, 'model') ?? $anthropicModel),
                'tokens_in' => (int) (data_get($json, 'usage.input_tokens') ?? 0),
                'tokens_out' => (int) (data_get($json, 'usage.output_tokens') ?? 0),
                'latency_ms' => $latencyMs,
            ];
        }

        $payload = $isOllama
            ? [
                'model' => $model,
                'stream' => false,
                'options' => ['temperature' => 0.2],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ]
            : [
                'model' => $model,
                'temperature' => 0.2,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ];

        $start = hrtime(true);

        $timeoutSeconds = $isOllama ? 120 : 30;
        $retryTimes = $isOllama ? 0 : 2;

        $request = ($isOllama ? Http::asJson() : Http::withToken($key))
            ->timeout($timeoutSeconds)
            ->retry($retryTimes, 200)
            ->acceptJson();

        if ($sslVerify === false || $sslVerify === 'false' || $sslVerify === 0 || $sslVerify === '0') {
            $request = $request->withoutVerifying();
        } elseif ($caBundle !== '') {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        if ($referer !== '') {
            $request = $request->withHeaders(['HTTP-Referer' => $referer]);
        }

        if ($appTitle !== '') {
            $request = $request->withHeaders(['X-Title' => $appTitle]);
        }

        $endpoint = $isOllama ? "{$baseUrl}/api/chat" : "{$baseUrl}/chat/completions";
        $response = $request->post($endpoint, $payload);

        $latencyMs = (int) ((hrtime(true) - $start) / 1_000_000);

        if (! $response->successful()) {
            $body = $response->body();
            throw new RuntimeException("AI gateway error ({$response->status()}): {$body}");
        }

        $json = $response->json();

        $content = $isOllama
            ? data_get($json, 'message.content')
            : data_get($json, 'choices.0.message.content');

        $decoded = is_string($content) ? json_decode($content, true) : null;

        if (! is_array($decoded)) {
            $decoded = $this->extractJsonObject(is_string($content) ? $content : '');
        }

        $normalized = $this->normalizeEvaluation($decoded ?? [], $userInput);

        return [
            'result' => $normalized,
            'raw' => is_string($content) ? $content : null,
            'model' => data_get($json, 'model', $model),
            'tokens_in' => (int) ($isOllama ? (data_get($json, 'prompt_eval_count') ?? 0) : (data_get($json, 'usage.prompt_tokens') ?? 0)),
            'tokens_out' => (int) ($isOllama ? (data_get($json, 'eval_count') ?? 0) : (data_get($json, 'usage.completion_tokens') ?? 0)),
            'latency_ms' => $latencyMs,
        ];
    }

    public function generateQuestions(string $scenario): array
    {
        $model = (string) config('services.openai.model', 'gpt-4o-mini');
        $key = (string) config('services.openai.key');
        $baseUrl = (string) config('services.openai.base_url', 'https://api.openai.com/v1');
        $referer = (string) (config('services.openai.referer') ?? '');
        $appTitle = (string) (config('services.openai.app_title') ?? '');
        $sslVerify = config('services.openai.ssl_verify', true);
        $caBundle = (string) (config('services.openai.ca_bundle') ?? '');

        $baseUrl = rtrim($baseUrl, '/');
        $isOllama = str_contains($baseUrl, 'localhost:11434') || str_contains($baseUrl, '127.0.0.1:11434');

        if (! $isOllama && $key === '') {
            throw new RuntimeException('OPENAI_API_KEY não configurada.');
        }

        $system = "You are an English coach specialized in helping software developers improve their speaking skills for real-world scenarios.\n\nAlways respond with valid JSON only.";

        $user = "Generate 5 realistic questions for a software developer in the context of: {$scenario}.\n\nRequirements:\n- Questions must be natural and commonly used in real situations\n- Keep them short and clear\n- Focus on speaking practice\n\nReturn as JSON:\n\n{\n  \"questions\": [\n    \"...\",\n    \"...\",\n    \"...\",\n    \"...\",\n    \"...\"\n  ]\n}";

        $provider = (string) config('services.ai.provider', 'openai');

        if ($provider === 'anthropic') {
            $anthropicKey = (string) config('services.anthropic.key');
            $anthropicModel = (string) config('services.anthropic.model', 'claude-3-5-sonnet-20241022');
            $anthropicBaseUrl = rtrim((string) config('services.anthropic.base_url', 'https://api.anthropic.com'), '/');
            $anthropicVersion = (string) config('services.anthropic.version', '2023-06-01');
            $maxTokens = (int) config('services.anthropic.max_tokens_questions', 400);

            if ($anthropicKey === '') {
                throw new RuntimeException('ANTHROPIC_API_KEY não configurada.');
            }

            $payload = [
                'model' => $anthropicModel,
                'max_tokens' => $maxTokens,
                'temperature' => 0.5,
                'system' => $system,
                'messages' => [
                    ['role' => 'user', 'content' => $user],
                ],
            ];

            $start = hrtime(true);

            $request = Http::asJson()
                ->withHeaders([
                    'x-api-key' => $anthropicKey,
                    'anthropic-version' => $anthropicVersion,
                ])
                ->timeout(60)
                ->retry(2, 200)
                ->acceptJson();

            if ($sslVerify === false || $sslVerify === 'false' || $sslVerify === 0 || $sslVerify === '0') {
                $request = $request->withoutVerifying();
            } elseif ($caBundle !== '') {
                $request = $request->withOptions(['verify' => $caBundle]);
            }

            $response = $request->post("{$anthropicBaseUrl}/v1/messages", $payload);

            $latencyMs = (int) ((hrtime(true) - $start) / 1_000_000);

            if (! $response->successful()) {
                $body = $response->body();
                throw new RuntimeException("AI gateway error ({$response->status()}): {$body}");
            }

            $json = $response->json();
            $blocks = data_get($json, 'content', []);
            $text = '';

            if (is_array($blocks)) {
                foreach ($blocks as $block) {
                    if (is_array($block) && ($block['type'] ?? null) === 'text' && is_string($block['text'] ?? null)) {
                        $text = $block['text'];
                        break;
                    }
                }
            }

            $decoded = $text !== '' ? json_decode($text, true) : null;
            if (! is_array($decoded)) {
                $decoded = $this->extractJsonObject($text);
            }

            $questions = data_get($decoded, 'questions', []);
            if (! is_array($questions)) {
                $questions = [];
            }

            $questions = array_values(array_filter($questions, fn ($q) => is_string($q) && trim($q) !== ''));
            $questions = array_slice($questions, 0, 5);

            return [
                'result' => ['questions' => $questions],
                'raw' => $text !== '' ? $text : null,
                'model' => (string) (data_get($json, 'model') ?? $anthropicModel),
                'tokens_in' => (int) (data_get($json, 'usage.input_tokens') ?? 0),
                'tokens_out' => (int) (data_get($json, 'usage.output_tokens') ?? 0),
                'latency_ms' => $latencyMs,
            ];
        }

        $cacheKey = 'ai_q_' . md5(serialize([$scenario]));
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $payload = $isOllama
            ? [
                'model' => $model,
                'stream' => false,
                'options' => ['temperature' => 0.5],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ]
            : [
                'model' => $model,
                'temperature' => 0.5,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ];

        $start = hrtime(true);

        $timeoutSeconds = $isOllama ? 120 : 30;
        $retryTimes = $isOllama ? 0 : 2;

        $request = ($isOllama ? Http::asJson() : Http::withToken($key))
            ->timeout($timeoutSeconds)
            ->retry($retryTimes, 200)
            ->acceptJson();

        if ($sslVerify === false || $sslVerify === 'false' || $sslVerify === 0 || $sslVerify === '0') {
            $request = $request->withoutVerifying();
        } elseif ($caBundle !== '') {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        if ($referer !== '') {
            $request = $request->withHeaders(['HTTP-Referer' => $referer]);
        }

        if ($appTitle !== '') {
            $request = $request->withHeaders(['X-Title' => $appTitle]);
        }

        $endpoint = $isOllama ? "{$baseUrl}/api/chat" : "{$baseUrl}/chat/completions";
        $response = $request->post($endpoint, $payload);

        $latencyMs = (int) ((hrtime(true) - $start) / 1_000_000);

        if (! $response->successful()) {
            $body = $response->body();
            throw new RuntimeException("AI gateway error ({$response->status()}): {$body}");
        }

        $json = $response->json();

        $content = $isOllama
            ? data_get($json, 'message.content')
            : data_get($json, 'choices.0.message.content');
        $decoded = is_string($content) ? json_decode($content, true) : null;

        if (! is_array($decoded)) {
            $decoded = $this->extractJsonObject(is_string($content) ? $content : '');
        }

        $questions = data_get($decoded, 'questions', []);
        if (! is_array($questions)) {
            $questions = [];
        }

        $questions = array_values(array_filter($questions, fn ($q) => is_string($q) && trim($q) !== ''));
        $questions = array_slice($questions, 0, 5);

        $result = [
            'result' => ['questions' => $questions],
            'raw' => is_string($content) ? $content : null,
            'model' => data_get($json, 'model', $model),
            'tokens_in' => (int) ($isOllama ? (data_get($json, 'prompt_eval_count') ?? 0) : (data_get($json, 'usage.prompt_tokens') ?? 0)),
            'tokens_out' => (int) ($isOllama ? (data_get($json, 'eval_count') ?? 0) : (data_get($json, 'usage.completion_tokens') ?? 0)),
            'latency_ms' => $latencyMs,
        ];

        Cache::put($cacheKey, $result, 86400);

        return $result;
    }

    private function extractJsonObject(string $text): array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start === false || $end === false || $end <= $start) {
            return [];
        }

        $json = substr($text, $start, $end - $start + 1);
        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function normalizeEvaluation(array $data, string $userInput): array
    {
        $score = $data['score'] ?? null;
        $scoreInt = is_numeric($score) ? (int) $score : null;

        if ($scoreInt !== null) {
            $scoreInt = max(0, min(10, $scoreInt));
        }

        return [
            'original' => is_string($data['original'] ?? null) && $data['original'] !== '' ? $data['original'] : $userInput,
            'corrected' => (string) ($data['corrected'] ?? ''),
            'improved' => (string) ($data['improved'] ?? ''),
            'explanation' => (string) ($data['explanation'] ?? ''),
            'pronunciation_tip' => (string) ($data['pronunciation_tip'] ?? ''),
            'score' => $scoreInt ?? 0,
        ];
    }
}