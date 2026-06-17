<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Sortable;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';
    const STATUS_NEW = 'new';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'personal_email',
        'image',
        'alert',
        'role',
        'status',
        'address',
        'location',
        'job_title',
        'organisation_id',
        'reg_source',
        'why_spread',
        'university_id',
        'students_alert',
        'alert_to_personal',
        'contact_to_personal',
        'is_subscribed',
        'provider',
        'provider_id',
        'linkedin_url',
        'number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $appends = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return mixed
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return '/img/img-default.png';
        }
        return '/storage' . $value;
    }

    /**
     * @return BelongsTo
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * @return BelongsTo
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * @return HasMany
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(ThreadView::class);
    }

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    /**
     * @return BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Thread::class, 'favorites');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearchName($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhereHas('organisation', function ($organisationQuery) use ($search) {
                        $organisationQuery->where('name', 'like', '%' . $search . '%');
                    });
            });;
        });
    }

    /**
     * @param $query
     * @param $location
     * @return mixed
     */
    public function scopeSearchLocation($query, $location)
    {
        return $query->when($location, function ($q) use ($location) {
            $q->where('location', 'like', '%' . $location . '%');
        });
    }

    /**
     * @param $query
     * @param $type
     * @param $id
     * @return mixed
     */
    public function scopeSearchType($query, $type, $id)
    {
        return $query->when($type == 'organisation', function ($q) use ($id) {
            $q->whereHas('organisation', function ($organisationQuery) use ($id) {
                $organisationQuery->where('id', $id);
            });
        })->when($type == 'user', function ($q) use ($id) {
            $q->where('id', $id);
        });
    }

    /**
     * @return BelongsToMany
     */
    public function allowed_quizes()
    {
        return $this->belongsToMany(Quiz::class, 'user_allowed_quizes', 'user_id', 'quiz_id');
    }

    /**
     * @return HasMany
     */
    public function quiz_reports()
    {
        return $this->hasMany(QuizReport::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }

    public function getAllowedQuizes()
    {
        $query = Quiz::query();
        if ($this->isAdmin()) {

            return $query;
        } else {

            return $query->where(function (Builder $q) {
                $q->orWhereHas('allowed_users', function ($q) {
                    $q->where('users.id', $this->id);
                });
                $q->orWhereIn('company_id', $this->companies()->pluck('id'));
            });
        }
    }
    function verified()
    {
        return $this->status == 'active' ? true : false;
    }
    function completeName()
    {
        $complete_name = $this->first_name . " " . $this->last_name;
        return $complete_name;
    }
}
