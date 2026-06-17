<?php

namespace App\Models;


use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes, Slugable;

    /**
     * @var string
     */
    protected $table = 'quizes';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'class',
        'title',
        'description',
        'time_limit',
        'answer_by_one',
        'slug',
        'is_active',
        'is_required_fields',
        'company_id',
        'verification_text_1',
        'verification_text_2',
        'verification_text_3',
        'verification_text_4',
        'verification_text_5',
        'quiz_code'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'answer_by_one'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique code like: F65A9C
            /* $randomCode = bin2hex(random_bytes(3));
            $model->quiz_code = strtoupper($randomCode); */
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $alphanumeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // 1. Get 2 random letters
            $randomLetters = substr(str_shuffle($letters), 0, 2);
            // 2. Get 4 random characters (numbers or letters)
            $randomPayload = substr(str_shuffle($alphanumeric), 0, 4);
            // 3. Combine them
            $model->quiz_code = $randomLetters . $randomPayload;
            // Output examples: FX65A9, QZ497C, AB12T9
        });
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * @param $quiz
     * @return array|string
     * @throws \Throwable
     */

    public function quiz_user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function user_quizes()
    {
        return $this->hasMany(UserQuiz::class, 'quiz_id', 'id');
    }

    public static function laratablesCustomActions($quiz)
    {
        return view('dashboard.quiz.custom._actions', compact('quiz'))->render();
    }
    public static function laratablesCustomQuizCode($quiz)
    {
        return view(
            'dashboard.quiz.custom._quiz_code_link',
            compact('quiz')
        )->render();
    }
    public function courses(): MorphToMany
    {
        return $this->morphedByMany('App\Course', 'quizable', 'quizables');
    }

    public function quiz_reports()
    {
        return $this->hasMany(QuizReport::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function groups()
    {
        return $this->belongsToMany(GroupForQuiz::class, 'quiz_groups', 'quiz_id', 'group_id');
    }
    /**
     * @return BelongsToMany
     */
    public function allowed_users()
    {
        return $this->belongsToMany(User::class, 'user_allowed_quizes', 'quiz_id', 'user_id');
    }
}
