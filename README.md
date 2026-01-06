# Laravel শিক্ষামূলক প্রজেক্ট: Role-Based Blog

এই প্রজেক্টটা আমি আমার স্টুডেন্টদের Laravel শেখানোর জন্য বানিয়েছি। এখানে চেষ্টা করেছি Laravel এর core concepts গুলো একটা real-world example এর মাধ্যমে দেখাতে। কোড যতটা সম্ভব simple রাখার চেষ্টা করেছি যাতে নতুনরা সহজে বুঝতে পারে।

## কি আছে এই প্রজেক্টে?

একটা simple blog application যেখানে দুই ধরনের user আছে:

**Author** - এরা blog post লিখতে পারে। তবে সরাসরি publish করতে পারে না। Post লেখা শেষ হলে Editor এর কাছে approval এর জন্য পাঠাতে হয়।

**Editor** - এরা Author দের লেখা post গুলো review করে approve অথবা reject করতে পারে। Approve হলে post publish হয়ে যায়, সবাই দেখতে পায়।

এই workflow টা অনেক real company তে follow করা হয়। তাই শেখার জন্য ভালো example।

## যা যা শিখতে পারবে

- MVC pattern কিভাবে কাজ করে
- Routing (web routes + API routes)
- Controller লেখা (Resource Controller pattern)
- Eloquent Model আর Relationships
- Database Migration
- Policy দিয়ে Authorization
- Middleware
- Laravel Sanctum দিয়ে API Authentication
- Blade Template আর Components
- TailwindCSS integration

## Installation

### Step 1: Clone করো

```bash
git clone <repository-url>
cd project-folder
```

### Step 2: PHP Dependencies install করো

```bash
composer install
```

### Step 3: Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Database setup

প্রজেক্টে SQLite ব্যবহার করা হয়েছে, তাই আলাদা করে MySQL/PostgreSQL setup করার দরকার নেই।

```bash
touch database/database.sqlite
```

অথবা Windows এ:

```bash
type nul > database/database.sqlite
```

### Step 5: Migration আর Demo Data

```bash
php artisan migrate --seed
```

এটা চালালে database তৈরি হয়ে যাবে আর কিছু demo user ও post তৈরি হবে।

### Step 6: Frontend Assets

```bash
pnpm install
pnpm run build
```

যদি pnpm না থাকে, npm দিয়েও করতে পারো:

```bash
npm install
npm run build
```

### Step 7: Server চালাও

```bash
php artisan serve
```

Browser এ যাও: <http://localhost:8000>

## Demo Login

তিনটা demo user তৈরি করা আছে। সবার password: `password`

| Email | Role | কি করতে পারবে |
|-------|------|---------------|
| <author@example.com> | Author | Post লেখা, Edit করা, Submit করা |
| <editor@example.com> | Editor | Post Approve/Reject করা |
| <fatema@example.com> | Author | Post লেখা, Edit করা, Submit করা |

## Project Structure বুঝি

```
app/
├── Http/Controllers/
│   ├── PostController.php      → Post CRUD (Create, Read, Update, Delete)
│   ├── DashboardController.php → User Dashboard
│   ├── EditorController.php    → Approval/Rejection handle করে
│   └── Api/PostController.php  → API endpoints
│
├── Models/
│   ├── User.php                → User model, role management
│   └── Post.php                → Post model, status management
│
└── Policies/
    └── PostPolicy.php          → কে কি করতে পারবে সেটার rules

database/
├── migrations/                 → Database table structure
├── factories/                  → Test data generate করার জন্য
└── seeders/                    → Demo data

resources/views/
├── layouts/                    → Main layout files
├── posts/                      → Post related views
├── editor/                     → Editor views
└── dashboard.blade.php         → Dashboard

routes/
├── web.php                     → Web routes
└── api.php                     → API routes
```

## Post এর Lifecycle

একটা post এর জীবনচক্র এরকম:

```
Draft → Pending → Published
                ↘ Rejected → (Edit করে আবার) → Pending
```

1. **Draft**: Author post লিখছে, এখনো submit করেনি
2. **Pending**: Author submit করেছে, Editor এর approval এর অপেক্ষায়
3. **Published**: Editor approve করেছে, সবাই দেখতে পাচ্ছে
4. **Rejected**: Editor reject করেছে। Author edit করে আবার submit করতে পারবে।

## Code পড়ার Tips

### ১. Model দেখো আগে

`app/Models/Post.php` আর `app/Models/User.php` দিয়ে শুরু করো। এখানে দেখবে:

- Relationships কিভাবে define করা হয়
- Helper methods কিভাবে লেখা হয় (`isAuthor()`, `isPublished()` ইত্যাদি)
- Query Scopes কি জিনিস (`scopePublished`, `scopePending`)

### ২. Migration দেখো

`database/migrations/` folder এ দেখো কিভাবে database table design করা হয়েছে।

### ৩. Policy বুঝো

`app/Policies/PostPolicy.php` - এটা important। কোন user কোন action নিতে পারবে সেটা এখানে define করা। Laravel এর authorization system বুঝতে এই file ভালো করে পড়ো।

### ৪. Routes দেখো

`routes/web.php` দেখো route কিভাবে organize করা হয়েছে। একটা জিনিস মনে রাখবে - **route order matters!** Static routes (`/posts/create`) অবশ্যই dynamic routes (`/posts/{post}`) এর আগে থাকতে হবে।

### ৫. Controller দেখো

`app/Http/Controllers/PostController.php` - Resource Controller এর example। CRUD operations কিভাবে handle করা হয় দেখো।

## API ব্যবহার

Sanctum দিয়ে API authentication করা হয়েছে। Postman বা অন্য কোনো API client দিয়ে test করতে পারো।

### Login করে Token নাও

```
POST /api/login
Body: {
    "email": "author@example.com",
    "password": "password"
}
```

Response এ একটা token পাবে।

### Token দিয়ে Request করো

```
GET /api/posts
Headers: {
    "Authorization": "Bearer <token>"
}
```

### API Endpoints

| Method | URL | কি করে |
|--------|-----|--------|
| POST | /api/login | Login, token দেয় |
| GET | /api/posts | Published posts list |
| POST | /api/posts | নতুন post create |
| GET | /api/posts/{id} | Single post দেখা |
| PUT | /api/posts/{id} | Post update |
| POST | /api/posts/{id}/submit | Approval এ পাঠানো |
| POST | /api/posts/{id}/approve | Approve করা (Editor) |
| POST | /api/posts/{id}/reject | Reject করা (Editor) |

## কিছু গুরুত্বপূর্ণ বিষয়

### Route Model Binding

Controller method এ `Post $post` লিখলে Laravel automatically URL থেকে post ID নিয়ে database থেকে Post model find করে দেয়। Manual ভাবে `Post::find($id)` করার দরকার নেই।

```php
// PostController.php
public function show(Post $post)  // Laravel নিজেই Post find করে দিবে
{
    return view('posts.show', compact('post'));
}
```

### Gate/Policy Authorization

Controller এ `Gate::authorize()` call করলে সেটা Policy check করে। Permission না থাকলে automatically 403 error দেয়।

```php
Gate::authorize('update', $post);  // PostPolicy এর update() method check করবে
```

### Blade এ Authorization Check

```blade
@can('update', $post)
    <a href="{{ route('posts.edit', $post) }}">Edit</a>
@endcan
```

## Development Commands

```bash
# Server চালানো
php artisan serve

# Migration fresh করা (সব মুছে আবার তৈরি)
php artisan migrate:fresh --seed

# Route list দেখা
php artisan route:list

# Cache clear করা
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

## শেষ কথা

এই প্রজেক্টটা production ready না। এটা শুধুমাত্র শেখার জন্য। Real project এ আরো অনেক কিছু add করতে হবে - proper validation, error handling, testing, ইত্যাদি।
