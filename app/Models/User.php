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
 * প্রতিটি user এর একটি role আছে: 'author', 'editor', অথবা 'admin'
 *
 * Role Hierarchy:
 * - admin: সব কিছু করতে পারে, user roles manage করতে পারে
 * - editor: posts approve/reject করতে পারে
 * - author: posts লিখতে পারে
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role - 'author', 'editor', বা 'admin'
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
     * User কি Admin?
     * Admin রা user roles manage করতে পারে
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * User কি Editor?
     * Editor রা posts approve/reject করতে পারে
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    /**
     * User কি Author?
     * Author রা blog post লিখতে পারে
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    /**
     * User কি Editor বা তার উপরের role?
     * Editor এবং Admin উভয়েই posts approve করতে পারে
     */
    public function canManagePosts(): bool
    {
        return $this->isEditor() || $this->isAdmin();
    }

    /**
     * Role এর বাংলা নাম return করে
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'অ্যাডমিন',
            'editor' => 'এডিটর',
            'author' => 'লেখক',
            default => $this->role,
        };
    }
}
