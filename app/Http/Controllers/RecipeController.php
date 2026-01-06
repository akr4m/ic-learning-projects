<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Recipe Controller
 *
 * এই Controller Recipe-এর সকল CRUD operation handle করে।
 * MVC Architecture-এ Controller হলো Model এবং View-এর মধ্যবর্তী স্তর।
 *
 * CRUD = Create, Read, Update, Delete
 */
class RecipeController extends Controller
{
    /**
     * সকল Recipe-এর তালিকা দেখানো (READ - Index)
     *
     * Query Builder ব্যবহার করে search এবং filter করা যায়।
     * latest() মেথড সাম্প্রতিক recipe গুলো আগে দেখায়।
     */
    public function index(Request $request): View
    {
        // Query Builder শুরু করা
        $query = Recipe::query();

        // Search functionality - title বা description-এ খোঁজা
        if ($request->filled('search')) {
            $searchTerm = $request->search;

            // where() এবং orWhere() দিয়ে multiple conditions
            // LIKE operator দিয়ে partial match করা যায়
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Ingredient দিয়ে filter - whereHas() দিয়ে related table-এ search
        if ($request->filled('ingredient')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->ingredient}%");
            });
        }

        // সাম্প্রতিক recipe আগে এবং pagination
        $recipes = $query->latest()->paginate(9);

        return view('recipes.index', compact('recipes'));
    }

    /**
     * নতুন Recipe তৈরির ফর্ম দেখানো (CREATE - Form)
     */
    public function create(): View
    {
        return view('recipes.create');
    }

    /**
     * নতুন Recipe সংরক্ষণ করা (CREATE - Store)
     *
     * Form থেকে আসা data validate করে database-এ save করা হয়।
     * Image upload এবং ingredients-ও handle করা হয়।
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation - ডেটার সঠিকতা যাচাই
        // required = আবশ্যক, max = সর্বোচ্চ দৈর্ঘ্য, image = শুধু ছবি
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'nullable|array',
            'ingredients.*.name' => 'required_with:ingredients|string|max:255',
            'ingredients.*.quantity' => 'nullable|string|max:100',
        ]);

        // Image Upload - Storage facade ব্যবহার
        $imagePath = null;
        if ($request->hasFile('image')) {
            // public/recipes folder-এ ছবি save হবে
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        // Recipe তৈরি করা - Eloquent create() মেথড
        $recipe = Recipe::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        // Ingredients যোগ করা - Relationship ব্যবহার করে
        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                if (!empty($ingredient['name'])) {
                    $recipe->ingredients()->create([
                        'name' => $ingredient['name'],
                        'quantity' => $ingredient['quantity'] ?? null,
                    ]);
                }
            }
        }

        // Redirect with success message
        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'রেসিপি সফলভাবে তৈরি হয়েছে!');
    }

    /**
     * একটি Recipe-এর বিস্তারিত দেখানো (READ - Show)
     *
     * Route Model Binding - Laravel automatically Recipe model খুঁজে দেয়
     */
    public function show(Recipe $recipe): View
    {
        // with() দিয়ে Eager Loading - N+1 query সমস্যা এড়ানো
        $recipe->load('ingredients');

        return view('recipes.show', compact('recipe'));
    }

    /**
     * Recipe সম্পাদনার ফর্ম দেখানো (UPDATE - Form)
     */
    public function edit(Recipe $recipe): View
    {
        $recipe->load('ingredients');

        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Recipe আপডেট করা (UPDATE - Update)
     */
    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ingredients' => 'nullable|array',
            'ingredients.*.name' => 'required_with:ingredients|string|max:255',
            'ingredients.*.quantity' => 'nullable|string|max:100',
        ]);

        // নতুন Image upload হলে পুরানোটা delete করা
        if ($request->hasFile('image')) {
            // পুরানো image delete
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            $validated['image'] = $request->file('image')->store('recipes', 'public');
        }

        // Recipe update করা
        $recipe->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? $recipe->image,
        ]);

        // পুরানো ingredients delete করে নতুন গুলো যোগ করা
        $recipe->ingredients()->delete();

        if (!empty($validated['ingredients'])) {
            foreach ($validated['ingredients'] as $ingredient) {
                if (!empty($ingredient['name'])) {
                    $recipe->ingredients()->create([
                        'name' => $ingredient['name'],
                        'quantity' => $ingredient['quantity'] ?? null,
                    ]);
                }
            }
        }

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'রেসিপি সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Recipe মুছে ফেলা (DELETE)
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        // Image delete করা
        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        // Recipe delete করলে ingredients-ও delete হবে (cascade)
        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with('success', 'রেসিপি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
