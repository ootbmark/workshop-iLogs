<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuiz extends Model
{
    use SoftDeletes;

    protected $table = 'user_quizes';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'is_completed',
        'group_id',
        'participate_name'
    ];

    protected $casts = [
        'is_completed' => 'boolean'
    ];

    /**
     * @param Builder $builder
     * @param int $user_id
     * @param int $quiz_id
     * @return Builder
     */
    public function scopeByUserIDAndQuizID(Builder $builder, int $user_id, int $quiz_id): Builder
    {
        return $builder
            ->where('user_id', $user_id)
            ->where('quiz_id', $quiz_id)
            ->orderBy('id', 'desc');
    }

    /**
     * @return HasMany
     */
    public function quiz_answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    function group()
    {
        return $this->belongsTo(GroupForQuiz::class, 'group_id');
    }
}
