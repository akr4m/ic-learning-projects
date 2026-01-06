<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * User Model
 *
 * এই model authenticated users এর তথ্য manage করে।
 * প্রতিটি user এর একটি role আছে: 'author' অথবা 'editor'
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role - 'author' বা 'editor'
 * @property string $password
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Mass assignment এ কোন fields গুলো fillable
     * এই fields গুলো create() বা update() এ সরাসরি set করা যাবে
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * JSON/Array তে convert করলে কোন fields লুকানো থাকবে
     * Password এবং remember_token security এর জন্য hide করা হয়
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting - database value কে PHP type এ convert করে
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Auto hash password
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    | Laravel Eloquent relationships define how models relate to each other.
    | One User has many Posts (One-to-Many relationship)
    */

    /**
     * User এর সব posts
     * একজন User এর অনেকগুলো Post থাকতে পারে (One-to-Many)
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Methods
    |--------------------------------------------------------------------------
    | এই methods গুলো role check করতে সাহায্য করে।
    | Code readability বাড়ায় এবং role check সহজ করে।
    */

    /**
     * User কি Author?
     * Author রা blog post লিখতে পারে
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    /**
     * User কি Editor?
     * Editor রা posts approve/reject করতে পারে
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }
}
