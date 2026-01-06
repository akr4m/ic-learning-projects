<?php

/**
 * Web Routes
 *
 * এই ফাইলে web application-এর সকল routes define করা হয়।
 * Route হলো URL এবং Controller action-এর মধ্যে সংযোগ।
 */

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home Page Route
|--------------------------------------------------------------------------
| "/" URL-এ গেলে recipes index page-এ redirect করবে
*/
Route::get('/', function () {
    return redirect()->route('recipes.index');
});

/*
|--------------------------------------------------------------------------
| Recipe Routes (Resource Controller)
|--------------------------------------------------------------------------
| Route::resource() একটি shortcut যা নিচের ৭টি route তৈরি করে:
|
| GET    /recipes          -> index()   - সকল রেসিপি দেখানো
| GET    /recipes/create   -> create()  - নতুন রেসিপি ফর্ম
| POST   /recipes          -> store()   - নতুন রেসিপি সংরক্ষণ
| GET    /recipes/{id}     -> show()    - একটি রেসিপি দেখানো
| GET    /recipes/{id}/edit-> edit()    - রেসিপি সম্পাদনা ফর্ম
| PUT    /recipes/{id}     -> update()  - রেসিপি আপডেট
| DELETE /recipes/{id}     -> destroy() - রেসিপি মুছে ফেলা
*/
Route::resource('recipes', RecipeController::class);
