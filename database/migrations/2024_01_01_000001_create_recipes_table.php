<?php

/**
 * Recipe Table Migration
 *
 * এই মাইগ্রেশন recipes টেবিল তৈরি করে।
 * প্রতিটি recipe-এ title, description এবং image থাকবে।
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * মাইগ্রেশন চালানো হলে এই মেথড এক্সিকিউট হয়।
     * এখানে recipes টেবিলের স্ট্রাকচার ডিফাইন করা হয়েছে।
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            // প্রতিটি টেবিলে একটি unique id থাকে (Primary Key)
            $table->id();

            // Recipe-এর শিরোনাম - string টাইপ (varchar)
            $table->string('title');

            // Recipe-এর বিস্তারিত বর্ণনা - text টাইপ (বড় টেক্সট)
            $table->text('description');

            // Recipe-এর ছবির পাথ - nullable কারণ ছবি optional
            $table->string('image')->nullable();

            // Laravel automatically created_at এবং updated_at কলাম যোগ করে
            $table->timestamps();
        });
    }

    /**
     * মাইগ্রেশন rollback করলে এই মেথড এক্সিকিউট হয়।
     * টেবিল ডিলিট করে দেয়।
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
