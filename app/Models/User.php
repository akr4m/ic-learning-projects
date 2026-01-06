<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model
 *
 * এই Model টি users টেবিলের সাথে সম্পর্কিত।
 * Laravel এর built-in Authenticatable class extend করে,
 * যার ফলে authentication সংক্রান্ত সব features পাওয়া যায়।
 *
 * @see https://laravel.com/docs/eloquent
 * @see https://laravel.com/docs/authentication
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User ও Task এর মধ্যে সম্পর্ক নির্ধারণ করে।
     *
     * এটি একটি HasMany relationship।
     * অর্থাৎ, একজন User এর অনেকগুলো Task থাকতে পারে।
     *
     * ব্যবহার: $user->tasks অথবা $user->tasks()->where('status', 'pending')->get()
     *
     * @return HasMany<Task, User>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
