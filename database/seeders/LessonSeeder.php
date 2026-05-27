<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonModule;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedModule('A1', 1, 0,
            'A1 — Fundamentos',
            'Aprenda o básico do inglês: apresentações, perguntas simples e vocabulário essencial do dia a dia.',
            $this->a1Lessons()
        );

        $this->seedModule('A2', 2, 200,
            'A2 — Elementar',
            'Expanda seu vocabulário: passado, futuro, comparações e comunicação profissional básica.',
            $this->a2Lessons()
        );

        $this->seedModule('B1', 3, 600,
            'B1 — Intermediário',
            'Domine o Present Perfect, condicionais, voz passiva e phrasal verbs essenciais em TI.',
            $this->b1Lessons()
        );

        $this->seedModule('B2', 4, 1100,
            'B2 — Pré-Avançado',
            'Comunique-se com precisão: Past Perfect, discurso indireto, cláusulas relativas e apresentações técnicas.',
            $this->b2Lessons()
        );

        $this->seedModule('C1', 5, 1700,
            'C1 — Avançado',
            'Linguagem sofisticada: tempos perfeitos contínuos, inversões, hedging e idiomas profissionais.',
            $this->c1Lessons()
        );

        $this->seedModule('C2', 6, 2400,
            'C2 — Domínio',
            'Precisão nativa: distinções verbais sutis, registro, condicionais mistas e escrita de impacto.',
            $this->c2Lessons()
        );
    }

    private function seedModule(string $level, int $order, int $minXp, string $title, string $description, array $lessonsData): void
    {
        $module = LessonModule::updateOrCreate(
            ['cefr_level' => $level, 'order' => $order],
            ['title' => $title, 'description' => $description, 'min_xp_to_unlock' => $minXp]
        );

        foreach ($lessonsData as $lessonData) {
            $exercisesData = $lessonData['exercises'];
            unset($lessonData['exercises']);

            $lesson = Lesson::updateOrCreate(
                ['module_id' => $module->id, 'order' => $lessonData['order']],
                array_merge($lessonData, ['module_id' => $module->id])
            );

            foreach ($exercisesData as $i => $exerciseData) {
                Exercise::updateOrCreate(
                    ['lesson_id' => $lesson->id, 'order' => $i + 1],
                    array_merge(['xp_reward' => 10], $exerciseData, [
                        'lesson_id' => $lesson->id,
                        'order' => $i + 1,
                    ])
                );
            }
        }
    }

    // ─── A1 ──────────────────────────────────────────────────────────────────

    private function a1Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Apresentações e Verb To Be',
                'objective' => 'Greet people and introduce yourself using the verb "to be"',
                'grammar_point' => 'Verb "to be": am / is / are',
                'intro_text' => 'In this lesson, you will learn how to greet people and introduce yourself in English. The verb "to be" is one of the most important verbs — it tells us who someone is or what they do.',
                'vocabulary' => ['I am', 'you are', 'he is', 'she is', 'my name is', 'nice to meet you', 'hello', 'hi', "what's your name?", "I'm from", 'developer', 'engineer', 'student'],
                'examples' => [
                    ['en' => 'Hello! I am Ana. I am a software developer.', 'pt' => 'Olá! Eu sou Ana. Sou desenvolvedora de software.'],
                    ['en' => 'What is your name? My name is João.', 'pt' => 'Qual é o seu nome? Meu nome é João.'],
                    ['en' => "Nice to meet you! I'm from Brazil.", 'pt' => 'Prazer em conhecê-lo! Sou do Brasil.'],
                ],
                'tips' => [
                    "Use 'I'm' instead of 'I am' in casual speech — it sounds more natural.",
                    "'Am' is only used with 'I'. Never say 'You am' or 'He am'.",
                    "Add 'a' or 'an' before job titles: I am A developer, She is AN engineer.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Choose the correct form: "I ___ a software developer."', 'options' => ['am', 'is', 'are', 'be'], 'correct_answer' => 'am', 'explanation' => 'Use "am" with "I". Example: I am a developer.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence is correct?', 'options' => ['My name is João.', 'My name are João.', 'My name am João.', 'I name is João.'], 'correct_answer' => 'My name is João.', 'explanation' => '"My name is..." is the correct form. "Name" is a noun, so we use "is".'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['My', 'name', 'is', 'Ana'], 'correct_answer' => 'My name is Ana', 'explanation' => 'The correct sentence is: My name is Ana.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete the sentence: "Nice to ___ you!"', 'options' => null, 'correct_answer' => 'meet', 'explanation' => '"Nice to meet you" is the standard greeting when meeting someone for the first time.'],
                    ['type' => 'free_write', 'prompt' => 'Introduce yourself in 2 sentences. Include your name and what you do. Example: "Hello! I am [name]. I am a [job]."', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => 'Perguntas Simples e Artigos',
                'objective' => 'Ask simple what/where questions and use articles a, an, the correctly',
                'grammar_point' => 'Question words: what / where + Articles: a / an / the',
                'intro_text' => 'In this lesson, you will learn how to ask basic questions using "what" and "where", and how to use the English articles "a", "an", and "the" correctly.',
                'vocabulary' => ['what', 'where', 'who', 'a', 'an', 'the', 'office', 'meeting', 'project', 'team', 'company', 'idea', 'name', 'engineer', 'update'],
                'examples' => [
                    ['en' => 'What is your job?', 'pt' => 'Qual é o seu trabalho?'],
                    ['en' => 'Where is the office?', 'pt' => 'Onde fica o escritório?'],
                    ['en' => 'I work at a tech company.', 'pt' => 'Trabalho em uma empresa de tecnologia.'],
                    ['en' => 'She is an engineer.', 'pt' => 'Ela é engenheira.'],
                ],
                'tips' => [
                    "Use 'a' before consonant sounds: a developer, a company, a project.",
                    "Use 'an' before vowel sounds (a, e, i, o, u): an engineer, an idea, an update.",
                    "Use 'the' for something specific that both speakers already know: the office, the meeting.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Choose the correct article: "She is ___ engineer."', 'options' => ['a', 'an', 'the', '(none)'], 'correct_answer' => 'an', 'explanation' => 'Use "an" before words starting with a vowel sound. "Engineer" starts with "e" (vowel), so we use "an".'],
                    ['type' => 'mcq', 'prompt' => 'Which question asks about a PLACE?', 'options' => ['Where is the office?', 'What is your name?', 'Who is the manager?', 'When is the meeting?'], 'correct_answer' => 'Where is the office?', 'explanation' => '"Where" asks about a location/place. The others ask about name (what), person (who), and time (when).'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['What', 'is', 'your', 'job'], 'correct_answer' => 'What is your job', 'explanation' => 'The correct question is: What is your job?', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "I work at ___ tech company."', 'options' => null, 'correct_answer' => 'a', 'explanation' => 'Use "a" before "tech company" because "tech" starts with a consonant sound.'],
                    ['type' => 'free_write', 'prompt' => 'Write 2 questions using "what" and "where". Example: "What is your role? Where do you work?"', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Rotina e Simple Present',
                'objective' => 'Describe daily work routines using the Simple Present tense',
                'grammar_point' => "Simple Present: I work / She works / He doesn't work",
                'intro_text' => 'The Simple Present tense is used to describe routines, habits, and facts. In this lesson, you will learn how to talk about your daily work routine in English.',
                'vocabulary' => ['work', 'code', 'study', 'start', 'finish', 'check', 'review', 'deploy', 'commit', 'test', 'every day', 'in the morning', 'at night', 'usually', 'always', 'never'],
                'examples' => [
                    ['en' => 'I start work at 9am every day.', 'pt' => 'Começo a trabalhar às 9h todos os dias.'],
                    ['en' => 'She reviews code every morning.', 'pt' => 'Ela revisa código toda manhã.'],
                    ['en' => 'We deploy on Fridays.', 'pt' => 'Fazemos deploy às sextas-feiras.'],
                    ['en' => "He doesn't work on weekends.", 'pt' => 'Ele não trabalha nos fins de semana.'],
                ],
                'tips' => [
                    "Add -s or -es to the verb with he/she/it: write→writes, finish→finishes.",
                    "For negatives use 'don't' or 'doesn't': I don't work, she doesn't work.",
                    "Common time words: every day, usually, always, never, on Mondays.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Choose the correct form: "He ___ code every day."', 'options' => ['writes', 'write', 'writing', 'wrote'], 'correct_answer' => 'writes', 'explanation' => 'With he/she/it, add -s to the verb: write → writes.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence is correct?', 'options' => ["She doesn't work on Sundays.", "She don't work on Sundays.", "She doesn't works on Sundays.", "She not work on Sundays."], 'correct_answer' => "She doesn't work on Sundays.", 'explanation' => 'Use "doesn\'t" (does not) with he/she/it for negatives. The main verb stays in base form.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['I', 'attend', 'meetings', 'every', 'morning'], 'correct_answer' => 'I attend meetings every morning', 'explanation' => 'The correct sentence is: I attend meetings every morning.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The team ___ code reviews on Mondays."', 'options' => null, 'correct_answer' => 'does', 'explanation' => 'Use "does" with "the team" (3rd person singular). Example: The team does reviews.'],
                    ['type' => 'free_write', 'prompt' => 'Describe your typical work day in 2-3 sentences. Use verbs: start, work, check, finish.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Números, Tempo e Preposições',
                'objective' => 'Tell the time and dates, and use in/on/at for scheduling',
                'grammar_point' => 'Prepositions of time: in (months/parts of day) / on (days/dates) / at (specific times)',
                'intro_text' => 'In this lesson, you will learn how to use prepositions "in", "on", and "at" when talking about time and scheduling — very useful in meetings and tech work.',
                'vocabulary' => ['in the morning', 'on Monday', 'at 9am', 'in January', 'on Friday', 'at noon', 'deadline', 'schedule', 'calendar', 'sprint', 'quarter', 'at the end of', 'in Q3'],
                'examples' => [
                    ['en' => 'The meeting is on Monday at 3pm.', 'pt' => 'A reunião é na segunda às 15h.'],
                    ['en' => 'We have a sprint review in the afternoon.', 'pt' => 'Temos uma revisão de sprint à tarde.'],
                    ['en' => 'The deadline is in March.', 'pt' => 'O prazo é em março.'],
                    ['en' => "I'll be at the office at 9.", 'pt' => 'Estarei no escritório às 9.'],
                ],
                'tips' => [
                    "AT: specific clock times → at 9am, at noon, at midnight.",
                    "ON: days and dates → on Monday, on March 5th, on Friday.",
                    "IN: months, years, parts of day → in January, in 2025, in the morning.",
                    "Quick rule: IN (big period) → ON (day) → AT (exact time).",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Choose the correct preposition: "The meeting is ___ Monday."', 'options' => ['in', 'on', 'at', 'by'], 'correct_answer' => 'on', 'explanation' => 'Use "on" with days of the week: on Monday, on Friday, on Tuesday.'],
                    ['type' => 'mcq', 'prompt' => "Choose the correct preposition: \"I start work ___ 9 o'clock.\"", 'options' => ['in', 'on', 'at', 'by'], 'correct_answer' => 'at', 'explanation' => 'Use "at" with specific clock times: at 9am, at noon, at 3pm.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'meeting', 'is', 'at', '3pm'], 'correct_answer' => 'The meeting is at 3pm', 'explanation' => 'The correct sentence is: The meeting is at 3pm.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "We deploy ___ Fridays."', 'options' => null, 'correct_answer' => 'on', 'explanation' => 'Use "on" with days: on Fridays, on Mondays, on the weekend.'],
                    ['type' => 'free_write', 'prompt' => 'Describe your next meeting or event using in/on/at. Example: "We have a meeting on Thursday at 2pm in the afternoon."', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Vocabulário do Dia a Dia e Mini Diálogos',
                'objective' => 'Use polite requests and common phrases in professional conversations',
                'grammar_point' => "Can / Could for requests: Can I...? / Could you...?",
                'intro_text' => 'In this final A1 lesson, you will learn useful everyday phrases and polite requests for the workplace. These expressions will help you communicate professionally in English.',
                'vocabulary' => ["Can I help you?", "Could you...?", 'please', 'thank you', 'no problem', 'of course', 'sounds good', 'let me know', 'follow up', 'update', 'check in', "I'll send", 'right away', 'excuse me'],
                'examples' => [
                    ['en' => 'Can I help you with anything?', 'pt' => 'Posso te ajudar com alguma coisa?'],
                    ['en' => 'Could you send me the report, please?', 'pt' => 'Poderia me enviar o relatório, por favor?'],
                    ['en' => "No problem! I'll send it right away.", 'pt' => 'Sem problema! Vou enviar agora.'],
                    ['en' => 'Sounds good! Let me know if you need anything.', 'pt' => 'Ótimo! Me avise se precisar de algo.'],
                ],
                'tips' => [
                    "Use 'Could you...' for polite requests — it's more formal than 'Can you...'.",
                    "'Let me know' = tell me / avise-me. Very common in professional emails and chats.",
                    "'I'll + verb' is the natural way to say you'll do something soon: I'll check, I'll send.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'How do you say "Posso te ajudar?" in English?', 'options' => ['Can I help you?', 'Do I help you?', 'I can help.', 'Help me please?'], 'correct_answer' => 'Can I help you?', 'explanation' => '"Can I help you?" is the standard English phrase to offer assistance.'],
                    ['type' => 'mcq', 'prompt' => 'Which phrase is MORE polite?', 'options' => ['Could you send the file?', 'Can you send the file?', 'Send the file.', 'File. Send now.'], 'correct_answer' => 'Could you send the file?', 'explanation' => '"Could you..." is more polite and formal than "Can you...". Always use it in professional settings.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Please', 'let', 'me', 'know'], 'correct_answer' => 'Please let me know', 'explanation' => '"Please let me know" is a very common professional phrase meaning: tell me / avise-me.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => "Complete: \"No ___, I'll do it right away!\"", 'options' => null, 'correct_answer' => 'problem', 'explanation' => '"No problem" is a friendly way to say "sure" or "of course" when someone asks a favor.'],
                    ['type' => 'free_write', 'prompt' => "Write a short 3-line work dialogue: someone asks for help, the other responds politely. Use: Can I, Could you, No problem, Let me know.", 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }

    // ─── A2 ──────────────────────────────────────────────────────────────────

    private function a2Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Past Simple — Eventos Passados',
                'objective' => 'Talk about past work events using the Simple Past tense',
                'grammar_point' => 'Past Simple: worked / went / was / were',
                'intro_text' => 'The Simple Past tense describes completed actions in the past. In this lesson you will learn to talk about what happened at work yesterday, last week, or in a previous project.',
                'vocabulary' => ['yesterday', 'last week', 'ago', 'worked', 'visited', 'finished', 'was', 'were', 'started', 'completed', 'launched', 'presented', 'attended', 'fixed', 'deployed'],
                'examples' => [
                    ['en' => 'I worked on a bug fix yesterday.', 'pt' => 'Ontem trabalhei em uma correção de bug.'],
                    ['en' => 'We launched the new feature last week.', 'pt' => 'Lançamos a nova funcionalidade na semana passada.'],
                    ['en' => 'The meeting was at 3pm.', 'pt' => 'A reunião foi às 15h.'],
                    ['en' => 'She fixed the critical bug two hours ago.', 'pt' => 'Ela consertou o bug crítico há duas horas.'],
                ],
                'tips' => [
                    "Regular verbs add -ed: work→worked, launch→launched, finish→finished.",
                    "Common irregular verbs: go→went, have→had, make→made, write→wrote, fix→fixed.",
                    "Use 'was' with I/he/she/it and 'were' with you/we/they.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "We ___ the app last Friday."', 'options' => ['launched', 'launch', 'launching', 'are launching'], 'correct_answer' => 'launched', 'explanation' => '"Last Friday" signals past tense. Regular verb: launch → launched.'],
                    ['type' => 'mcq', 'prompt' => 'Which is the correct Past Simple of "go"?', 'options' => ['went', 'goed', 'gone', 'going'], 'correct_answer' => 'went', 'explanation' => '"Go" is irregular: go → went. It does not follow the -ed rule.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['I', 'finished', 'the', 'task', 'yesterday'], 'correct_answer' => 'I finished the task yesterday', 'explanation' => 'The correct sentence is: I finished the task yesterday.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The bug ___ fixed in the last update."', 'options' => null, 'correct_answer' => 'was', 'explanation' => '"The bug" is singular (it), so use "was" in the past passive.'],
                    ['type' => 'free_write', 'prompt' => 'Describe what you did at work yesterday or last week. Use past verbs: worked, fixed, attended, wrote, deployed.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => 'Futuro — Will e Going To',
                'objective' => "Use 'will' for spontaneous decisions and 'going to' for plans",
                'grammar_point' => "going to (planned) / will (spontaneous/offers)",
                'intro_text' => "In this lesson you will learn two ways to talk about the future in English: 'going to' for things you have already planned, and 'will' for decisions made in the moment or offers.",
                'vocabulary' => ["I'm going to", 'I will', 'plan', 'schedule', 'next week', 'tomorrow', 'deploy', 'update', 'improve', 'probably', 'definitely', 'soon', 'later'],
                'examples' => [
                    ['en' => "I'm going to deploy the update tomorrow.", 'pt' => 'Vou fazer o deploy da atualização amanhã.'],
                    ['en' => "Don't worry, I'll help you with that.", 'pt' => 'Não se preocupe, eu vou te ajudar com isso.'],
                    ['en' => "We're going to refactor the code next sprint.", 'pt' => 'Vamos refatorar o código no próximo sprint.'],
                    ['en' => "The server is slow — I'll restart it now.", 'pt' => 'O servidor está lento — vou reiniciá-lo agora.'],
                ],
                'tips' => [
                    "Use 'going to' for plans already decided: I'm going to fix this bug (I planned it).",
                    "Use 'will' for spontaneous decisions or offers: 'I'll do it!' (decided now).",
                    "Will = 'll in informal speech: I'll, she'll, we'll, they'll.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => "You just decided to help your colleague. You say:", 'options' => ["I'll help you!", "I'm going to help you!", "I help you!", "I would help you!"], 'correct_answer' => "I'll help you!", 'explanation' => '"Will" expresses a spontaneous decision made right now. Use "going to" only for plans made in advance.'],
                    ['type' => 'mcq', 'prompt' => "It's in your sprint plan. You say: 'I ___ refactor this module.'", 'options' => ['am going to', 'will', 'would', 'shall'], 'correct_answer' => 'am going to', 'explanation' => 'Use "going to" for pre-planned actions. It was already in your sprint, so it is a plan.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['We', 'are', 'going', 'to', 'deploy', 'tomorrow'], 'correct_answer' => 'We are going to deploy tomorrow', 'explanation' => 'The correct sentence is: We are going to deploy tomorrow.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The server is down — I ___ restart it right now."', 'options' => null, 'correct_answer' => 'will', 'explanation' => '"Will" is used for a spontaneous decision — you decided to restart it at this moment.'],
                    ['type' => 'free_write', 'prompt' => "Write about 2 things you plan to do at work this week. Use 'going to' for plans and 'will' for offers.", 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Comparativos e Superlativos',
                'objective' => 'Compare technologies, tools, and solutions using comparative and superlative forms',
                'grammar_point' => 'Comparative: faster / more efficient — Superlative: the fastest / the most efficient',
                'intro_text' => 'In this lesson you will learn how to compare things in English — very useful when discussing which technology, approach, or tool is better for a project.',
                'vocabulary' => ['fast', 'faster', 'fastest', 'easy', 'easier', 'easiest', 'efficient', 'more efficient', 'most efficient', 'complex', 'better', 'worse', 'best', 'worst', 'performance', 'solution', 'approach'],
                'examples' => [
                    ['en' => 'Python is easier than Java for beginners.', 'pt' => 'Python é mais fácil que Java para iniciantes.'],
                    ['en' => 'This solution is more efficient than the previous one.', 'pt' => 'Esta solução é mais eficiente que a anterior.'],
                    ['en' => 'React is the most popular frontend framework.', 'pt' => 'React é o framework frontend mais popular.'],
                    ['en' => 'This is the worst bug I have ever seen.', 'pt' => 'Este é o pior bug que já vi.'],
                ],
                'tips' => [
                    "Short adjectives (1-2 syllables): add -er/-est: fast→faster→fastest.",
                    "Long adjectives (3+ syllables): use more/most: efficient→more efficient→most efficient.",
                    "Irregular: good→better→best, bad→worse→worst.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "This method is ___ than the previous one." (fast)', 'options' => ['faster', 'more fast', 'fastest', 'more faster'], 'correct_answer' => 'faster', 'explanation' => '"Fast" is a short adjective (1 syllable) so add -er: faster. Never use "more" with short adjectives.'],
                    ['type' => 'mcq', 'prompt' => 'Complete: "This is the ___ approach." (efficient)', 'options' => ['most efficient', 'more efficient', 'efficientest', 'efficient more'], 'correct_answer' => 'most efficient', 'explanation' => '"Efficient" has 3 syllables so use "most efficient" for the superlative.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Python', 'is', 'easier', 'than', 'Java'], 'correct_answer' => 'Python is easier than Java', 'explanation' => 'Comparative: easy → easier. The structure is: [A] is [comparative] than [B].', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "TypeScript has ___ type safety than plain JavaScript."', 'options' => null, 'correct_answer' => 'better', 'explanation' => '"Better" is the comparative of "good". Use it before a noun: better type safety.'],
                    ['type' => 'free_write', 'prompt' => 'Compare two programming languages, tools, or frameworks. Use: better, easier, more powerful, faster, the most popular.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Expressando Opinião',
                'objective' => 'Share and respond to professional opinions in English',
                'grammar_point' => 'I think / I believe / In my opinion / I agree / I disagree',
                'intro_text' => 'In this lesson you will learn how to express your opinion, agree, and disagree politely in English — essential skills for meetings, code reviews, and technical discussions.',
                'vocabulary' => ['I think', 'I believe', 'in my opinion', 'I agree', 'I disagree', "I'm not sure", 'perhaps', 'probably', 'it seems', 'I feel', 'personally', 'that makes sense'],
                'examples' => [
                    ['en' => 'In my opinion, remote work is more productive.', 'pt' => 'Na minha opinião, o trabalho remoto é mais produtivo.'],
                    ['en' => 'I think we should use microservices.', 'pt' => 'Acho que deveríamos usar microsserviços.'],
                    ['en' => 'I agree with your approach.', 'pt' => 'Concordo com sua abordagem.'],
                    ['en' => "I see your point, but I'm not sure about that.", 'pt' => 'Entendo seu ponto, mas não tenho certeza sobre isso.'],
                ],
                'tips' => [
                    "Start with 'I think' or 'In my opinion' to soften your statement — it sounds less aggressive.",
                    "To agree: 'I agree', 'Exactly!', 'That's a good point.', 'You're right.'",
                    "To disagree politely: 'I see your point, but...', 'I'm not sure I agree...'",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which phrase expresses a personal opinion?', 'options' => ['I think we should refactor.', 'We refactor now.', 'Refactor!', 'Do we refactor?'], 'correct_answer' => 'I think we should refactor.', 'explanation' => '"I think" introduces a personal opinion. The other options are commands or direct questions.'],
                    ['type' => 'mcq', 'prompt' => 'How do you politely disagree in a meeting?', 'options' => ["I see your point, but I disagree.", "No, you're wrong.", "That's incorrect.", "Disagree."], 'correct_answer' => "I see your point, but I disagree.", 'explanation' => 'Acknowledging the other person\'s point first ("I see your point") is the professional way to disagree.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['In', 'my', 'opinion', 'we', 'should', 'test', 'more'], 'correct_answer' => 'In my opinion we should test more', 'explanation' => 'The correct sentence is: In my opinion, we should test more.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "I ___ with your solution — it is very elegant."', 'options' => null, 'correct_answer' => 'agree', 'explanation' => '"I agree with..." expresses agreement. Follow it with "with + noun/pronoun".'],
                    ['type' => 'free_write', 'prompt' => 'Share your opinion about a technology or approach at work. Use: I think, I believe, In my opinion, I agree/disagree.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Localização e Preposições de Lugar',
                'objective' => 'Describe locations and give simple directions using prepositions of place',
                'grammar_point' => 'Prepositions of place: next to / opposite / between / in front of / behind',
                'intro_text' => 'In this lesson you will learn how to describe where things and places are. This vocabulary is useful both in physical offices and when describing system architecture or data flow.',
                'vocabulary' => ['next to', 'opposite', 'in front of', 'behind', 'between', 'near', 'around the corner', 'go straight', 'turn left', 'turn right', 'entrance', 'floor', 'building', 'stairs', 'elevator'],
                'examples' => [
                    ['en' => 'The server room is next to the main office.', 'pt' => 'A sala de servidores fica ao lado do escritório principal.'],
                    ['en' => 'Go straight and turn right at the elevator.', 'pt' => 'Siga em frente e vire à direita no elevador.'],
                    ['en' => 'The meeting room is between the kitchen and the reception.', 'pt' => 'A sala de reunião fica entre a cozinha e a recepção.'],
                    ['en' => 'The CEO office is opposite the main entrance.', 'pt' => 'O escritório do CEO fica em frente à entrada principal.'],
                ],
                'tips' => [
                    "Between = entre dois (between A and B). Always use 'and': between the desk and the wall.",
                    "Next to = ao lado de; Opposite = em frente de (across from).",
                    "Directions: go straight (siga em frente), turn left/right (vire à esquerda/direita).",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => '"The printer is ___ the desk and the window."', 'options' => ['between', 'next', 'behind', 'in front'], 'correct_answer' => 'between', 'explanation' => '"Between" is used when something is in the middle of two things: between A and B.'],
                    ['type' => 'mcq', 'prompt' => 'Which phrase gives a direction?', 'options' => ['Turn left at the elevator.', 'The room is nice.', 'I like the office.', 'The door is open.'], 'correct_answer' => 'Turn left at the elevator.', 'explanation' => '"Turn left at the elevator" gives a direction. The others are just descriptions.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'server', 'is', 'next', 'to', 'the', 'desk'], 'correct_answer' => 'The server is next to the desk', 'explanation' => 'The correct sentence is: The server is next to the desk.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The coffee shop is ___ the office building."', 'options' => null, 'correct_answer' => 'opposite', 'explanation' => '"Opposite" means directly across from something: the coffee shop is on the other side of the street.'],
                    ['type' => 'free_write', 'prompt' => 'Describe where your workspace or a room is. Use: next to, between, opposite, in front of, behind.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }

    // ─── B1 ──────────────────────────────────────────────────────────────────

    private function b1Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Present Perfect — Experiências e Ações Recentes',
                'objective' => 'Use Present Perfect to talk about experience and recent events',
                'grammar_point' => 'Present Perfect: have / has + past participle',
                'intro_text' => 'The Present Perfect connects past actions to the present. Use it to describe your professional experience, things you have just done, or actions that are still relevant now.',
                'vocabulary' => ['have worked', 'have developed', 'have fixed', 'have launched', 'have completed', 'already', 'yet', 'ever', 'never', 'recently', 'just', 'since', 'for'],
                'examples' => [
                    ['en' => 'I have worked with AWS for two years.', 'pt' => 'Trabalho com AWS há dois anos.'],
                    ['en' => 'She has just pushed a new commit.', 'pt' => 'Ela acabou de fazer um novo commit.'],
                    ['en' => 'Have you ever used Kubernetes?', 'pt' => 'Você já usou Kubernetes?'],
                    ['en' => "We haven't deployed the fix yet.", 'pt' => 'Ainda não fizemos o deploy da correção.'],
                ],
                'tips' => [
                    "Use 'already' in positive sentences: I've already deployed it.",
                    "Use 'yet' in questions and negatives: Have you tested it yet? I haven't done it yet.",
                    "Use 'just' for very recent actions: I've just pushed the fix.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => '"She ___ just merged the pull request."', 'options' => ['has', 'have', 'had', 'is'], 'correct_answer' => 'has', 'explanation' => 'With he/she/it, use "has" + past participle. Example: She has merged.'],
                    ['type' => 'mcq', 'prompt' => '"Have you ___ used Docker?" — "Yes, I have!"', 'options' => ['ever', 'already', 'just', 'yet'], 'correct_answer' => 'ever', 'explanation' => '"Ever" is used in questions to ask about any time in someone\'s life: Have you ever used X?'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['I', 'have', 'worked', 'with', 'React', 'for', 'three', 'years'], 'correct_answer' => 'I have worked with React for three years', 'explanation' => 'Present Perfect with "for" expresses duration: have worked for three years.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "Have you finished the report ___?"', 'options' => null, 'correct_answer' => 'yet', 'explanation' => '"Yet" is used at the end of questions and negatives with Present Perfect: Have you done it yet?'],
                    ['type' => 'free_write', 'prompt' => 'Use the Present Perfect to describe your professional experience. What have you built, used, or learned? Use: have worked, have developed, have used, have learned.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => '1º Condicional — Se Isso Acontecer',
                'objective' => 'Express real and likely future conditions using the First Conditional',
                'grammar_point' => 'First Conditional: If + Present Simple, will + verb',
                'intro_text' => 'The First Conditional describes real and possible situations: if something happens, something else will result. It is very common in tech discussions about risks, deployments, and testing.',
                'vocabulary' => ['if', 'unless', 'when', 'will', 'deploy', 'fix', 'fail', 'succeed', 'break', 'test', 'error', 'result', 'crash', 'scale', 'improve'],
                'examples' => [
                    ['en' => 'If we test the code now, we will catch bugs early.', 'pt' => 'Se testarmos o código agora, encontraremos bugs cedo.'],
                    ['en' => 'If you push without reviewing, the CI will fail.', 'pt' => 'Se você fizer push sem revisar, o CI vai falhar.'],
                    ['en' => 'Unless we scale the server, it will crash.', 'pt' => 'A menos que escalemos o servidor, ele vai cair.'],
                    ['en' => 'If performance improves, users will stay longer.', 'pt' => 'Se o desempenho melhorar, os usuários ficarão mais tempo.'],
                ],
                'tips' => [
                    "1st Conditional = real/possible situations: If [it happens], [result will follow].",
                    "Use 'unless' to mean 'if not': Unless you fix it = If you don't fix it.",
                    "The 'if' clause uses present simple, even though it refers to the future.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "If we don\'t test it, the app ___ crash."', 'options' => ['will', 'would', 'should', 'might'], 'correct_answer' => 'will', 'explanation' => 'First Conditional uses "will" in the result clause: If + present, will + verb.'],
                    ['type' => 'mcq', 'prompt' => 'Which is a First Conditional sentence?', 'options' => ["If I have time, I'll review it.", 'If I had time, I would review it.', 'I reviewed it.', 'I will review it.'], 'correct_answer' => "If I have time, I'll review it.", 'explanation' => 'First Conditional: If + present simple (have), will + verb (review). The second option is Second Conditional.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['If', 'we', 'scale', 'the', 'server', 'it', 'will', 'be', 'faster'], 'correct_answer' => 'If we scale the server it will be faster', 'explanation' => 'The correct sentence is: If we scale the server, it will be faster.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "___ we don\'t fix the bug, the release will be delayed."', 'options' => null, 'correct_answer' => 'If', 'explanation' => '"If" introduces the condition clause. It means: the delay happens as a consequence of not fixing.'],
                    ['type' => 'free_write', 'prompt' => 'Write 2 sentences about what will happen at work if certain conditions are met. Use: If...will... and Unless...will...', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Voz Passiva — Foco na Ação',
                'objective' => 'Use passive voice to describe processes and technical documentation',
                'grammar_point' => 'Passive Voice: is/are + past participle / was/were + past participle',
                'intro_text' => 'Passive voice is essential in technical writing and documentation. Instead of saying who does the action, we focus on what is done. You will find it everywhere in tech specs, error messages, and reports.',
                'vocabulary' => ['is deployed', 'was merged', 'is tested', 'is reviewed', 'was reported', 'is maintained', 'is built', 'was fixed', 'is used', 'was sent', 'is stored', 'is encrypted'],
                'examples' => [
                    ['en' => 'The code is reviewed before each release.', 'pt' => 'O código é revisado antes de cada lançamento.'],
                    ['en' => 'The bug was fixed in the latest update.', 'pt' => 'O bug foi corrigido na última atualização.'],
                    ['en' => 'Data is stored in the cloud.', 'pt' => 'Os dados são armazenados na nuvem.'],
                    ['en' => 'All passwords are encrypted automatically.', 'pt' => 'Todas as senhas são criptografadas automaticamente.'],
                ],
                'tips' => [
                    "Active: The team fixes bugs. Passive: Bugs are fixed (by the team).",
                    "The 'doer' can be omitted when it is obvious: 'Tests are run automatically.'",
                    "Passive is very common in tech docs: 'The data is encrypted', 'The API is tested'.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "The update ___ deployed last night."', 'options' => ['was', 'is', 'were', 'has'], 'correct_answer' => 'was', 'explanation' => 'Past passive: was + past participle. "The update" is singular, so "was deployed".'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence uses passive voice correctly?', 'options' => ['The API is tested automatically.', 'We test the API automatically.', 'Test the API.', 'The API tests.'], 'correct_answer' => 'The API is tested automatically.', 'explanation' => 'Passive voice: subject + is/are + past participle. "The API is tested" — the API is the subject, not the doer.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'report', 'was', 'sent', 'by', 'the', 'manager'], 'correct_answer' => 'The report was sent by the manager', 'explanation' => 'Passive: The report (subject) was sent (passive verb) by the manager (agent).', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The database ___ backed up every night."', 'options' => null, 'correct_answer' => 'is', 'explanation' => 'Present passive for a recurring process: is + past participle (backed up).'],
                    ['type' => 'free_write', 'prompt' => 'Describe 2 processes in your work using passive voice. Example: "Code is reviewed before merging. Updates are deployed every Friday."', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Phrasal Verbs em TI',
                'objective' => 'Use common IT phrasal verbs correctly in professional contexts',
                'grammar_point' => 'Phrasal Verbs: verb + particle with a new meaning',
                'intro_text' => 'Phrasal verbs are combinations of a verb and a particle (in, out, up, down, back) that together have a new meaning. In IT, they are extremely common and you need to recognise them instantly.',
                'vocabulary' => ['log in', 'log out', 'set up', 'shut down', 'back up', 'look into', 'roll back', 'sign up', 'run out of', 'come up with', 'deal with', 'figure out', 'break down', 'turn off'],
                'examples' => [
                    ['en' => 'Please log in to the system with your credentials.', 'pt' => 'Por favor, faça login no sistema com suas credenciais.'],
                    ['en' => 'We need to set up the development environment first.', 'pt' => 'Precisamos configurar o ambiente de desenvolvimento primeiro.'],
                    ["en" => "I'll look into this issue right away.", 'pt' => 'Vou investigar esse problema imediatamente.'],
                    ['en' => 'We had to roll back the deployment after the error.', 'pt' => 'Tivemos que reverter o deploy após o erro.'],
                ],
                'tips' => [
                    "Phrasal verbs cannot be translated literally — learn them as fixed units of meaning.",
                    "Some are separable: back (it) up, set (it) up — the object can go in the middle.",
                    "In tech: 'roll back' = revert; 'log in' = authenticate; 'back up' = create a copy.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'What does "look into" mean?', 'options' => ['investigate/research', 'look visually at something', 'log in', 'look outside'], 'correct_answer' => 'investigate/research', 'explanation' => '"Look into" means to investigate or research a problem. Example: I will look into the issue.'],
                    ['type' => 'mcq', 'prompt' => 'Which phrasal verb means "revert to a previous version"?', 'options' => ['roll back', 'shut down', 'log in', 'set up'], 'correct_answer' => 'roll back', 'explanation' => '"Roll back" means to revert to a previous state or version. Very common in deployments.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['I', 'will', 'look', 'into', 'the', 'issue', 'tomorrow'], 'correct_answer' => 'I will look into the issue tomorrow', 'explanation' => 'The correct sentence is: I will look into the issue tomorrow.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "Please ___ up your files before updating the system."', 'options' => null, 'correct_answer' => 'back', 'explanation' => '"Back up" = create a copy/backup. "Back up your files" is standard IT instruction.'],
                    ['type' => 'free_write', 'prompt' => 'Use 3 phrasal verbs from the list in sentences describing your work. Example: "I log in every morning. I set up my environment. I look into issues quickly."', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Emails Profissionais',
                'objective' => 'Write and understand professional emails in English',
                'grammar_point' => 'Formal email structure: greeting / purpose / request / closing',
                'intro_text' => 'Writing professional emails in English is a critical skill for any IT professional. In this lesson you will learn the standard structure, key phrases, and the right level of formality.',
                'vocabulary' => ['Dear', 'I am writing to', 'I would like to', 'please find attached', 'regarding', 'looking forward to', 'best regards', 'sincerely', 'I hope this email finds you well', 'could you please', 'I apologize', 'as per', 'follow up'],
                'examples' => [
                    ['en' => 'Dear Mr. Smith, I am writing to request an update on the project.', 'pt' => 'Caro Sr. Smith, Escrevo para solicitar uma atualização sobre o projeto.'],
                    ['en' => 'Please find attached the report you requested.', 'pt' => 'Em anexo, encontre o relatório solicitado.'],
                    ['en' => 'Looking forward to your reply. Best regards, Ana.', 'pt' => 'Aguardo seu retorno. Atenciosamente, Ana.'],
                    ['en' => 'Could you please confirm your availability for Thursday?', 'pt' => 'Poderia confirmar sua disponibilidade para quinta-feira?'],
                ],
                'tips' => [
                    "Start with 'Dear [Name],' (formal) or 'Hi [Name],' (informal/friendly).",
                    "End with 'Best regards', 'Kind regards', or 'Sincerely' before your name.",
                    "Always state your purpose clearly: 'I am writing to...' / 'I am contacting you regarding...'",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which is the correct formal greeting for a professional email?', 'options' => ['Dear Mr. Johnson,', 'Hey Johnson,', 'Hello there,', 'Yo Mr. J,'], 'correct_answer' => 'Dear Mr. Johnson,', 'explanation' => '"Dear [Title] [Last Name]," is the standard formal email greeting in English.'],
                    ['type' => 'mcq', 'prompt' => 'Which phrase requests something politely?', 'options' => ['Could you please review the document?', 'Review the document.', 'Document. Review.', 'Review it now.'], 'correct_answer' => 'Could you please review the document?', 'explanation' => '"Could you please..." is the polite way to make a request. It is more formal than "Can you...".'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['I', 'am', 'writing', 'to', 'request', 'a', 'meeting'], 'correct_answer' => 'I am writing to request a meeting', 'explanation' => '"I am writing to..." is the standard opening to state the purpose of a formal email.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "Please ___ attached the updated proposal."', 'options' => null, 'correct_answer' => 'find', 'explanation' => '"Please find attached..." is the standard phrase for sending attachments in formal emails.'],
                    ['type' => 'free_write', 'prompt' => 'Write a short professional email (4-5 sentences) asking for a project update. Use: Dear, I am writing to, Could you please, Looking forward to, Best regards.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }

    // ─── B2 ──────────────────────────────────────────────────────────────────

    private function b2Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Past Perfect — Antes do Passado',
                'objective' => 'Use Past Perfect to show one past action happened before another',
                'grammar_point' => 'Past Perfect: had + past participle',
                'intro_text' => 'The Past Perfect is used when you need to show that one past event happened before another past event. It is especially useful in incident reports, retrospectives, and storytelling about projects.',
                'vocabulary' => ['had already', 'had just', 'before', 'by the time', 'when', 'had deployed', 'had written', 'had fixed', 'had tested', 'had completed', 'had reviewed', 'had merged'],
                'examples' => [
                    ['en' => 'By the time I arrived, the team had already merged the PR.', 'pt' => 'Quando cheguei, a equipe já tinha feito o merge do PR.'],
                    ['en' => 'She had written the tests before the code review.', 'pt' => 'Ela tinha escrito os testes antes da revisão de código.'],
                    ['en' => 'We discovered the bug after we had deployed the update.', 'pt' => 'Descobrimos o bug depois que tínhamos feito o deploy da atualização.'],
                    ['en' => 'He had never seen such a complex architecture before.', 'pt' => 'Ele nunca tinha visto uma arquitetura tão complexa antes.'],
                ],
                'tips' => [
                    "Past Perfect = the 'earlier past'. Use it when one past event happened BEFORE another.",
                    "Key signals: 'by the time', 'before', 'after', 'already', 'just', 'never... before'.",
                    "Structure is the same for all subjects: had + past participle.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "By the time she arrived, the meeting ___ started."', 'options' => ['had already', 'has already', 'already had', 'already has'], 'correct_answer' => 'had already', 'explanation' => '"Had already" + past participle forms the Past Perfect. "By the time" signals a past moment as reference.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence uses Past Perfect correctly?', 'options' => ['He had fixed the bug before the demo.', 'He fixed the bug before the demo.', 'He has fixed the bug before the demo.', 'He fix the bug before the demo.'], 'correct_answer' => 'He had fixed the bug before the demo.', 'explanation' => '"Had fixed" (Past Perfect) shows the fix happened before the demo (another past event).'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['She', 'had', 'already', 'deployed', 'the', 'update', 'before', 'lunch'], 'correct_answer' => 'She had already deployed the update before lunch', 'explanation' => 'The correct sentence is: She had already deployed the update before lunch.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "When I reviewed the code, someone ___ already merged the PR."', 'options' => null, 'correct_answer' => 'had', 'explanation' => '"Had already merged" = Past Perfect. The merge happened before the code review (which is also past).'],
                    ['type' => 'free_write', 'prompt' => 'Describe a work situation where one thing happened before another. Use: had already, by the time, before, after.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => '2º Condicional — Situações Hipotéticas',
                'objective' => 'Express imaginary or unlikely situations using the Second Conditional',
                'grammar_point' => 'Second Conditional: If + Past Simple, would + base verb',
                'intro_text' => 'The Second Conditional is used for hypothetical, imaginary, or unlikely situations. It is very common in tech discussions: "What would you do if...?" — useful in architecture debates and interview questions.',
                'vocabulary' => ['if', 'would', 'could', 'might', 'hypothetically', 'imagine', 'suppose', 'were', 'had', 'resources', 'infrastructure', 'approach', 'decision'],
                'examples' => [
                    ['en' => 'If I had more time, I would refactor this entire module.', 'pt' => 'Se eu tivesse mais tempo, refatoraria todo este módulo.'],
                    ['en' => 'What would you do if the production server crashed?', 'pt' => 'O que você faria se o servidor de produção caísse?'],
                    ['en' => 'If I were the tech lead, I would adopt agile practices.', 'pt' => 'Se eu fosse o líder técnico, adotaria práticas ágeis.'],
                    ['en' => 'If we had better monitoring, we would catch issues faster.', 'pt' => 'Se tivéssemos um monitoramento melhor, detectaríamos problemas mais rápido.'],
                ],
                'tips' => [
                    "2nd Conditional = imaginary situations (compare: 1st = possible, 2nd = unlikely/hypothetical).",
                    "Use 'were' for all subjects in formal/correct English: If I were, If she were (not 'was').",
                    "If I have time, I'll fix it (real) vs If I had time, I would fix it (hypothetical).",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "If I ___ the CTO, I would invest more in AI tools."', 'options' => ['were', 'was', 'am', 'are'], 'correct_answer' => 'were', 'explanation' => 'In 2nd Conditional, use "were" for all subjects in formal English: If I were, If she were.'],
                    ['type' => 'mcq', 'prompt' => 'Which is a Second Conditional sentence?', 'options' => ['If I had more resources, I would build it.', "If I have time, I'll do it.", 'I would do it tomorrow.', 'If it works, ship it.'], 'correct_answer' => 'If I had more resources, I would build it.', 'explanation' => 'Second Conditional: If + past simple (had), would + base verb (build). It describes a hypothetical situation.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['If', 'we', 'had', 'more', 'servers', 'the', 'app', 'would', 'be', 'faster'], 'correct_answer' => 'If we had more servers the app would be faster', 'explanation' => 'The correct sentence is: If we had more servers, the app would be faster.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "If I were you, I ___ use TypeScript for this project."', 'options' => null, 'correct_answer' => 'would', 'explanation' => '"Would" is the result clause in Second Conditional: If + past, would + base verb.'],
                    ['type' => 'free_write', 'prompt' => 'Write 2 hypothetical sentences about your work or tech. Use: If I had..., If I were..., I would...', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Discurso Indireto — Reportando o que Alguém Disse',
                'objective' => 'Report what others said using correct tense backshift',
                'grammar_point' => 'Reported Speech: said (that) / told me (that) + tense backshift',
                'intro_text' => 'Reported speech (indirect speech) is essential for summarising meetings, reporting requirements, and communicating decisions. In English, the tense typically shifts back when reporting.',
                'vocabulary' => ['said', 'told', 'asked', 'reported', 'mentioned', 'explained', 'suggested', 'warned', 'advised', 'confirmed', 'replied', 'claimed', 'announced'],
                'examples' => [
                    ['en' => 'She said that the bug was in the authentication module.', 'pt' => 'Ela disse que o bug estava no módulo de autenticação.'],
                    ['en' => 'He told me that he would fix it by Friday.', 'pt' => 'Ele me disse que consertaria até sexta-feira.'],
                    ['en' => 'The manager asked if we had tested the feature.', 'pt' => 'O gerente perguntou se tínhamos testado a funcionalidade.'],
                    ['en' => 'They mentioned that the API was down.', 'pt' => 'Eles mencionaram que a API estava fora do ar.'],
                ],
                'tips' => [
                    "Tense backshift: present→past, will→would, can→could, have→had.",
                    "Say vs Tell: 'She said (that)...' / 'She told me (that)...' — 'tell' needs an object person.",
                    "For questions in reported speech: 'He asked if/whether...' — no inversion.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => '"I will deploy it tomorrow." → He said that he ___ deploy it the next day.', 'options' => ['would', 'will', 'can', 'should'], 'correct_answer' => 'would', 'explanation' => 'Backshift: will → would. In reported speech, "will" becomes "would".'],
                    ['type' => 'mcq', 'prompt' => 'Which is correct reported speech?', 'options' => ['She told me that the server was down.', 'She said me that the server was down.', 'She told that the server was down.', 'She told to me the server was down.'], 'correct_answer' => 'She told me that the server was down.', 'explanation' => '"Tell" requires an object: told ME. "Say" does not: she said (that)... — not "she said me".'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['He', 'said', 'that', 'the', 'fix', 'was', 'ready'], 'correct_answer' => 'He said that the fix was ready', 'explanation' => 'The correct sentence is: He said that the fix was ready.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "She ___ me that she had already reviewed the code."', 'options' => null, 'correct_answer' => 'told', 'explanation' => '"Told me" = reported speech with a recipient. "Said me" is incorrect in English.'],
                    ['type' => 'free_write', 'prompt' => 'Report what was discussed in a recent meeting or conversation. Use: said, told me, asked if, mentioned, explained that.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Frases Relativas — who, which, that',
                'objective' => 'Use relative clauses to add information about people and things',
                'grammar_point' => 'Relative clauses: who (people) / which (things) / that (both)',
                'intro_text' => 'Relative clauses let you add extra information about a noun within the same sentence. They make your English more sophisticated and are essential for writing technical documentation and descriptions.',
                'vocabulary' => ['who', 'which', 'that', 'where', 'whose', 'defines', 'describes', 'specifies', 'identifies', 'refers to', 'developed', 'maintained', 'designed'],
                'examples' => [
                    ['en' => 'The developer who fixed the bug got a bonus.', 'pt' => 'O desenvolvedor que consertou o bug ganhou um bônus.'],
                    ['en' => 'The tool that we use for CI/CD is Jenkins.', 'pt' => 'A ferramenta que usamos para CI/CD é o Jenkins.'],
                    ['en' => 'React, which was created by Facebook, is very popular.', 'pt' => 'O React, que foi criado pelo Facebook, é muito popular.'],
                    ['en' => 'The engineer whose code I reviewed is very talented.', 'pt' => 'O engenheiro cujo código eu revisei é muito talentoso.'],
                ],
                'tips' => [
                    "Use 'who' for people, 'which' for things/ideas, 'that' for both in defining clauses.",
                    "Defining clause (no commas): 'The bug that caused the crash was in line 42.'",
                    "Non-defining clause (with commas): 'React, which is a library, was released in 2013.'",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "The engineer ___ built this system is very experienced."', 'options' => ['who', 'which', 'what', 'where'], 'correct_answer' => 'who', 'explanation' => 'Use "who" for people. "The engineer who built..." refers to a person.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence uses a relative clause correctly?', 'options' => ['The file that you sent was corrupted.', 'The file what you sent was corrupted.', 'The file who you sent was corrupted.', 'The file where you sent was corrupted.'], 'correct_answer' => 'The file that you sent was corrupted.', 'explanation' => '"That" is used for things in defining relative clauses: the file that you sent.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'framework', 'which', 'we', 'use', 'is', 'very', 'efficient'], 'correct_answer' => 'The framework which we use is very efficient', 'explanation' => 'The correct sentence is: The framework which we use is very efficient.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The developer ___ wrote this code left the company."', 'options' => null, 'correct_answer' => 'who', 'explanation' => '"Who" is the correct relative pronoun for people. "The developer who wrote..." refers to a person.'],
                    ['type' => 'free_write', 'prompt' => 'Describe a tool, technology, or colleague using relative clauses. Use: who, which, that, where.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Apresentações Técnicas',
                'objective' => 'Present technical data and results clearly using signposting language',
                'grammar_point' => 'Presentation language: As you can see... / This shows... / To summarize...',
                'intro_text' => 'Being able to present technical information clearly in English is a highly valued skill. In this lesson you will learn how to structure presentations, describe data trends, and conclude effectively.',
                'vocabulary' => ['as you can see', 'this graph shows', 'there is an increase', 'there is a decrease', 'significantly', 'slightly', 'gradually', 'compared to', 'this demonstrates', 'in conclusion', 'to summarize', 'moving on', 'firstly', 'finally'],
                'examples' => [
                    ['en' => 'As you can see, performance improved by 40% after the optimization.', 'pt' => 'Como podem ver, o desempenho melhorou 40% após a otimização.'],
                    ['en' => 'This graph shows a significant increase in user traffic.', 'pt' => 'Este gráfico mostra um aumento significativo no tráfego de usuários.'],
                    ['en' => 'To summarize, the new architecture is faster and more cost-effective.', 'pt' => 'Para resumir, a nova arquitetura é mais rápida e mais econômica.'],
                    ['en' => 'Moving on to the next point, let me show you the test results.', 'pt' => 'Passando ao próximo ponto, deixe-me mostrar os resultados dos testes.'],
                ],
                'tips' => [
                    "Signpost your presentation: 'Firstly...', 'Moving on to...', 'Finally...', 'In conclusion...'",
                    "Describe trends: increase, decrease, remain stable, fluctuate, peak at, drop to.",
                    "Support your claims with data: 'This shows that...', 'The data suggests that...'",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'How do you introduce a new topic in a presentation?', 'options' => ['Moving on to...', 'Ending now...', "That's wrong...", 'Therefore I disagree...'], 'correct_answer' => 'Moving on to...', 'explanation' => '"Moving on to..." is a signposting phrase that signals a transition to a new topic.'],
                    ['type' => 'mcq', 'prompt' => 'Which phrase describes a positive data trend?', 'options' => ['There was a significant increase.', 'The data dropped sharply.', 'Nothing changed.', 'The results were below average.'], 'correct_answer' => 'There was a significant increase.', 'explanation' => '"There was a significant increase" describes a positive upward trend in data.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['As', 'you', 'can', 'see', 'the', 'performance', 'improved'], 'correct_answer' => 'As you can see the performance improved', 'explanation' => '"As you can see" is a common phrase to draw attention to visual data in a presentation.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "___ summarize, our solution reduced costs by 30%."', 'options' => null, 'correct_answer' => 'To', 'explanation' => '"To summarize" signals the conclusion of a presentation. Also: "In conclusion", "To conclude".'],
                    ['type' => 'free_write', 'prompt' => 'Describe the results of a project or feature you worked on. Use presentation language: As you can see, this shows, in conclusion, there was an increase/improvement.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }

    // ─── C1 ──────────────────────────────────────────────────────────────────

    private function c1Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Tempos Perfeitos Contínuos',
                'objective' => 'Use Perfect Continuous tenses to emphasise duration and ongoing actions',
                'grammar_point' => 'has/have been + -ing / had been + -ing',
                'intro_text' => 'Perfect Continuous tenses add a sense of duration and ongoing effort to your English. They are ideal for describing long-running projects, troubleshooting sessions, and progress updates.',
                'vocabulary' => ['has been working', 'have been developing', 'had been testing', 'have been refactoring', 'has been improving', 'continuously', 'progressively', 'lately', 'recently', 'for months', 'all morning', 'since last week'],
                'examples' => [
                    ['en' => 'I have been working on this feature for three weeks.', 'pt' => 'Tenho trabalhado nesta funcionalidade há três semanas.'],
                    ['en' => 'She has been learning Rust lately.', 'pt' => 'Ela tem aprendido Rust ultimamente.'],
                    ['en' => 'They had been debugging the issue for hours before finding it.', 'pt' => 'Eles tinham depurado o problema por horas antes de encontrá-lo.'],
                    ['en' => 'The team has been refactoring the legacy codebase since January.', 'pt' => 'A equipe tem refatorado o código legado desde janeiro.'],
                ],
                'tips' => [
                    "Perfect Continuous emphasises duration or the ongoing nature of an activity.",
                    "Has/have been + -ing: activity still in progress or recently finished with current relevance.",
                    "Had been + -ing: ongoing activity interrupted by another past event.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => '"She ___ refactoring the codebase for weeks and it is almost done."', 'options' => ['has been', 'have been', 'had been', 'was been'], 'correct_answer' => 'has been', 'explanation' => '"She" is third person singular, so "has been" + -ing. The activity is ongoing now.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence best expresses an ongoing process?', 'options' => ['We have been improving the algorithm.', 'We improved the algorithm.', 'We improve the algorithm.', 'We had improved the algorithm.'], 'correct_answer' => 'We have been improving the algorithm.', 'explanation' => '"Have been improving" (Present Perfect Continuous) shows an ongoing process with current relevance.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['They', 'had', 'been', 'testing', 'the', 'feature', 'all', 'morning'], 'correct_answer' => 'They had been testing the feature all morning', 'explanation' => 'Past Perfect Continuous: had been + -ing. Shows an ongoing past action before another event.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "I have ___ waiting for the deployment to finish for two hours."', 'options' => null, 'correct_answer' => 'been', 'explanation' => 'Present Perfect Continuous: have/has + BEEN + -ing. "Been" is the missing auxiliary.'],
                    ['type' => 'free_write', 'prompt' => 'Describe an ongoing project or task using perfect continuous tenses. Express how long it has been happening. Use: have been working on, has been improving, had been running.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => 'Inversões e Ênfase',
                'objective' => 'Use fronted negatives and inversions for emphasis in formal writing and speech',
                'grammar_point' => 'Inversion after: Never / Not only / Rarely / Under no circumstances',
                'intro_text' => 'Inversion — placing the auxiliary before the subject — is used in formal English to add emphasis. It is found in professional writing, technical documentation, and formal presentations.',
                'vocabulary' => ['never', 'not only', 'rarely', 'seldom', 'barely', 'hardly', 'under no circumstances', 'only when', 'no sooner', 'but also', 'should', 'have', 'did', 'would'],
                'examples' => [
                    ['en' => 'Never have I seen such an elegant solution.', 'pt' => 'Nunca vi uma solução tão elegante.'],
                    ['en' => 'Not only did we fix the bug, but we also improved performance.', 'pt' => 'Não apenas consertamos o bug, como também melhoramos o desempenho.'],
                    ['en' => 'Rarely do we get such detailed requirements.', 'pt' => 'Raramente temos requisitos tão detalhados.'],
                    ['en' => 'Under no circumstances should credentials be stored in the repo.', 'pt' => 'Em hipótese alguma as credenciais devem ser armazenadas no repositório.'],
                ],
                'tips' => [
                    "Inversion = auxiliary verb BEFORE the subject, for emphasis: Never have I..., Rarely do we...",
                    "After 'Not only...' the first clause inverts: Not only DID we fix it, but we ALSO improved it.",
                    "This style is formal — use it in reports, presentations, and technical documentation.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "Never ___ I encountered such a complex system."', 'options' => ['have', 'had', 'has', 'did'], 'correct_answer' => 'have', 'explanation' => 'Inversion after "Never": Never + have + I... (Present Perfect). The auxiliary comes before the subject.'],
                    ['type' => 'mcq', 'prompt' => 'Which sentence uses inversion correctly?', 'options' => ['Not only did we solve it, but we improved it too.', 'Not only we solved it, but we improved it.', 'We solved not only it but improved too.', 'Only not did we solve it.'], 'correct_answer' => 'Not only did we solve it, but we improved it too.', 'explanation' => '"Not only did we..." — inversion (did + we) in the first clause, then "but we also..." in the second.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Rarely', 'do', 'we', 'deploy', 'on', 'a', 'Friday'], 'correct_answer' => 'Rarely do we deploy on a Friday', 'explanation' => 'Inversion after "Rarely": Rarely + do + we. The auxiliary "do" precedes the subject "we".', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "Under no ___ should passwords be stored in plain text."', 'options' => null, 'correct_answer' => 'circumstances', 'explanation' => '"Under no circumstances" is a fixed emphatic phrase meaning "never, in any situation".'],
                    ['type' => 'free_write', 'prompt' => 'Write 2-3 sentences using emphatic inversions about your technical work or beliefs. Use: Never, Not only...but also, Rarely, Under no circumstances.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Linguagem de Hedging — Incerteza Formal',
                'objective' => 'Express uncertainty and caution appropriately in professional and technical contexts',
                'grammar_point' => 'Hedging: may / might / it appears / it seems / one might argue',
                'intro_text' => 'Hedging means expressing ideas with appropriate uncertainty — avoiding overcommitment to a claim. It is essential in technical reports, RFCs, academic writing, and any context where you present conclusions or hypotheses.',
                'vocabulary' => ['it appears that', 'there is a possibility', 'it may be', 'it seems likely', 'tends to', 'could potentially', 'one might argue', 'it is worth noting', 'arguably', 'presumably', 'seemingly', 'it would appear'],
                'examples' => [
                    ['en' => 'It appears that the performance issue stems from the database query.', 'pt' => 'Parece que o problema de desempenho vem da consulta ao banco de dados.'],
                    ['en' => 'This approach may potentially reduce latency by 30%.', 'pt' => 'Esta abordagem pode potencialmente reduzir a latência em 30%.'],
                    ['en' => 'One might argue that microservices introduce unnecessary complexity.', 'pt' => 'Poder-se-ia argumentar que os microsserviços introduzem complexidade desnecessária.'],
                    ['en' => 'It seems likely that the issue is intermittent rather than constant.', 'pt' => 'Parece provável que o problema seja intermitente em vez de constante.'],
                ],
                'tips' => [
                    "Hedging = expressing ideas with appropriate caution and avoiding overcommitment.",
                    "Essential in technical analysis, reports, and proposals where certainty is limited.",
                    "Too much hedging weakens your argument; too little sounds overconfident. Find the balance.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which sentence uses hedging language?', 'options' => ['It appears that the server is overloaded.', 'The server is overloaded.', 'The server always fails.', 'Fix the server.'], 'correct_answer' => 'It appears that the server is overloaded.', 'explanation' => '"It appears that" hedges the claim — expressing a tentative conclusion rather than a definite fact.'],
                    ['type' => 'mcq', 'prompt' => '"___ argue that this design pattern creates technical debt."', 'options' => ['One might', 'Someone', 'We can always', 'People must'], 'correct_answer' => 'One might', 'explanation' => '"One might argue" is a formal hedging phrase meaning "it could be argued". Impersonal and cautious.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['It', 'seems', 'likely', 'that', 'the', 'issue', 'is', 'intermittent'], 'correct_answer' => 'It seems likely that the issue is intermittent', 'explanation' => '"It seems likely that..." introduces a hedged conclusion. The speaker is not 100% certain.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "There ___ a possibility that the API rate limit is causing the timeout."', 'options' => null, 'correct_answer' => 'is', 'explanation' => '"There is a possibility that..." hedges the conclusion — it might be the cause, but you are not certain.'],
                    ['type' => 'free_write', 'prompt' => 'Write a short technical analysis (3-4 sentences) of a problem using hedging. Use: it appears, may, seems likely, one might argue, there is a possibility.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Expressões Idiomáticas em TI e Negócios',
                'objective' => 'Recognise and use common business/tech idioms in professional conversations',
                'grammar_point' => 'Fixed idiomatic expressions used in English-speaking tech companies',
                'intro_text' => 'English-speaking tech companies use many idiomatic expressions that you will not find in textbooks. Understanding and using these idioms naturally marks you as a fluent professional.',
                'vocabulary' => ['ballpark figure', 'hit the ground running', 'get up to speed', 'think outside the box', 'bandwidth', 'pain point', 'game changer', 'low-hanging fruit', 'move the needle', 'deep dive', 'circle back', 'take offline'],
                'examples' => [
                    ['en' => "Let's do a deep dive into the performance metrics.", 'pt' => 'Vamos fazer uma análise aprofundada das métricas de desempenho.'],
                    ['en' => 'The new caching strategy was a real game changer.', 'pt' => 'A nova estratégia de cache foi uma verdadeira virada de jogo.'],
                    ['en' => 'Can we take this offline and circle back tomorrow?', 'pt' => 'Podemos discutir isso fora da reunião e retomar amanhã?'],
                    ['en' => "I don't have the bandwidth for that right now.", 'pt' => 'Não tenho capacidade/disponibilidade para isso agora.'],
                ],
                'tips' => [
                    "These idioms are extremely common in English-speaking tech companies worldwide.",
                    "'Bandwidth' in meetings = capacity/availability: 'Do you have bandwidth for this?'",
                    "'Circle back' = return to a topic; 'take offline' = discuss privately outside the meeting.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'What does "low-hanging fruit" mean in a business context?', 'options' => ['Easy wins requiring little effort', 'Literal fruit that is low on a tree', 'Difficult problems to solve', 'Tasks that are not important'], 'correct_answer' => 'Easy wins requiring little effort', 'explanation' => '"Low-hanging fruit" = tasks or goals that are easy to achieve quickly. Start with these before tackling harder problems.'],
                    ['type' => 'mcq', 'prompt' => 'When someone says "let\'s take this offline", they mean:', 'options' => ["Let's discuss it privately/separately", "Let's go outside", "Let's disconnect from the internet", "Let's stop working"], 'correct_answer' => "Let's discuss it privately/separately", 'explanation' => '"Take this offline" = discuss the topic in a separate, private conversation, not during the current meeting.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Let', 'us', 'do', 'a', 'deep', 'dive', 'into', 'the', 'data'], 'correct_answer' => 'Let us do a deep dive into the data', 'explanation' => '"Do a deep dive into" = perform a thorough, detailed analysis of something.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "We should focus on the ___ fruit first before tackling complex problems."', 'options' => null, 'correct_answer' => 'low-hanging', 'explanation' => '"Low-hanging fruit" = easy tasks that can be completed quickly with minimal effort.'],
                    ['type' => 'free_write', 'prompt' => 'Use 3 idioms from the list in a realistic work context. Write as if you are in a team meeting: deep dive, bandwidth, game changer, circle back, move the needle.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Argumentação — Concessão e Contraste',
                'objective' => 'Build balanced, nuanced arguments using concession and contrast connectors',
                'grammar_point' => 'Although / Even though / Despite / However / Nevertheless / Whereas',
                'intro_text' => 'Advanced argumentation requires acknowledging opposing views while maintaining your position. These connectors allow you to build credible, nuanced arguments — essential in technical debates and written proposals.',
                'vocabulary' => ['although', 'even though', 'despite', 'in spite of', 'however', 'nevertheless', 'on the other hand', 'admittedly', 'while', 'whereas', 'granted', 'that said', 'nonetheless', 'yet'],
                'examples' => [
                    ['en' => 'Although the solution is complex, it is the most scalable option.', 'pt' => 'Embora a solução seja complexa, é a opção mais escalável.'],
                    ['en' => 'Despite the tight deadline, the team delivered a high-quality product.', 'pt' => 'Apesar do prazo apertado, a equipe entregou um produto de alta qualidade.'],
                    ['en' => 'Microservices offer flexibility; however, they increase operational overhead.', 'pt' => 'Microsserviços oferecem flexibilidade; contudo, aumentam a sobrecarga operacional.'],
                    ['en' => 'Admittedly, the approach is unconventional, but the results speak for themselves.', 'pt' => 'Reconhecidamente, a abordagem é não convencional, mas os resultados falam por si.'],
                ],
                'tips' => [
                    "Although/Even though + clause: Although it is complex, it works well.",
                    "Despite/In spite of + noun or gerund: Despite the bugs, Despite being complex.",
                    "'That said' and 'Admittedly' show balance — very common in professional writing.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which is grammatically correct?', 'options' => ['Despite the bugs, the release was successful.', 'Despite that there were bugs, the release was successful.', 'Although bugs, the release was successful.', 'Even bugs the release succeeded.'], 'correct_answer' => 'Despite the bugs, the release was successful.', 'explanation' => '"Despite" is followed by a noun/gerund, not a clause. "Despite the bugs" = correct. "Despite that..." is not standard.'],
                    ['type' => 'mcq', 'prompt' => 'Choose the correct connector: "___ the system is old, it performs remarkably well."', 'options' => ['Although', 'Despite', 'However', 'In spite'], 'correct_answer' => 'Although', 'explanation' => '"Although" is followed by a full clause (subject + verb). "Despite" needs a noun/gerund, not a full clause.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Despite', 'the', 'complexity', 'the', 'solution', 'was', 'elegant'], 'correct_answer' => 'Despite the complexity the solution was elegant', 'explanation' => '"Despite + noun, result": Despite the complexity (noun), the solution was elegant (result).', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The code is efficient; ___, it is difficult to read."', 'options' => null, 'correct_answer' => 'however', 'explanation' => '"However" introduces a contrasting idea after a semicolon. It concedes the first point but adds a counterpoint.'],
                    ['type' => 'free_write', 'prompt' => 'Write a 3-4 sentence argument about a technical decision, using concession. Use: although, despite, however, nevertheless, admittedly.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }

    // ─── C2 ──────────────────────────────────────────────────────────────────

    private function c2Lessons(): array
    {
        return [
            [
                'order' => 1,
                'title' => 'Distinções Verbais Sutis — Make, Do, Say, Tell',
                'objective' => 'Master the subtle distinctions between commonly confused English verbs',
                'grammar_point' => 'make vs do / say vs tell / bring vs take',
                'intro_text' => 'Near-native proficiency requires mastering subtle verb distinctions. Make, do, say, and tell are among the most commonly confused verbs in English — even advanced speakers make errors. This lesson eliminates those gaps.',
                'vocabulary' => ['make a decision', 'make progress', 'make a mistake', 'do research', 'do a favour', 'do your best', 'say something', 'say hello', 'tell someone', 'tell the truth', 'tell a story', 'bring value', 'take action', 'take notes'],
                'examples' => [
                    ['en' => 'We need to make a decision before the sprint ends.', 'pt' => 'Precisamos tomar uma decisão antes do sprint terminar.'],
                    ['en' => 'She always does thorough research before proposing a solution.', 'pt' => 'Ela sempre faz uma pesquisa completa antes de propor uma solução.'],
                    ['en' => 'He told the team about the architectural change.', 'pt' => 'Ele informou a equipe sobre a mudança arquitetural.'],
                    ['en' => 'Could you take notes during the meeting?', 'pt' => 'Você poderia tomar notas durante a reunião?'],
                ],
                'tips' => [
                    "Make: creation, decisions, mistakes — make a plan, make an error, make progress.",
                    "Do: actions, tasks, work — do research, do a favour, do your best, do damage.",
                    "Say = the words spoken; Tell = the person receiving: 'say something TO someone' vs 'tell someone something'.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete: "We need to ___ a decision about the architecture today."', 'options' => ['make', 'do', 'take', 'say'], 'correct_answer' => 'make', 'explanation' => '"Make a decision" is a fixed collocation. Other fixed expressions: make a mistake, make progress, make a plan.'],
                    ['type' => 'mcq', 'prompt' => 'Which is correct? "She ___ me about the new requirement."', 'options' => ['told', 'said', 'spoke', 'talked'], 'correct_answer' => 'told', 'explanation' => '"Tell" needs an indirect object (a person): told me, told the team. "Say" does not take a person directly.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['He', 'made', 'significant', 'progress', 'on', 'the', 'refactor'], 'correct_answer' => 'He made significant progress on the refactor', 'explanation' => '"Make progress" is a fixed collocation. Not "do progress" or "take progress".', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "Could you ___ me a favour and review this PR?"', 'options' => null, 'correct_answer' => 'do', 'explanation' => '"Do someone a favour" is a fixed expression. Not "make me a favour" — that is a common error.'],
                    ['type' => 'free_write', 'prompt' => 'Use make, do, say, and tell correctly in a short work narrative (3-4 sentences). Include: make a decision, do research, tell someone, say something.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 2,
                'title' => 'Dispositivos Coesivos Avançados',
                'objective' => 'Use advanced discourse markers to create cohesive, logical professional writing',
                'grammar_point' => 'Furthermore / Consequently / Given that / Therefore / Notwithstanding',
                'intro_text' => 'Advanced cohesive devices signal how ideas relate — causation, addition, contrast, condition. Mastering these transforms adequate writing into persuasive, publication-ready prose.',
                'vocabulary' => ['furthermore', 'moreover', 'in addition', 'consequently', 'as a result', 'therefore', 'thus', 'hence', 'given that', 'provided that', 'in light of', 'with regard to', 'notwithstanding', 'accordingly'],
                'examples' => [
                    ['en' => 'The system was optimised; consequently, response times dropped by 60%.', 'pt' => 'O sistema foi otimizado; consequentemente, os tempos de resposta caíram 60%.'],
                    ['en' => 'Furthermore, the new architecture supports horizontal scaling.', 'pt' => 'Além disso, a nova arquitetura suporta escalonamento horizontal.'],
                    ['en' => 'Given that the deadline is approaching, we should prioritise core features.', 'pt' => 'Dado que o prazo está se aproximando, devemos priorizar as funcionalidades principais.'],
                    ['en' => 'The migration was complex; nevertheless, the team completed it on schedule.', 'pt' => 'A migração foi complexa; no entanto, a equipe a concluiu dentro do prazo.'],
                ],
                'tips' => [
                    "'Furthermore' and 'Moreover' add a stronger or additional point.",
                    "'Consequently' and 'Therefore' show cause-effect; use after a semicolon or at sentence start.",
                    "'Given that' introduces a premise or condition: Given that X, we should Y.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which connector shows cause and effect?', 'options' => ['Consequently', 'Furthermore', 'However', 'Although'], 'correct_answer' => 'Consequently', 'explanation' => '"Consequently" = as a result of. It shows that the second idea is the direct consequence of the first.'],
                    ['type' => 'mcq', 'prompt' => 'Complete: "___ the deadline is tight, we must prioritise ruthlessly."', 'options' => ['Given that', 'Moreover', 'However', 'Despite'], 'correct_answer' => 'Given that', 'explanation' => '"Given that" introduces a premise or acknowledged condition that informs the conclusion that follows.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['Furthermore', 'the', 'new', 'system', 'is', 'more', 'cost', 'effective'], 'correct_answer' => 'Furthermore the new system is more cost effective', 'explanation' => '"Furthermore" adds an additional supporting point. It comes at the start of the sentence.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "The tests passed; ___, we can proceed with the deployment."', 'options' => null, 'correct_answer' => 'therefore', 'explanation' => '"Therefore" signals a logical conclusion: X happened, therefore Y follows. Formal and precise.'],
                    ['type' => 'free_write', 'prompt' => 'Write a 4-5 sentence technical summary of a project decision using advanced connectors: furthermore, consequently, given that, therefore, in light of.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 3,
                'title' => 'Registro e Tom — Formal, Informal e Técnico',
                'objective' => 'Match language register to audience: executives, teammates, and engineers',
                'grammar_point' => 'Register awareness: formal / informal / technical language',
                'intro_text' => 'One of the clearest markers of near-native proficiency is the ability to shift register effortlessly. The same message must be framed differently for a CEO, a colleague, and an SRE — this lesson gives you that control.',
                'vocabulary' => ['I wish to bring to your attention', 'I would be grateful', 'please do not hesitate', 'heads up', 'FYI', 'ASAP', 'let me know', 'implement', 'deploy', 'iterate', 'leverage', 'refactor', 'on my radar', 'loop in'],
                'examples' => [
                    ['en' => 'Formal: "I wish to bring to your attention a critical issue with the authentication system."', 'pt' => 'Formal: Desejo trazer ao seu conhecimento um problema crítico no sistema de autenticação.'],
                    ['en' => "Informal: 'Hey, heads up — there's a bug in auth.'", 'pt' => "Informal: 'Ei, atenção — tem um bug no auth.'"],
                    ['en' => "Technical: 'The OAuth2 flow has a race condition in the token refresh logic.'", 'pt' => "Técnico: 'O fluxo OAuth2 tem uma condição de corrida na lógica de atualização do token.'"],
                    ['en' => "Formal close: 'I would be grateful if you could review this at your earliest convenience.'", 'pt' => "Fechamento formal: 'Ficaria grato se pudesse analisar isso assim que possível.'"],
                ],
                'tips' => [
                    "Match register to audience: formal for executives/clients, casual for teammates, technical for engineers.",
                    "Avoid mixing registers: 'Hey Mr. CEO, FYI there's a major bug ASAP' sounds jarring and unprofessional.",
                    "Technical jargon is precise but excludes non-technical stakeholders — know when to translate it.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which message is appropriate for a CEO about a security incident?', 'options' => ['I wish to bring to your attention a critical security vulnerability.', 'Hey, we got hacked lol.', 'FYI: security bug.', 'There is a bug I think.'], 'correct_answer' => 'I wish to bring to your attention a critical security vulnerability.', 'explanation' => 'Messages to executives require formal register: clear, professional, and no informal abbreviations.'],
                    ['type' => 'mcq', 'prompt' => 'Which message is most appropriate in a quick team Slack message?', 'options' => ['Heads up — the deploy failed!', 'I wish to inform you that the deployment was unsuccessful.', 'The deployment process has encountered an error condition.', 'Deployment failure has been identified and logged.'], 'correct_answer' => 'Heads up — the deploy failed!', 'explanation' => '"Heads up" is informal and direct — appropriate for quick team communication. Formal language in Slack feels unnatural.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'system', 'has', 'a', 'critical', 'security', 'vulnerability'], 'correct_answer' => 'The system has a critical security vulnerability', 'explanation' => 'This is a clear, formal statement appropriate for professional communication about a serious issue.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "I ___ to bring to your attention a serious issue with our data pipeline."', 'options' => null, 'correct_answer' => 'wish', 'explanation' => '"I wish to bring to your attention..." is a formal phrase used when reporting an important issue to a superior.'],
                    ['type' => 'free_write', 'prompt' => 'Write the same message (a production server outage) in 3 registers: formal (to the CEO), informal (to your team), and technical (to the SRE team). Show register mastery.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 4,
                'title' => 'Condicionais Mistas e Subjuntivo',
                'objective' => 'Use mixed conditionals and subjunctive mood for nuanced hypothetical reasoning',
                'grammar_point' => 'Mixed Conditionals: past + present / Subjunctive: I suggest that he be...',
                'intro_text' => 'Mixed conditionals and the subjunctive mood are hallmarks of C2 proficiency. They allow you to reason across time frames and express formal recommendations — essential for architecture decisions, RFCs, and governance documents.',
                'vocabulary' => ['were', 'had been', 'would have', 'would be', 'I suggest', 'it is essential that', 'it is imperative that', 'I recommend that', 'provided that', 'on the assumption that', 'had we', 'were it not for'],
                'examples' => [
                    ['en' => 'If we had adopted microservices two years ago, scaling would be much easier now.', 'pt' => 'Se tivéssemos adotado microsserviços há dois anos, escalar seria muito mais fácil agora.'],
                    ['en' => 'If I were more experienced with distributed systems, I would have designed it differently.', 'pt' => 'Se eu tivesse mais experiência com sistemas distribuídos, o teria projetado de forma diferente.'],
                    ['en' => 'I suggest that the team review the security policies immediately.', 'pt' => 'Sugiro que a equipe revise as políticas de segurança imediatamente.'],
                    ['en' => 'It is imperative that every engineer read the onboarding documentation.', 'pt' => 'É imperativo que todo engenheiro leia a documentação de integração.'],
                ],
                'tips' => [
                    "Mixed conditional type 1: If + past perfect → would + base verb (past hypothesis, present result).",
                    "Mixed conditional type 2: If + past simple → would have + past participle (present state, past result).",
                    "Subjunctive after suggest/recommend/insist/essential/imperative: base verb, no -s: 'I suggest he be...'",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Complete (mixed conditional): "If we ___ better tests, we would not have so many bugs now."', 'options' => ['had written', 'have written', 'wrote', 'write'], 'correct_answer' => 'had written', 'explanation' => 'Mixed conditional: past hypothetical (had written) → present result (would not have). Past Perfect in the "if" clause.'],
                    ['type' => 'mcq', 'prompt' => 'Which uses the subjunctive correctly?', 'options' => ['I recommend that the team review the policy.', 'I recommend that the team reviews the policy.', 'I recommend that the team reviewed the policy.', 'I recommend the team to review the policy.'], 'correct_answer' => 'I recommend that the team review the policy.', 'explanation' => 'Subjunctive after "recommend that": use the base form (review), not third-person -s (reviews).'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['If', 'we', 'had', 'invested', 'in', 'CI', 'releases', 'would', 'be', 'smoother'], 'correct_answer' => 'If we had invested in CI releases would be smoother', 'explanation' => 'Mixed conditional: If we had invested (past perfect) → releases would be smoother (present result).', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "It is essential that every engineer ___ the security guidelines."', 'options' => null, 'correct_answer' => 'read', 'explanation' => 'Subjunctive after "it is essential that": base form (read), not "reads". This is a formal, impersonal construction.'],
                    ['type' => 'free_write', 'prompt' => 'Write 2 sentences using mixed conditionals about technology decisions. Then write 1 sentence using the subjunctive (recommend/suggest/essential that). Show mastery of these advanced structures.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
            [
                'order' => 5,
                'title' => 'Escrita de Precisão — Concisão e Impacto',
                'objective' => 'Write with precision: eliminate redundancy, prefer active voice, choose powerful verbs',
                'grammar_point' => 'Precision writing: active voice / strong verbs / no redundancy',
                'intro_text' => 'The final mark of C2 mastery is writing that is precise, concise, and impactful. This lesson focuses on the principles that separate competent writing from truly excellent professional and technical prose.',
                'vocabulary' => ['leverage', 'optimise', 'streamline', 'facilitate', 'mitigate', 'robust', 'scalable', 'comprehensive', 'meticulous', 'pivotal', 'accelerate', 'yield', 'demonstrate', 'substantiate', 'eliminate'],
                'examples' => [
                    ['en' => 'Redundant: "We made the decision to use..." → Precise: "We chose..."', 'pt' => 'Redundante: "Tomamos a decisão de usar..." → Preciso: "Escolhemos..."'],
                    ['en' => 'Nominalisation: "The implementation of the system..." → Better: "Implementing the system..."', 'pt' => 'Nominalização: "A implementação do sistema..." → Melhor: "Implementar o sistema..."'],
                    ['en' => 'Passive (weak): "Mistakes were made." → Active (strong): "The team made mistakes."', 'pt' => 'Passiva (fraca): "Erros foram cometidos." → Ativa (forte): "A equipe cometeu erros."'],
                    ['en' => 'Wordy: "Due to the fact that..." → Concise: "Because..."', 'pt' => 'Prolixa: "Devido ao fato de que..." → Concisa: "Porque..."'],
                ],
                'tips' => [
                    "Cut redundant phrases: 'in order to' → 'to', 'due to the fact that' → 'because'.",
                    "Prefer active voice for clarity and accountability: 'We deployed' not 'It was deployed by us'.",
                    "Strong verbs beat nominalisations: 'We optimised' not 'We performed optimisation of'.",
                ],
                'xp_reward' => 50,
                'exercises' => [
                    ['type' => 'mcq', 'prompt' => 'Which version is more concise?', 'options' => ['We chose to use TypeScript.', 'We made the decision to use TypeScript.', 'It was decided to use TypeScript.', 'The decision was made that TypeScript would be used.'], 'correct_answer' => 'We chose to use TypeScript.', 'explanation' => '"We chose" is a single strong verb. "Made the decision to" is redundant padding — cut it.'],
                    ['type' => 'mcq', 'prompt' => 'Choose the cleaner version of: "Due to the fact that the server was down, we lost data."', 'options' => ['Because the server was down, we lost data.', 'Due to the fact that the server was down, we lost data.', 'The server being down meant there was a data loss occurrence.', 'Loss of data occurred because of a server outage situation.'], 'correct_answer' => 'Because the server was down, we lost data.', 'explanation' => '"Because" replaces the wordy "due to the fact that". One word instead of five — same meaning, sharper impact.'],
                    ['type' => 'order_sentence', 'prompt' => 'Put the words in the correct order:', 'options' => ['The', 'team', 'optimised', 'the', 'query', 'and', 'reduced', 'latency'], 'correct_answer' => 'The team optimised the query and reduced latency', 'explanation' => 'Active voice with strong verbs: optimised, reduced. Clear, direct, accountable.', 'xp_reward' => 15],
                    ['type' => 'fill_blank', 'prompt' => 'Complete: "We ___ the onboarding process, reducing setup time by 50%."', 'options' => null, 'correct_answer' => 'streamlined', 'explanation' => '"Streamlined" is a single precise verb meaning made more efficient/simpler. Better than "made the onboarding process more streamlined".'],
                    ['type' => 'free_write', 'prompt' => 'Write a 3-4 sentence executive summary of a technical project. Focus on precision, active voice, and strong verbs. No redundancy, no filler phrases. Every word must earn its place.', 'options' => null, 'correct_answer' => null, 'explanation' => null, 'xp_reward' => 15],
                ],
            ],
        ];
    }
}
