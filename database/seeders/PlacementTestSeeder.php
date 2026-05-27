<?php

namespace Database\Seeders;

use App\Models\PlacementTestQuestion;
use Illuminate\Database\Seeder;

class PlacementTestSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            // A1 — 3 questions
            ['cefr_level' => 'A1', 'order' => 1, 'question' => 'Complete: "I ___ a software developer."', 'options' => ['am', 'is', 'are', 'be'], 'correct_answer' => 'am', 'explanation' => 'Use "am" with the pronoun "I". I am, you are, he/she is.'],
            ['cefr_level' => 'A1', 'order' => 2, 'question' => 'Choose the correct article: "She is ___ engineer."', 'options' => ['a', 'an', 'the', '(none)'], 'correct_answer' => 'an', 'explanation' => 'Use "an" before words starting with a vowel sound. "Engineer" starts with "e".'],
            ['cefr_level' => 'A1', 'order' => 3, 'question' => 'Which is correct? "He ___ code every day."', 'options' => ['writes', 'write', 'writing', 'wrote'], 'correct_answer' => 'writes', 'explanation' => 'With he/she/it in Simple Present, add -s: write → writes.'],

            // A2 — 3 questions
            ['cefr_level' => 'A2', 'order' => 1, 'question' => 'Complete: "We ___ the new feature last week."', 'options' => ['launched', 'launch', 'launching', 'will launch'], 'correct_answer' => 'launched', 'explanation' => '"Last week" signals past tense. Regular verb: launch → launched.'],
            ['cefr_level' => 'A2', 'order' => 2, 'question' => 'Choose the correct comparative: "Python is ___ than Java for beginners."', 'options' => ['easier', 'more easy', 'easiest', 'easy more'], 'correct_answer' => 'easier', 'explanation' => 'Short adjectives use -er for comparatives: easy → easier.'],
            ['cefr_level' => 'A2', 'order' => 3, 'question' => 'Complete: "I ___ deploy the update tomorrow." (It is in your sprint plan)', 'options' => ['am going to', 'will', 'would', 'shall'], 'correct_answer' => 'am going to', 'explanation' => '"Going to" expresses a pre-planned intention. "Will" is for spontaneous decisions.'],

            // B1 — 3 questions
            ['cefr_level' => 'B1', 'order' => 1, 'question' => '"She ___ just pushed a new commit."', 'options' => ['has', 'have', 'had', 'is'], 'correct_answer' => 'has', 'explanation' => 'Present Perfect with "she": has + past participle. "She has just pushed..."'],
            ['cefr_level' => 'B1', 'order' => 2, 'question' => 'Which sentence uses passive voice correctly?', 'options' => ['The API is tested automatically.', 'We test the API automatically.', 'Test the API automatically.', 'The API tests automatically.'], 'correct_answer' => 'The API is tested automatically.', 'explanation' => 'Passive voice: subject + is/are + past participle. The API (subject) is tested (passive verb).'],
            ['cefr_level' => 'B1', 'order' => 3, 'question' => 'What does "roll back" mean in software development?', 'options' => ['Revert to a previous version', 'Log in to the system', 'Set up the environment', 'Look outside the project'], 'correct_answer' => 'Revert to a previous version', 'explanation' => '"Roll back" is a phrasal verb meaning to revert a deployment or change to an earlier state.'],

            // B2 — 3 questions
            ['cefr_level' => 'B2', 'order' => 1, 'question' => '"By the time I arrived, the team ___ already merged the PR."', 'options' => ['had', 'has', 'have', 'did'], 'correct_answer' => 'had', 'explanation' => 'Past Perfect: had + past participle. Used when one past event happened before another.'],
            ['cefr_level' => 'B2', 'order' => 2, 'question' => 'Complete: "If I ___ the CTO, I would invest more in AI tools."', 'options' => ['were', 'was', 'am', 'are'], 'correct_answer' => 'were', 'explanation' => 'Second Conditional: use "were" for all subjects in formal English. If I were...'],
            ['cefr_level' => 'B2', 'order' => 3, 'question' => 'Complete: "She ___ me that the server was down."', 'options' => ['told', 'said', 'spoke', 'asked'], 'correct_answer' => 'told', 'explanation' => '"Tell" needs an indirect object: told ME. "Said" does not take a person directly.'],

            // C1 — 3 questions
            ['cefr_level' => 'C1', 'order' => 1, 'question' => 'Complete: "Never ___ I encountered such a complex system."', 'options' => ['have', 'had', 'has', 'did'], 'correct_answer' => 'have', 'explanation' => 'Inversion after "Never": auxiliary before subject. Present Perfect: Never have I...'],
            ['cefr_level' => 'C1', 'order' => 2, 'question' => 'Which sentence uses hedging language?', 'options' => ['It appears that the server is overloaded.', 'The server is overloaded.', 'Fix the server immediately.', 'The server always fails.'], 'correct_answer' => 'It appears that the server is overloaded.', 'explanation' => '"It appears that" is a hedging phrase — it expresses a tentative conclusion rather than a definite fact.'],
            ['cefr_level' => 'C1', 'order' => 3, 'question' => 'What does "take this offline" mean in a meeting?', 'options' => ['Discuss it privately/separately', 'Go outside the building', 'Disconnect from the internet', 'Stop the meeting entirely'], 'correct_answer' => 'Discuss it privately/separately', 'explanation' => '"Take offline" = continue this discussion in a private channel, not in the current meeting.'],

            // C2 — 3 questions
            ['cefr_level' => 'C2', 'order' => 1, 'question' => 'Which sentence is most concise?', 'options' => ['We chose TypeScript.', 'We made the decision to use TypeScript.', 'It was decided to use TypeScript.', 'TypeScript was chosen by our team.'], 'correct_answer' => 'We chose TypeScript.', 'explanation' => '"We chose" is a single strong verb. "Made the decision to use" is redundant padding.'],
            ['cefr_level' => 'C2', 'order' => 2, 'question' => 'Complete: "I recommend that the team ___ the security policy."', 'options' => ['review', 'reviews', 'reviewed', 'reviewing'], 'correct_answer' => 'review', 'explanation' => 'Subjunctive after "recommend that": use the base form (review), not third-person -s.'],
            ['cefr_level' => 'C2', 'order' => 3, 'question' => '"The deployment was successful; ___, we can release tomorrow."', 'options' => ['consequently', 'although', 'despite', 'unless'], 'correct_answer' => 'consequently', 'explanation' => '"Consequently" signals cause-effect: the successful deployment leads to the ability to release.'],
        ];

        foreach ($questions as $q) {
            PlacementTestQuestion::updateOrCreate(
                ['cefr_level' => $q['cefr_level'], 'order' => $q['order']],
                $q
            );
        }
    }
}
