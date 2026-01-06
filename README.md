# ICBlog - Responsive Blog Theme

Laravel + Tailwind CSS দিয়ে বানানো একটা mobile-first blog theme। এই project টা মূলত শেখার জন্য - কোন database বা backend logic নেই, pure frontend UI।

## কী কী আছে এখানে?

- Mobile-first responsive design
- Dark/Light mode toggle
- Blog listing page with grid layout
- Single post page with comments
- Reusable Blade components
- Bengali font support (Noto Sans Bengali)

## Project Structure

```
resources/
├── css/
│   └── app.css                 # Tailwind config + custom styles
├── views/
│   ├── layouts/
│   │   └── app.blade.php       # Base layout (header, footer include করে)
│   ├── components/
│   │   ├── header.blade.php    # Navigation + dark mode toggle
│   │   ├── footer.blade.php    # Footer with newsletter form
│   │   ├── sidebar.blade.php   # Search, categories, tags
│   │   └── blog-card.blade.php # Reusable blog card
│   └── blog/
│       ├── index.blade.php     # Blog listing / home page
│       └── show.blade.php      # Single post page
routes/
└── web.php                     # All routes
```

## Setup

### Step 1: Clone করুন

```bash
git clone <repo-url>
cd ic-learning-pro
```

### Step 2: Dependencies install করুন

```bash
composer install
npm install
```

### Step 3: Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Run করুন

Terminal 1:

```bash
npm run dev
```

Terminal 2:

```bash
php artisan serve
```

Browser এ যান: `http://localhost:8000`

## Dark Mode কিভাবে কাজ করে?

Header এ একটা toggle button আছে (চাঁদ/সূর্য icon)। Click করলে:

1. `<html>` element এ `dark` class add/remove হয়
2. localStorage এ preference save হয়
3. Page reload করলেও setting মনে থাকে

CSS এ Tailwind এর `dark:` variant use করা হয়েছে:

```html
<body class="bg-gray-50 dark:bg-gray-900">
```

## Component গুলো কিভাবে কাজ করে?

### Blog Card

```blade
<x-blog-card
    title="Post Title"
    excerpt="Short description..."
    category="Laravel"
    categoryColor="blue"
    author="Author Name"
    date="১৫ জানুয়ারি, ২০২৫"
    readTime="৫ মিনিট"
    url="/blog/post-slug"
/>
```

Available colors: `blue`, `green`, `purple`, `orange`, `pink`, `cyan`

### Layout extend করা

```blade
@extends('layouts.app')

@section('title', 'Page Title')

@section('content')
    <!-- Your content here -->
@endsection
```

## Responsive Breakpoints

Tailwind এর default breakpoints follow করা হয়েছে:

| Breakpoint | Width | Use Case |
|------------|-------|----------|
| Default | 0px+ | Mobile |
| `sm:` | 640px+ | Large phones |
| `md:` | 768px+ | Tablets |
| `lg:` | 1024px+ | Laptops |
| `xl:` | 1280px+ | Desktops |

Example:

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```

## Fonts

- **Noto Sans Bengali** - বাংলা text এর জন্য
- **Inter** - English text এর জন্য

Google Fonts থেকে load হয় `layouts/app.blade.php` এ।

## File by File Breakdown

### `layouts/app.blade.php`

Base layout। সব page এটা extend করে। এখানে আছে:

- HTML head (meta, fonts, vite assets)
- Dark mode initialization script
- Header ও Footer include
- Main content area (`@yield('content')`)
- Theme toggle JavaScript

### `components/header.blade.php`

- Logo
- Desktop navigation (md+ এ visible)
- Mobile hamburger menu
- Dark mode toggle button

### `components/footer.blade.php`

- 4 column grid (responsive)
- Social links
- Quick links
- Categories
- Newsletter form

### `components/sidebar.blade.php`

- Search form
- Categories with post count
- Popular posts
- Tags cloud
- Author card

### `components/blog-card.blade.php`

Props নেয়, reusable। Features:

- Thumbnail (image বা gradient placeholder)
- Category badge
- Title, excerpt
- Author avatar, date, read time
- Hover effects

### `blog/index.blade.php`

Home page। Sections:

- Hero (featured post)
- Blog grid (2 columns on tablet+)
- Sidebar
- Newsletter CTA
- Pagination (static)

### `blog/show.blade.php`

Single post page। Sections:

- Breadcrumb
- Post header (title, meta)
- Featured image
- Content (prose typography)
- Tags
- Share buttons
- Author bio
- Comment form
- Comments list
- Related posts

## Customization Tips

### Color scheme change করতে চাইলে

Tailwind এর color classes change করুন। Example: `blue-600` কে `indigo-600` করুন।

### নতুন category color add করতে

`blog-card.blade.php` এ `$gradients` এবং `$badgeColors` array তে নতুন color add করুন।

### Font change করতে

1. `layouts/app.blade.php` এ Google Fonts link update করুন
2. `resources/css/app.css` এ `--font-sans` update করুন

## Known Issues

- Forms গুলো static, submit করলে কিছু হবে না (backend নেই)
- Pagination decorative, click করলে same page থাকবে
- Search কাজ করবে না (no backend)

## Production Build

```bash
npm run build
```
