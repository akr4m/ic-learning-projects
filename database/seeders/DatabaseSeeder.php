<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DatabaseSeeder - Demo Data তৈরি করে
 *
 * এই seeder চালালে blog application টেস্ট করার জন্য
 * প্রয়োজনীয় demo users এবং posts তৈরি হবে।
 *
 * Demo Users:
 * - author@example.com (password: password) - Author role
 * - editor@example.com (password: password) - Editor role
 *
 * Command: php artisan db:seed
 *
 * @see https://laravel.com/docs/seeding
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===================================
        // Demo Users তৈরি করি
        // ===================================

        // Author User - Blog post লেখার জন্য
        $author = User::factory()->author()->create([
            'name' => 'আবদুল্লাহ (Author)',
            'email' => 'author@example.com',
        ]);

        // Editor User - Post approve/reject করার জন্য
        $editor = User::factory()->editor()->create([
            'name' => 'রফিকুল (Editor)',
            'email' => 'editor@example.com',
        ]);

        // Admin User - সব manage করার জন্য
        $admin = User::factory()->admin()->create([
            'name' => 'করিম (Admin)',
            'email' => 'admin@example.com',
        ]);

        // Extra Author
        $author2 = User::factory()->author()->create([
            'name' => 'ফাতেমা (Author)',
            'email' => 'fatema@example.com',
        ]);

        // ===================================
        // Demo Posts তৈরি করি
        // ===================================

        // Author এর Draft posts
        Post::factory()
            ->draft()
            ->forUser($author)
            ->create([
                'title' => 'Laravel দিয়ে ব্লগ তৈরি - Draft',
                'slug' => 'laravel-diye-blog-toiri-draft',
                'body' => 'এটি একটি draft post যা এখনো submit করা হয়নি। Author এটি edit করতে পারবে এবং যখন প্রস্তুত হবে তখন submit করতে পারবে।

Laravel হলো একটি PHP web application framework যা expressive এবং elegant syntax এর জন্য পরিচিত। এটি MVC (Model-View-Controller) architectural pattern follow করে।',
            ]);

        // Author এর Pending post
        Post::factory()
            ->pending()
            ->forUser($author)
            ->create([
                'title' => 'Laravel Routing সম্পর্কে জানুন',
                'slug' => 'laravel-routing-somporke-janun',
                'body' => 'এই post টি অনুমোদনের অপেক্ষায় আছে।

Laravel এ routes define করার জন্য routes ফোল্ডারে web.php এবং api.php ফাইল থাকে। Web routes session state এবং CSRF protection সহ থাকে, আর API routes stateless।

Route::get(), Route::post(), Route::put(), Route::delete() ইত্যাদি HTTP methods ব্যবহার করা যায়।',
            ]);

        // Author এর Published post
        Post::factory()
            ->published()
            ->forUser($author)
            ->create([
                'title' => 'Laravel Eloquent ORM পরিচিতি',
                'slug' => 'laravel-eloquent-orm-porichiti',
                'body' => 'Eloquent হলো Laravel এর built-in ORM (Object-Relational Mapping) যা database এর সাথে কাজ করা সহজ করে দেয়।

প্রতিটি database table এর জন্য একটি Model class থাকে। Model এর মাধ্যমে আমরা database থেকে data retrieve, insert, update এবং delete করতে পারি।

উদাহরণ:
$user = User::find(1);
$posts = Post::where("status", "published")->get();
$newPost = Post::create(["title" => "নতুন পোস্ট", "body" => "..."]);

Eloquent relationships (hasOne, hasMany, belongsTo, belongsToMany) ব্যবহার করে tables এর মধ্যে সম্পর্ক define করা যায়।',
                'published_at' => now()->subDays(5),
            ]);

        // Author এর Rejected post
        Post::factory()
            ->rejected()
            ->forUser($author)
            ->create([
                'title' => 'এই পোস্টটি প্রত্যাখ্যাত হয়েছে',
                'slug' => 'ei-postoti-prottyakhyat-hoyeche',
                'body' => 'এটি একটি rejected post। Editor এটি কোনো কারণে reject করেছে। Author এই post edit করে আবার submit করতে পারবে।',
                'rejection_reason' => 'পোস্টে আরও বিস্তারিত তথ্য যোগ করুন এবং কোড উদাহরণ দিন।',
            ]);

        // Author2 এর posts
        Post::factory()
            ->published()
            ->forUser($author2)
            ->create([
                'title' => 'Laravel Blade Template Engine',
                'slug' => 'laravel-blade-template-engine',
                'body' => 'Blade হলো Laravel এর powerful template engine। এটি plain PHP code এবং template এর সুন্দর combination।

Blade Features:
- Template inheritance (@extends, @section, @yield)
- Components (@component, <x-component>)
- Control structures (@if, @foreach, @for)
- Raw PHP (@php)

Blade files .blade.php extension এ save করতে হয় এবং resources/views ফোল্ডারে রাখতে হয়।',
                'published_at' => now()->subDays(3),
            ]);

        Post::factory()
            ->pending()
            ->forUser($author2)
            ->create([
                'title' => 'Laravel Middleware কি এবং কিভাবে কাজ করে',
                'slug' => 'laravel-middleware-ki-ebong-kibhabe-kaj-kore',
                'body' => 'Middleware হলো HTTP request filtering mechanism। Request controller এ পৌঁছানোর আগে middleware দিয়ে যায়।

Common uses:
- Authentication check
- CSRF verification
- Logging
- Rate limiting

php artisan make:middleware CheckAge
এই command দিয়ে নতুন middleware তৈরি করা যায়।',
            ]);

        // Extra published posts for better demo
        Post::factory()
            ->published()
            ->forUser($author)
            ->create([
                'title' => 'Laravel Migration এবং Database Schema',
                'slug' => 'laravel-migration-ebong-database-schema',
                'body' => 'Migration হলো database version control system। এটা দিয়ে database schema define এবং modify করা যায়।

php artisan make:migration create_posts_table
php artisan migrate

Migration file এ up() এবং down() method থাকে। up() দিয়ে changes apply হয়, down() দিয়ে rollback করা যায়।

Schema Builder দিয়ে column types define করা যায়:
$table->string("name");
$table->text("body");
$table->integer("count");
$table->boolean("is_active");
$table->timestamps();',
                'published_at' => now()->subDays(7),
            ]);

        // Editor এরও একটি post (Editor নিজেও post লিখতে পারে)
        Post::factory()
            ->published()
            ->forUser($editor)
            ->create([
                'title' => 'Laravel Authorization - Policies এবং Gates',
                'slug' => 'laravel-authorization-policies-ebong-gates',
                'body' => 'Authorization হলো কোন user কি কাজ করতে পারবে সেটা নির্ধারণ করা।

Gates:
Simple closure-based authorization checks।

Policies:
Model-specific authorization logic organized করার জন্য।

php artisan make:policy PostPolicy --model=Post

Policy methods:
- viewAny, view, create, update, delete

Controller এ:
$this->authorize("update", $post);

Blade এ:
@can("update", $post)
    // Edit button
@endcan',
                'published_at' => now()->subDays(2),
            ]);

        // Output message
        $this->command->info('Demo data created successfully!');
        $this->command->info('');
        $this->command->info('Demo Users:');
        $this->command->info('  Admin:  admin@example.com (password: password)');
        $this->command->info('  Editor: editor@example.com (password: password)');
        $this->command->info('  Author: author@example.com (password: password)');
        $this->command->info('  Author: fatema@example.com (password: password)');
    }
}
