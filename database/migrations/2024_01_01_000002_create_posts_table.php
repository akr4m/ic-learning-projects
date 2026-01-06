<?php

/**
 * Migration: Create posts table
 *
 * এই migration ব্লগ পোস্ট সংরক্ষণের জন্য posts টেবিল তৈরি করে।
 * প্রতিটি পোস্টের একজন author থাকবে (users টেবিলের সাথে relationship)
 * পোস্টের status চার ধরনের: draft, pending, published, rejected
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migration চালালে posts টেবিল তৈরি হবে
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Post এর author - users টেবিলের সাথে foreign key relationship
            // যদি user delete হয়, তাহলে তার সব post ও delete হবে (cascade)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Post এর মূল তথ্য
            $table->string('title');           // পোস্টের শিরোনাম
            $table->string('slug')->unique();  // URL-friendly identifier
            $table->text('body');              // পোস্টের মূল content

            // Post এর status - workflow management এর জন্য
            // draft: লেখা শুরু করেছে কিন্তু submit করেনি
            // pending: Author submit করেছে, Editor এর approval এর অপেক্ষায়
            // published: Editor approve করেছে, সবাই দেখতে পারবে
            // rejected: Editor reject করেছে
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('draft');

            // Rejection এর কারণ (optional) - Editor reject করলে কারণ লিখতে পারবে
            $table->text('rejection_reason')->nullable();

            // Timestamps
            $table->timestamp('published_at')->nullable(); // কখন publish হয়েছে
            $table->timestamps();                          // created_at, updated_at
        });
    }

    /**
     * Migration rollback করলে posts টেবিল মুছে যাবে
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
