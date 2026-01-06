<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Authentication Controller
 *
 * এই Controller টি User authentication সংক্রান্ত সব কাজ handle করে।
 * Registration, Login, Logout - এই তিনটি প্রধান কাজ এখানে করা হয়।
 *
 * @see https://laravel.com/docs/authentication
 */
class AuthController extends Controller
{
    /**
     * Registration form দেখায়।
     *
     * GET /register route এ এই method call হয়।
     *
     * @return View
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * নতুন User registration process করে।
     *
     * POST /register route এ এই method call হয়।
     *
     * কাজের ধাপ:
     * 1. Form data validate করা
     * 2. Password hash করে database এ User তৈরি করা
     * 3. নতুন User কে auto-login করানো
     * 4. Dashboard এ redirect করা
     *
     * @param Request $request - Form থেকে আসা data
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // Validation - form data সঠিক কিনা পরীক্ষা করা
        // validated() method শুধু valid data return করে
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // নতুন User তৈরি করা
        // Hash::make() password কে secure hash এ convert করে
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // নতুন User কে auto-login করানো
        Auth::login($user);

        // Dashboard এ redirect সাথে success message
        return redirect()
            ->route('tasks.index')
            ->with('success', 'রেজিস্ট্রেশন সফল হয়েছে! স্বাগতম।');
    }

    /**
     * Login form দেখায়।
     *
     * GET /login route এ এই method call হয়।
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * User login process করে।
     *
     * POST /login route এ এই method call হয়।
     *
     * কাজের ধাপ:
     * 1. Credentials validate করা
     * 2. Auth::attempt() দিয়ে login চেষ্টা করা
     * 3. সফল হলে session regenerate করে redirect
     * 4. ব্যর্থ হলে error সহ আবার login page এ ফেরত
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Login credentials validate করা
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Login চেষ্টা করা
        // Auth::attempt() সফল হলে true return করে এবং user কে login করিয়ে দেয়
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Session ID regenerate করা - security এর জন্য গুরুত্বপূর্ণ
            // Session fixation attack প্রতিরোধ করে
            $request->session()->regenerate();

            return redirect()
                ->intended(route('tasks.index'))
                ->with('success', 'সফলভাবে লগইন হয়েছে!');
        }

        // Login ব্যর্থ হলে error সহ আবার login page এ
        // withErrors() দিয়ে error message পাঠানো হচ্ছে
        return back()->withErrors([
            'email' => 'প্রদত্ত তথ্য আমাদের রেকর্ডের সাথে মিলছে না।',
        ])->onlyInput('email');
    }

    /**
     * User logout করে।
     *
     * POST /logout route এ এই method call হয়।
     *
     * কাজের ধাপ:
     * 1. Auth::logout() দিয়ে user কে logout করানো
     * 2. Session invalidate করা
     * 3. CSRF token regenerate করা
     * 4. Home page এ redirect করা
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // User কে logout করানো
        Auth::logout();

        // Session পুরোপুরি মুছে ফেলা
        $request->session()->invalidate();

        // নতুন CSRF token তৈরি করা - security এর জন্য
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'সফলভাবে লগআউট হয়েছে!');
    }
}
