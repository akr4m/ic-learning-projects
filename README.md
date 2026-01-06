# Task Manager - Laravel শেখার প্রজেক্ট

আমি এই প্রজেক্টটা বানিয়েছি মূলত Laravel এর বেসিক কনসেপ্টগুলো হাতে-কলমে শেখানোর জন্য। অনেকে documentation পড়ে বুঝতে পারে না, তাই একটা real project এর মধ্য দিয়ে শেখালে বিষয়টা অনেক সহজ হয়ে যায়।

এই প্রজেক্টে আমরা একটা Task Management App বানাবো যেখানে:

- User registration ও login system থাকবে
- প্রতিটি user নিজের task গুলো manage করতে পারবে
- Task এর সাথে file attach করা যাবে

## কী কী শিখবে এই প্রজেক্ট থেকে?

- **MVC Pattern** - Model, View, Controller কিভাবে কাজ করে
- **Eloquent ORM** - Database এর সাথে কথা বলা
- **Blade Templates** - Frontend বানানো
- **Form Validation** - User input check করা
- **Authentication** - Login/Register system
- **File Upload** - Storage system ব্যবহার
- **Middleware** - Route protection
- **Relationships** - User আর Task এর মধ্যে সম্পর্ক

---

## প্রথমে সেটআপ করে নাও

### ১. প্রজেক্ট ক্লোন করো

```bash
git clone <repository-url>
cd ic-learning-pro
```

### ২. Dependencies ইনস্টল করো

```bash
composer install
```

### ৩. Environment সেটআপ

```bash
cp .env.example .env
php artisan key:generate
```

### ৪. Database তৈরি করো

এই প্রজেক্টে SQLite ব্যবহার করা হয়েছে (সবচেয়ে সহজ)। `.env` ফাইলে দেখো:

```
DB_CONNECTION=sqlite
```

এখন migration চালাও:

```bash
php artisan migrate
```

### ৫. Storage Link তৈরি করো

File upload কাজ করার জন্য এটা দরকার:

```bash
php artisan storage:link
```

### ৬. Server চালু করো

```bash
php artisan serve
```

এখন browser এ যাও: `http://localhost:8000`

---

## প্রজেক্ট Structure বুঝে নাও

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      → Login, Register, Logout handle করে
│   │   └── TaskController.php      → Task CRUD operations
│   └── Requests/
│       └── TaskRequest.php         → Form validation rules
├── Models/
│   ├── User.php                    → User model (tasks relationship আছে)
│   └── Task.php                    → Task model (user relationship আছে)

database/
└── migrations/
    └── 2025_01_06_000001_create_tasks_table.php

resources/views/
├── layouts/
│   └── app.blade.php               → Main layout (header, footer)
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── tasks/
│   ├── index.blade.php             → Task list
│   ├── create.blade.php            → নতুন task form
│   ├── edit.blade.php              → Task edit form
│   └── show.blade.php              → Task details
└── welcome.blade.php               → Home page

routes/
└── web.php                         → সব routes এখানে
```

---

## ধাপে ধাপে বুঝে নাও

### ধাপ ১: Migration - Database Table বানানো

প্রথমে database এ table লাগবে। `database/migrations/2025_01_06_000001_create_tasks_table.php` ফাইলটা দেখো:

```php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
    $table->string('attachment_path')->nullable();
    $table->string('attachment_name')->nullable();
    $table->date('due_date')->nullable();
    $table->timestamps();
});
```

**কী শিখলাম:**

- `foreignId('user_id')` - এটা users table এর সাথে relation তৈরি করে
- `onDelete('cascade')` - user মুছলে তার সব task ও মুছে যাবে
- `nullable()` - এই field খালি থাকতে পারে
- `timestamps()` - created_at, updated_at automatically যোগ হবে

---

### ধাপ ২: Model - Database এর সাথে কথা বলা

#### Task Model (`app/Models/Task.php`)

```php
class Task extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description',
        'status', 'attachment_path', 'attachment_name', 'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

**কী শিখলাম:**

- `$fillable` - এই fields গুলো mass assignment এ ব্যবহার করা যাবে
- `casts` - database থেকে আসা data কে PHP type এ convert করে
- `belongsTo` - প্রতিটা Task একজন User এর অধীনে

#### User Model এ Relationship

```php
public function tasks(): HasMany
{
    return $this->hasMany(Task::class);
}
```

এখন `$user->tasks` দিয়ে একজন user এর সব task পাওয়া যাবে।

---

### ধাপ ৩: Routes - URL গুলো Define করা

`routes/web.php` ফাইলে সব routes আছে:

```php
// Guest routes - শুধু logged out users
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes - শুধু logged in users
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks/{task}/download', [TaskController::class, 'download'])->name('tasks.download');
});
```

**কী শিখলাম:**

- `middleware('guest')` - logged in user এই routes এ যেতে পারবে না
- `middleware('auth')` - logged out user এই routes এ যেতে পারবে না
- `Route::resource()` - একসাথে 7টা CRUD route তৈরি করে

---

### ধাপ ৪: Controller - Logic Handle করা

#### AuthController - Login/Register

```php
public function register(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect()->route('tasks.index')->with('success', 'রেজিস্ট্রেশন সফল!');
}
```

**কী শিখলাম:**

- `$request->validate()` - form data validate করে
- `Hash::make()` - password hash করে (plain text save করা বিপজ্জনক!)
- `Auth::login()` - user কে login করিয়ে দেয়
- `with('success', ...)` - session এ message পাঠায়

#### TaskController - CRUD Operations

```php
public function store(TaskRequest $request): RedirectResponse
{
    $data = $request->validated();
    $data['user_id'] = Auth::id();

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $path = Storage::putFile('attachments', $file);
        $data['attachment_path'] = $path;
        $data['attachment_name'] = $file->getClientOriginalName();
    }

    Task::create($data);

    return redirect()->route('tasks.index')->with('success', 'টাস্ক তৈরি হয়েছে!');
}
```

**কী শিখলাম:**

- `TaskRequest` - আলাদা class এ validation rules রাখা (clean code!)
- `Auth::id()` - current logged in user এর ID
- `Storage::putFile()` - file save করে unique name দিয়ে

---

### ধাপ ৫: Form Request - Validation আলাদা রাখা

`app/Http/Requests/TaskRequest.php`:

```php
public function rules(): array
{
    return [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'status' => ['required', Rule::in(array_keys(Task::statuses()))],
        'due_date' => ['nullable', 'date'],
        'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', 'max:10240'],
    ];
}

public function messages(): array
{
    return [
        'title.required' => 'টাস্কের শিরোনাম দিতে হবে।',
        'attachment.max' => 'ফাইল সাইজ সর্বোচ্চ ১০ মেগাবাইট হতে পারে।',
    ];
}
```

**কী শিখলাম:**

- Controller ছোট রাখার জন্য validation আলাদা class এ রাখা ভালো
- Custom error messages বাংলায় দেওয়া যায়

---

### ধাপ ৬: Blade Templates - Frontend বানানো

#### Layout (`resources/views/layouts/app.blade.php`)

```blade
<!DOCTYPE html>
<html lang="bn">
<head>
    <title>@yield('title') - Task Manager</title>
    <!-- Noto Sans Bengali font for বাংলা -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        @auth
            <span>{{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">লগআউট</button>
            </form>
        @else
            <a href="{{ route('login') }}">লগইন</a>
        @endauth
    </header>

    <main>
        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
```

**কী শিখলাম:**

- `@yield('title')` - child view থেকে value inject হবে
- `@auth` / `@guest` - logged in কিনা check করে
- `@csrf` - form এ security token যোগ করে (CSRF protection)

#### Task Create Form

```blade
<form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="text" name="title" value="{{ old('title') }}">
    @error('title')
        <span class="error">{{ $message }}</span>
    @enderror

    <input type="file" name="attachment">

    <button type="submit">তৈরি করুন</button>
</form>
```

**কী শিখলাম:**

- `enctype="multipart/form-data"` - file upload এর জন্য must!
- `old('title')` - validation fail হলে আগের value ফিরে আসে
- `@error` - validation error থাকলে দেখায়

---

### ধাপ ৭: Authorization - নিজের Task নিজে দেখবে

```php
private function authorizeTask(Task $task): void
{
    if ($task->user_id !== Auth::id()) {
        abort(403, 'এই টাস্ক দেখার অনুমতি নেই।');
    }
}
```

প্রতিটা method এ এটা call করা হয়েছে যাতে এক user অন্য user এর task access করতে না পারে।

---

## Route List

| Method | URL | কাজ |
|--------|-----|-----|
| GET | `/` | Home page |
| GET | `/register` | Registration form |
| POST | `/register` | Register করে |
| GET | `/login` | Login form |
| POST | `/login` | Login করে |
| POST | `/logout` | Logout করে |
| GET | `/tasks` | Task list |
| GET | `/tasks/create` | নতুন task form |
| POST | `/tasks` | Task save করে |
| GET | `/tasks/{id}` | Task details |
| GET | `/tasks/{id}/edit` | Task edit form |
| PUT | `/tasks/{id}` | Task update করে |
| DELETE | `/tasks/{id}` | Task মুছে ফেলে |
| GET | `/tasks/{id}/download` | File download |

---

## কিছু Tips

1. **Code পড়ো মনোযোগ দিয়ে** - প্রতিটা file এ comment আছে বাংলায়, বুঝতে সুবিধা হবে

2. **একটু একটু করে বুঝো** - একসাথে সব বুঝতে যেও না, আজকে Model বুঝো, কালকে Controller

3. **নিজে modify করো** - নতুন field যোগ করো, দেখো কিভাবে কাজ করে

4. **Error আসলে ভয় পেও না** - Error message পড়ো, সেটাই solution বলে দেবে

---

## পরবর্তী ধাপ

এই প্রজেক্ট শেষ করার পর এগুলো শিখতে পারো:

- Laravel Policies (authorization আরো ভালোভাবে)
- API development
- Queue ও Jobs
- Testing

---
