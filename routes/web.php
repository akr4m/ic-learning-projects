<?php

use Illuminate\Support\Facades\Route;

// Home & Blog List
Route::get('/', fn() => view('blog.index'))->name('home');
Route::get('/blog', fn() => view('blog.index'))->name('blog.index');

// Single Blog Post
Route::get('/blog/{slug}', fn($slug) => view('blog.show'))->name('blog.show');

// Static Pages (placeholders)
Route::get('/categories', fn() => view('blog.index'))->name('categories');
Route::get('/about', fn() => view('blog.index'))->name('about');
Route::get('/contact', fn() => view('blog.index'))->name('contact');
Route::get('/privacy', fn() => view('blog.index'))->name('privacy');
