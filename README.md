# Laravel শেখার প্রজেক্ট: Role-Based Blog

এই প্রজেক্টটা বানিয়েছি আমার স্টুডেন্টদের Laravel শেখানোর জন্য। চেষ্টা করেছি সিম্পল রাখতে যাতে সবাই বুঝতে পারে। এটা production-ready কোড না, শুধুই শেখার জন্য।

## প্রজেক্টে কি আছে?

একটা ব্লগ অ্যাপ। তিন টাইপের ইউজার:

**Author** - পোস্ট লিখতে পারে। সরাসরি পাবলিশ হয় না, Editor-এর অ্যাপ্রুভাল লাগে।

**Editor** - পোস্ট রিভিউ করে অ্যাপ্রুভ বা রিজেক্ট করে।

**Admin** - সব পারে। ইউজারদের role-ও চেঞ্জ করতে পারে।

## কি কি শিখবে?

- MVC pattern
- Routing
- Controller
- Eloquent Model ও Relationships
- Migration
- Policy দিয়ে Authorization
- Middleware
- Sanctum API Auth
- Blade Template
- TailwindCSS

---

## সেটআপ

### ১. Clone

```bash
git clone <repository-url>
cd project-folder
```

### ২. Composer

```bash
composer install
```

### ৩. Environment

```bash
cp .env.example .env
php artisan key:generate
```

### ৪. Database

SQLite ব্যবহার করেছি।

```bash
touch database/database.sqlite
```

Windows:

```bash
type nul > database/database.sqlite
```

### ৫. Migration

```bash
php artisan migrate --seed
```

### ৬. Frontend

```bash
npm install && npm run build
```

### ৭. Server

```bash
php artisan serve
```

Browser: <http://localhost:8000>

---

## Demo Users

Password সবার: `password`

| Email | Role |
|-------|------|
| <admin@example.com> | Admin |
| <editor@example.com> | Editor |
| <author@example.com> | Author |
| <fatema@example.com> | Author |

---

## Post Lifecycle

```
Draft → Pending → Published
              ↘ Rejected
```

---

## ফোল্ডার স্ট্রাকচার

```
project-folder/
├── app/
│   ├── Http/Controllers/
│   │   ├── PostController.php
│   │   ├── EditorController.php
│   │   └── Api/PostController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Post.php
│   └── Policies/
│       └── PostPolicy.php
├── database/migrations/
├── resources/views/
└── routes/
    ├── web.php
    └── api.php
```

---

## কোড পড়ার টিপস

১. **Model আগে** - `Post.php`, `User.php` দেখো
২. **Migration** - টেবিল ডিজাইন
৩. **Policy** - Authorization rules
৪. **Routes** - order matters! `/posts/create` আগে, `/posts/{post}` পরে
৫. **Controller** - CRUD operations

---

## API

### Login

```
POST /api/login
{"email": "author@example.com", "password": "password"}
```

### Endpoints

| Method | URL | কাজ |
|--------|-----|-----|
| GET | /api/posts | Posts |
| POST | /api/posts | Create |
| POST | /api/posts/{id}/submit | Submit |
| POST | /api/posts/{id}/approve | Approve |

---

## Commands

```bash
php artisan serve
php artisan migrate:fresh --seed
php artisan route:list
```

---
