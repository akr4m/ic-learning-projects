<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Recipe Model
 *
 * এই Model recipes টেবিলের সাথে interact করে।
 * Eloquent ORM ব্যবহার করে database operations সহজে করা যায়।
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $image
 */
class Recipe extends Model
{
    /**
     * Mass Assignment Protection
     *
     * $fillable array-তে যে fields আছে, শুধু সেগুলোতে
     * create() বা update() দিয়ে data insert করা যাবে।
     * এটি security feature - অবাঞ্ছিত data insert হওয়া থেকে রক্ষা করে।
     */
    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    /**
     * One-to-Many Relationship
     *
     * একটি Recipe-তে অনেক Ingredients থাকতে পারে।
     * hasMany() মেথড দিয়ে এই সম্পর্ক define করা হয়।
     *
     * ব্যবহার: $recipe->ingredients
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }
}
