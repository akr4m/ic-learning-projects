<?php

/**
 * Migration: Add role column to users table
 *
 * এই migration users টেবিলে role কলাম যোগ করে।
 * Role তিন ধরনের: 'author', 'editor', এবং 'admin'
 * Default role হলো 'author'
 *
 * Role Hierarchy:
 * - admin: সব কিছু করতে পারে, user roles manage করতে পারে
 * - editor: posts approve/reject করতে পারে
 * - author: posts লিখতে পারে
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration চালানো হলে users টেবিলে role কলাম যোগ হবে
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role কলাম - enum type ব্যবহার করা হয়েছে
            // 'author' হলো default value
            $table->enum('role', ['author', 'editor', 'admin'])->default('author')->after('email');
        });
    }

    /**
     * Migration rollback করলে role কলাম মুছে যাবে
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
