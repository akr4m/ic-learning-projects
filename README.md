# রেসিপি শেয়ারিং প্ল্যাটফর্ম

স্টুডেন্টদের Laravel শেখানোর জন্য এই প্রজেক্টটা বানিয়েছি। একদম বেসিক - রেসিপি add করা যায়, edit করা যায়, delete করা যায়। সাথে ছবি upload আর search-ও আছে।

## কি কি শিখবে?

- MVC কিভাবে কাজ করে
- Routing
- Controller
- Eloquent ORM
- Model Relationships (one-to-many)
- Query Builder দিয়ে search/filter
- Form handling ও Validation
- File Upload (ছবি)
- Blade Template
- TailwindCSS

---

## সেটআপ

### ১. Clone

```bash
git clone <repository-url>
cd project-folder
```

### ২. Dependencies

```bash
composer install
```

### ৩. Environment

```bash
cp .env.example .env
php artisan key:generate
```

### ৪. Database

SQLite ব্যবহার করেছি, আলাদা করে MySQL লাগবে না।

```bash
touch database/database.sqlite
```

Windows এ:

```bash
type nul > database/database.sqlite
```

### ৫. Migration

```bash
php artisan migrate --seed
```

### ৬. Storage Link

ছবি দেখানোর জন্য এটা দরকার:

```bash
php artisan storage:link
```

### ৭. Frontend

```bash
npm install
npm run build
```

### ৮. Server

```bash
php artisan serve
```

Browser: <http://localhost:8000>

---

## ফিচার

- রেসিপি তৈরি, দেখা, এডিট, ডিলিট (CRUD)
- প্রতিটা রেসিপিতে একাধিক উপকরণ (ingredients) যোগ করা যায়
- রেসিপির ছবি আপলোড
- রেসিপি সার্চ (নাম বা বর্ণনা দিয়ে)
- উপকরণ দিয়ে ফিল্টার

---

## ফোল্ডার স্ট্রাকচার

```
project-folder/
├── app/
│   ├── Http/Controllers/
│   │   └── RecipeController.php   # CRUD + Search
│   └── Models/
│       ├── Recipe.php             # রেসিপি মডেল
│       └── Ingredient.php         # উপকরণ মডেল
│
├── database/migrations/
│   ├── create_recipes_table.php
│   └── create_ingredients_table.php
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php          # মেইন লেআউট
│   └── recipes/
│       ├── index.blade.php        # রেসিপি লিস্ট
│       ├── create.blade.php       # নতুন রেসিপি ফর্ম
│       ├── edit.blade.php         # এডিট ফর্ম
│       └── show.blade.php         # রেসিপি ডিটেইলস
│
└── routes/
    └── web.php                    # সব routes
```

---

## Database Structure

### recipes টেবিল

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| title | string | রেসিপির নাম |
| description | text | বিস্তারিত |
| image | string | ছবির path |
| timestamps | | created_at, updated_at |

### ingredients টেবিল

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| recipe_id | foreign | কোন রেসিপির |
| name | string | উপকরণের নাম |
| quantity | string | পরিমাণ |
| timestamps | | created_at, updated_at |

---

## Relationship

একটা Recipe তে অনেক Ingredient থাকতে পারে (One-to-Many):

```php
// Recipe.php
public function ingredients()
{
    return $this->hasMany(Ingredient::class);
}

// Ingredient.php
public function recipe()
{
    return $this->belongsTo(Recipe::class);
}
```

---

## Routes

`Route::resource()` দিয়ে একবারে ৭টা route বানানো হয়েছে:

| Method | URL | Controller Method | কাজ |
|--------|-----|-------------------|-----|
| GET | /recipes | index() | সব রেসিপি দেখানো |
| GET | /recipes/create | create() | নতুন রেসিপি ফর্ম |
| POST | /recipes | store() | রেসিপি সেভ |
| GET | /recipes/{id} | show() | একটা রেসিপি দেখানো |
| GET | /recipes/{id}/edit | edit() | এডিট ফর্ম |
| PUT | /recipes/{id} | update() | আপডেট করা |
| DELETE | /recipes/{id} | destroy() | ডিলিট করা |

---

## কোড বুঝতে যা যা দেখবে

### ১. Model দেখো আগে

`app/Models/Recipe.php` - দেখো কিভাবে `$fillable` দিয়ে mass assignment protection করা হয়েছে, আর `hasMany()` দিয়ে relationship।

### ২. Migration

`database/migrations/` - টেবিল কিভাবে ডিজাইন করা হয়েছে দেখো। `foreignId()` দিয়ে কিভাবে relation বানানো হয়।

### ৩. Controller

`RecipeController.php` - এখানে সব আছে:

- Validation কিভাবে করে
- File upload কিভাবে handle করে
- Query Builder দিয়ে search
- `whereHas()` দিয়ে related table এ search

### ৪. Views

`resources/views/recipes/` - Blade template গুলো দেখো:

- `@extends` দিয়ে layout inherit
- `@section` দিয়ে content
- `@foreach` দিয়ে loop
- `@if` দিয়ে condition
- `@error` দিয়ে validation error দেখানো

---

## কিছু জিনিস মনে রাখবে

### Route Model Binding

Controller এ `Recipe $recipe` লিখলে Laravel নিজে থেকে database থেকে খুঁজে দেয়:

```php
public function show(Recipe $recipe)
{
    // $recipe তে automatically data চলে আসে
    return view('recipes.show', compact('recipe'));
}
```

### Eager Loading

N+1 query problem এড়াতে `load()` বা `with()` ব্যবহার করো:

```php
$recipe->load('ingredients');
```

### File Upload

```php
$path = $request->file('image')->store('recipes', 'public');
```

এতে `storage/app/public/recipes/` folder এ ছবি save হয়।

---

## Commands

```bash
# সার্ভার চালানো
php artisan serve

# ডাটাবেস রিফ্রেশ
php artisan migrate:fresh --seed

# Route লিস্ট
php artisan route:list
```

---

## Demo Data

`php artisan db:seed` দিলে কিছু বাংলা রেসিপি automatically ঢুকে যাবে - পায়েস, বিরিয়ানি, ভর্তা, পিঠা, শিঙ্গাড়া।
