<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Task Form Request
 *
 * এই class টি Task সংক্রান্ত form validation handle করে।
 * Controller এ validation logic না রেখে এখানে রাখা হয়েছে।
 * এতে কোড পরিষ্কার থাকে এবং reusable হয়।
 *
 * Form Request ব্যবহারের সুবিধা:
 * 1. Controller ছোট থাকে
 * 2. Validation logic একজায়গায় থাকে
 * 3. Reusable - একই validation একাধিক জায়গায় ব্যবহার করা যায়
 * 4. Custom error messages সহজে যোগ করা যায়
 *
 * @see https://laravel.com/docs/validation#form-request-validation
 */
class TaskRequest extends FormRequest
{
    /**
     * এই request authorized কিনা নির্ধারণ করে।
     *
     * true return করলে request প্রসেস হবে।
     * false return করলে 403 Forbidden error দেখাবে।
     *
     * এখানে true রাখা হয়েছে কারণ authorization
     * TaskController এ handle করা হচ্ছে।
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules define করে।
     *
     * প্রতিটি field এর জন্য কোন কোন validation rule প্রয়োগ হবে তা এখানে বলা হয়।
     *
     * Rule এর ব্যাখ্যা:
     * - required: ফিল্ড অবশ্যই থাকতে হবে
     * - string: text data হতে হবে
     * - max:255: সর্বোচ্চ 255 characters
     * - nullable: খালি থাকতে পারে
     * - in:pending,in_progress,completed: এই তিনটির একটি হতে হবে
     * - date: valid date format হতে হবে
     * - file: file upload হতে হবে
     * - mimes:...: নির্দিষ্ট file type গুলো allowed
     * - max:10240: সর্বোচ্চ 10MB (10240 KB)
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => [
                'required',
                // Rule::in() দিয়ে allowed values check করা
                // Task::statuses() থেকে keys নেওয়া হচ্ছে
                Rule::in(array_keys(Task::statuses())),
            ],

            'due_date' => ['nullable', 'date'],

            'attachment' => [
                'nullable',
                'file',
                // Allowed file types
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip',
                // Maximum file size 10MB
                'max:10240',
            ],
        ];
    }

    /**
     * Custom validation error messages।
     *
     * Default Laravel messages এর বদলে নিজের messages ব্যবহার করা যায়।
     * বাংলায় messages দেওয়া হয়েছে স্টুডেন্টদের বোঝার সুবিধার্থে।
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'টাস্কের শিরোনাম দিতে হবে।',
            'title.max' => 'শিরোনাম সর্বোচ্চ ২৫৫ অক্ষর হতে পারে।',
            'status.required' => 'স্ট্যাটাস নির্বাচন করতে হবে।',
            'status.in' => 'সঠিক স্ট্যাটাস নির্বাচন করুন।',
            'due_date.date' => 'সঠিক তারিখ দিন।',
            'attachment.file' => 'সঠিক ফাইল আপলোড করুন।',
            'attachment.mimes' => 'শুধুমাত্র PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF, ZIP ফাইল আপলোড করা যাবে।',
            'attachment.max' => 'ফাইল সাইজ সর্বোচ্চ ১০ মেগাবাইট হতে পারে।',
        ];
    }

    /**
     * Attribute names - error message এ field name এর বদলে এই নাম দেখাবে।
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'শিরোনাম',
            'description' => 'বিবরণ',
            'status' => 'স্ট্যাটাস',
            'due_date' => 'শেষ তারিখ',
            'attachment' => 'সংযুক্তি',
        ];
    }
}
