<?php

/**
 * Migration: Add role column to users table
 *
 * এই migration users টেবিলে role কলাম যোগ করে।
 * Role দুই ধরনের: 'author' এবং 'editor'
 * Default role হলো 'author'
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
            $table->enum('role', ['author', 'editor'])->default('author')->after('email');
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
