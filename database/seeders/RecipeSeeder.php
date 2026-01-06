<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

/**
 * Recipe Seeder
 *
 * এই Seeder দিয়ে database-এ demo recipe data যোগ করা যায়।
 * শেখানোর সময় initial data থাকলে সুবিধা হয়।
 *
 * Run command: php artisan db:seed --class=RecipeSeeder
 */
class RecipeSeeder extends Seeder
{
    /**
     * Demo রেসিপি এবং উপকরণ যোগ করা
     */
    public function run(): void
    {
        // প্রথম রেসিপি - পায়েস
        $payesh = Recipe::create([
            'title' => 'চালের পায়েস',
            'description' => 'বাংলাদেশের ঐতিহ্যবাহী মিষ্টি খাবার। দুধ ও চাল দিয়ে তৈরি এই পায়েস যেকোনো উৎসবে অপরিহার্য। ধীরে ধীরে রান্না করলে সবচেয়ে ভালো স্বাদ হয়।',
        ]);

        $payesh->ingredients()->createMany([
            ['name' => 'চাল (গোবিন্দভোগ)', 'quantity' => '১ কাপ'],
            ['name' => 'দুধ', 'quantity' => '১ লিটার'],
            ['name' => 'চিনি', 'quantity' => '১ কাপ'],
            ['name' => 'এলাচ', 'quantity' => '৪-৫টি'],
            ['name' => 'কিশমিশ', 'quantity' => '২ টেবিল চামচ'],
            ['name' => 'বাদাম কুচি', 'quantity' => '২ টেবিল চামচ'],
        ]);

        // দ্বিতীয় রেসিপি - বিরিয়ানি
        $biryani = Recipe::create([
            'title' => 'কাচ্চি বিরিয়ানি',
            'description' => 'ঢাকাই কাচ্চি বিরিয়ানি বাংলাদেশের অন্যতম জনপ্রিয় খাবার। কাঁচা মাংস এবং চাল একসাথে দম দিয়ে রান্না করা হয়। আলু দিয়ে পরিবেশন করলে স্বাদ আরো বাড়ে।',
        ]);

        $biryani->ingredients()->createMany([
            ['name' => 'খাসির মাংস', 'quantity' => '১ কেজি'],
            ['name' => 'বাসমতি চাল', 'quantity' => '৫০০ গ্রাম'],
            ['name' => 'টক দই', 'quantity' => '১ কাপ'],
            ['name' => 'পেঁয়াজ বেরেস্তা', 'quantity' => '১ কাপ'],
            ['name' => 'গরম মসলা', 'quantity' => '২ টেবিল চামচ'],
            ['name' => 'জাফরান', 'quantity' => 'অল্প পরিমাণ'],
            ['name' => 'ঘি', 'quantity' => '৪ টেবিল চামচ'],
            ['name' => 'আলু', 'quantity' => '৪টি মাঝারি'],
        ]);

        // তৃতীয় রেসিপি - ভর্তা
        $vorta = Recipe::create([
            'title' => 'বেগুন ভর্তা',
            'description' => 'সহজ এবং সুস্বাদু বাংলা খাবার। পোড়া বেগুনের ধোঁয়াটে গন্ধ এই ভর্তার বিশেষত্ব। গরম ভাতের সাথে এক অসাধারণ combination।',
        ]);

        $vorta->ingredients()->createMany([
            ['name' => 'বেগুন (বড়)', 'quantity' => '২টি'],
            ['name' => 'পেঁয়াজ কুচি', 'quantity' => '১টি মাঝারি'],
            ['name' => 'কাঁচা মরিচ', 'quantity' => '৩-৪টি'],
            ['name' => 'সরিষার তেল', 'quantity' => '২ টেবিল চামচ'],
            ['name' => 'লবণ', 'quantity' => 'স্বাদমতো'],
            ['name' => 'ধনে পাতা', 'quantity' => 'সাজানোর জন্য'],
        ]);

        // চতুর্থ রেসিপি - পিঠা
        $pitha = Recipe::create([
            'title' => 'ভাপা পিঠা',
            'description' => 'শীতকালের জনপ্রিয় পিঠা। চালের গুঁড়া দিয়ে তৈরি এবং গুড়ের পুর দিয়ে ভাপে সেদ্ধ করা হয়। নতুন ধানের চাল দিয়ে বানালে সবচেয়ে সুস্বাদু হয়।',
        ]);

        $pitha->ingredients()->createMany([
            ['name' => 'চালের গুঁড়া', 'quantity' => '২ কাপ'],
            ['name' => 'খেজুরের গুড়', 'quantity' => '১ কাপ'],
            ['name' => 'নারকেল কোরা', 'quantity' => '১ কাপ'],
            ['name' => 'লবণ', 'quantity' => 'সামান্য'],
            ['name' => 'পানি', 'quantity' => 'প্রয়োজনমতো'],
        ]);

        // পঞ্চম রেসিপি - শিঙ্গাড়া
        $shingara = Recipe::create([
            'title' => 'আলুর শিঙ্গাড়া',
            'description' => 'বিকেলের নাস্তায় চায়ের সাথে শিঙ্গাড়ার জুড়ি নেই। খাস্তা আবরণ এবং মসলাদার আলুর পুর এই শিঙ্গাড়াকে অনন্য করে তোলে।',
        ]);

        $shingara->ingredients()->createMany([
            ['name' => 'ময়দা', 'quantity' => '২ কাপ'],
            ['name' => 'আলু সেদ্ধ', 'quantity' => '৩টি মাঝারি'],
            ['name' => 'পেঁয়াজ কুচি', 'quantity' => '১টি'],
            ['name' => 'কাঁচা মরিচ', 'quantity' => '২-৩টি'],
            ['name' => 'জিরা গুঁড়া', 'quantity' => '১ চা চামচ'],
            ['name' => 'ধনে গুঁড়া', 'quantity' => '১ চা চামচ'],
            ['name' => 'তেল (ভাজার জন্য)', 'quantity' => 'প্রয়োজনমতো'],
        ]);
    }
}
