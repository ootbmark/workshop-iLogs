<?php

namespace App\Models;

use App\Imports\Mapping\QuizMapping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizReport extends Model
{
    public const SCOPE_ONLY_FRESH = 'only_fresh';

    protected $table = 'quiz_reports';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'name',
        'questions_count',
        'answers_count',
        'quiz_duration',
        'questions_answers',
        'status',
        'status_effort',
        'quiz_status',
        'focal_point',
        'action_party',
        'target_date',
        'group_id',
        'report_status',
        'parent_id',
        'quiz_user_id',
        'priority',
        'business_partner',
        'is_verification_1',
        'is_verification_2',
        'is_verification_3',
        'is_verification_4',
        'is_verification_5',
    ];

    protected $casts = [
        'is_verification_1' => 'boolean',
        'is_verification_2' => 'boolean',
        'is_verification_3' => 'boolean',
        'is_verification_4' => 'boolean',
        'is_verification_5' => 'boolean',
    ];

    protected $appends = [
        'question_answers'
    ];

    protected static function booted()
    {
        static::addGlobalScope(self::SCOPE_ONLY_FRESH, function (Builder $builder) {
            $builder->whereNull('parent_id');
        });
    }

    public function newer()
    {
        return $this->belongsTo(self::class, 'parent_id')->withoutGlobalScope(self::SCOPE_ONLY_FRESH);
    }

    public function older()
    {
        return $this->hasOne(self::class, 'parent_id')->withoutGlobalScope(self::SCOPE_ONLY_FRESH);
    }

    /**
     * Returns change list from previous revision and null if there isn't previous revision
     *
     * @param QuizMapping $mapping
     *
     * @return array|null
     */
    public function listChanges(QuizMapping $mapping): ?array
    {
        if ($this->older === null) {
            return null;
        }

        $report = $this->listDataFor($this->id);
        $oldReport = $this->listDataFor($this->older->id);

        $changeList = [];
        foreach ($report as $key => $item) {
            if ($key === 'group') {

                $groupChanges = $this->getGroupChanges($oldReport[$key], $item);
                if ($groupChanges !== null) {
                    $changeList[] = $groupChanges;
                }

                continue;
            }

            $baseChanges = $this->getBaseChanges($mapping, $key, $item, $oldReport[$key]);
            if ($baseChanges !== null) {
                $changeList[] = $baseChanges;
            }
        }

        $questionChanges = $this->getQuestionChanges($mapping, $this, $this->older);

        return array_merge($changeList, $questionChanges);
    }

    public function getBaseChanges(QuizMapping $mapping, string $key, $after, $before): ?array
    {
        if ($after === $before) {

            return null;
        }

        if (strpos($key, 'is_verification_') !== false) {
            $after = $after ? 'Yes' : 'No';
            $before = $before ? 'Yes' : 'No';
        }

        return [
            'label' => $mapping->getTitleForKey($key),
            'after' => $after,
            'before' => $before,
        ];
    }

    private function getQuestionChanges(QuizMapping $mapping, self $report, self $oldReport): array
    {
        $results = [];

        $questions = $report->quiz->questions ?? [];
        foreach ($questions as $question) {
            $answer = $mapping->answersToString($report, $question);
            $oldAnswer = $mapping->answersToString($oldReport, $question);

            if ($answer === $oldAnswer) {

                continue;
            }

            $results[] = [
                'label' => $mapping->getTitleForKey($question->id),
                'after' => $answer,
                'before' => $oldAnswer,
            ];
        }

        return $results;
    }

    private function getGroupChanges($oldData, $newData): ?array
    {
        $oldGroup = $oldData['name'] ?? null;
        $group = $newData['name'] ?? null;
        if ($group === $oldGroup) {

            return null;
        }

        return [
            'label' => 'Group',
            'after' => $group,
            'before' => $oldGroup,
        ];
    }

    private function listDataFor(int $id): array
    {
        $report = $this->query()
            ->withoutGlobalScope(self::SCOPE_ONLY_FRESH)
            ->select(
                'id',
                'quiz_id',
                'group_id',
                'status',
                'status_effort',
                'priority',
                'action_party',
                'focal_point',
                'target_date',
                'business_partner',
                'is_verification_1',
                'is_verification_2',
                'is_verification_3',
                'is_verification_4',
                'is_verification_5',
                'report_status'
            )
            ->with(['group' => function ($q) {
                $q->select('id', 'name');
            }])
            ->findOrFail($id);

        $report->makeHidden(['id', 'quiz_id', 'group_id', 'question_answers']);
        if ($report->group !== null) {
            $report->group->makeHidden('id');
        }

        return $report->toArray();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function getQuestionAnswersAttribute()
    {
        return "{$this->questions_count} / {$this->answers_count}";
    }

    public static function laratablesCustomActions($quiz_report)
    {
        return view('dashboard.quiz.custom._actions', [
            'quiz_report' => $quiz_report
        ])->render();
    }
    public static function laratablesCustomQuizCodeLink($quiz)
    {
        return view('dashboard.quiz.custom._quiz_code_link', compact('quiz'))->render();
    }
    public function quiz_answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function group()
    {
        return $this->belongsTo(GroupForQuiz::class);
    }
    function quizUser()
    {
        return $this->belongsTo(UserQuiz::class, 'quiz_user_id');
    }
}
