<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ingredient Model
 *
 * এই Model ingredients টেবিলের সাথে interact করে।
 * প্রতিটি Ingredient একটি Recipe-এর অধীনে থাকে।
 *
 * @property int $id
 * @property int $recipe_id
 * @property string $name
 * @property string|null $quantity
 */
class Ingredient extends Model
{
    /**
     * Mass Assignment এর জন্য অনুমোদিত fields
     */
    protected $fillable = [
        'recipe_id',
        'name',
        'quantity',
    ];

    /**
     * Inverse Relationship
     *
     * প্রতিটি Ingredient একটি Recipe-এর সাথে সম্পর্কিত।
     * belongsTo() মেথড দিয়ে এই inverse relationship define করা হয়।
     *
     * ব্যবহার: $ingredient->recipe
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
