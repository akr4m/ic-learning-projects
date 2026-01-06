<?php

/**
 * Tasks টেবিল Migration
 *
 * এই মাইগ্রেশন ফাইলটি tasks টেবিল তৈরি করে।
 * প্রতিটি task একজন user-এর সাথে সম্পর্কিত (foreign key relationship)।
 *
 * @see https://laravel.com/docs/migrations
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration চালানোর সময় এই মেথড execute হয়।
     * এখানে টেবিল তৈরি করা হয়।
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            // Primary key - auto increment ID
            $table->id();

            // Foreign key - কোন user এই task তৈরি করেছে
            // onDelete('cascade') মানে user মুছে গেলে তার সব task-ও মুছে যাবে
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Task এর শিরোনাম - সর্বোচ্চ 255 characters
            $table->string('title');

            // Task এর বিস্তারিত বিবরণ - optional field (nullable)
            $table->text('description')->nullable();

            // Task এর অবস্থা - pending, in_progress, completed
            // default value হলো 'pending'
            $table->enum('status', ['pending', 'in_progress', 'completed'])
                  ->default('pending');

            // File attachment এর path সংরক্ষণ করার জন্য
            // nullable কারণ সব task-এ file নাও থাকতে পারে
            $table->string('attachment_path')->nullable();

            // Original file name সংরক্ষণ - download এর সময় কাজে লাগবে
            $table->string('attachment_name')->nullable();

            // Task এর deadline - optional
            $table->date('due_date')->nullable();

            // Laravel এর built-in timestamps - created_at ও updated_at
            $table->timestamps();
        });
    }

    /**
     * Migration রোলব্যাক করার সময় এই মেথড execute হয়।
     * এখানে টেবিল মুছে ফেলা হয়।
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
