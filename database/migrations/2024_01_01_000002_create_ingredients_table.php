<?php

/**
 * Ingredient Table Migration
 *
 * এই মাইগ্রেশন ingredients টেবিল তৈরি করে।
 * প্রতিটি ingredient একটি recipe-এর সাথে সম্পর্কিত (one-to-many relationship)।
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * মাইগ্রেশন চালানো হলে এই মেথড এক্সিকিউট হয়।
     * এখানে ingredients টেবিলের স্ট্রাকচার ডিফাইন করা হয়েছে।
     */
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Foreign Key - recipes টেবিলের সাথে সম্পর্ক
            // constrained() মেথড automatically recipes টেবিলের id-এর সাথে link করে
            // cascadeOnDelete() মানে recipe ডিলিট হলে ingredients-ও ডিলিট হবে
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();

            // Ingredient-এর নাম
            $table->string('name');

            // Ingredient-এর পরিমাণ (যেমন: ১ কাপ, ২ চামচ)
            $table->string('quantity')->nullable();

            $table->timestamps();
        });
    }

    /**
     * মাইগ্রেশন rollback করলে এই মেথড এক্সিকিউট হয়।
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
