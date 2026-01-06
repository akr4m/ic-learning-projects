<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Task Controller
 *
 * এই Controller টি Task সংক্রান্ত সব CRUD operation handle করে।
 * CRUD = Create, Read, Update, Delete
 *
 * প্রতিটি method এ শুধুমাত্র logged-in user এর নিজের task গুলো access করা হয়।
 * এটি Auth::user()->tasks() relationship দিয়ে নিশ্চিত করা হয়েছে।
 *
 * @see https://laravel.com/docs/controllers
 */
class TaskController extends Controller
{
    /**
     * Task এর তালিকা দেখায় (Index/List)।
     *
     * GET /tasks route এ এই method call হয়।
     *
     * শুধুমাত্র logged-in user এর task গুলো দেখানো হয়।
     * latest() দিয়ে নতুন task আগে দেখানো হচ্ছে।
     * paginate(10) দিয়ে প্রতি পেজে 10টি task দেখানো হচ্ছে।
     *
     * @return View
     */
    public function index(): View
    {
        // Auth::user() বর্তমান logged-in user return করে
        // tasks() হলো User model এ define করা relationship
        $tasks = Auth::user()
            ->tasks()
            ->latest() // ORDER BY created_at DESC
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * নতুন Task তৈরির form দেখায়।
     *
     * GET /tasks/create route এ এই method call হয়।
     *
     * @return View
     */
    public function create(): View
    {
        // Task::statuses() দিয়ে available status গুলো পাঠানো হচ্ছে
        $statuses = Task::statuses();

        return view('tasks.create', compact('statuses'));
    }

    /**
     * নতুন Task database এ সংরক্ষণ করে।
     *
     * POST /tasks route এ এই method call হয়।
     *
     * TaskRequest class ব্যবহার করে validation করা হচ্ছে।
     * File upload হলে সেটা Storage এ সংরক্ষণ করা হচ্ছে।
     *
     * @param TaskRequest $request - Validated form data
     * @return RedirectResponse
     */
    public function store(TaskRequest $request): RedirectResponse
    {
        // Validated data নেওয়া
        $data = $request->validated();

        // বর্তমান user এর ID যোগ করা
        $data['user_id'] = Auth::id();

        // File upload handle করা
        if ($request->hasFile('attachment')) {
            $data = $this->handleFileUpload($request, $data);
        }

        // Task তৈরি করা
        Task::create($data);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'টাস্ক সফলভাবে তৈরি হয়েছে!');
    }

    /**
     * একটি নির্দিষ্ট Task এর বিস্তারিত দেখায়।
     *
     * GET /tasks/{task} route এ এই method call হয়।
     *
     * Route Model Binding ব্যবহার করা হয়েছে।
     * Laravel automatically URL থেকে {task} ID নিয়ে Task model load করে।
     *
     * @param Task $task - Route Model Binding দ্বারা inject করা
     * @return View
     */
    public function show(Task $task): View
    {
        // Authorization check - এই task কি এই user এর?
        $this->authorizeTask($task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Task edit করার form দেখায়।
     *
     * GET /tasks/{task}/edit route এ এই method call হয়।
     *
     * @param Task $task
     * @return View
     */
    public function edit(Task $task): View
    {
        $this->authorizeTask($task);

        $statuses = Task::statuses();

        return view('tasks.edit', compact('task', 'statuses'));
    }

    /**
     * Task update করে database এ সংরক্ষণ করে।
     *
     * PUT/PATCH /tasks/{task} route এ এই method call হয়।
     *
     * @param TaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        $data = $request->validated();

        // নতুন file upload হলে
        if ($request->hasFile('attachment')) {
            // পুরাতন file মুছে ফেলা
            $this->deleteOldAttachment($task);
            // নতুন file upload করা
            $data = $this->handleFileUpload($request, $data);
        }

        // Task update করা
        $task->update($data);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'টাস্ক সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Task মুছে ফেলে।
     *
     * DELETE /tasks/{task} route এ এই method call হয়।
     *
     * Task এর সাথে attached file ও মুছে ফেলা হয়।
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        // Attachment থাকলে মুছে ফেলা
        $this->deleteOldAttachment($task);

        // Task মুছে ফেলা
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'টাস্ক সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * Task এর attachment download করে।
     *
     * GET /tasks/{task}/download route এ এই method call হয়।
     *
     * @param Task $task
     * @return StreamedResponse
     */
    public function download(Task $task): StreamedResponse
    {
        $this->authorizeTask($task);

        // Attachment আছে কিনা পরীক্ষা
        if (!$task->hasAttachment()) {
            abort(404, 'কোনো ফাইল সংযুক্ত নেই।');
        }

        // File download করা - original name সহ
        return Storage::download(
            $task->attachment_path,
            $task->attachment_name
        );
    }

    /**
     * Task এর attachment মুছে ফেলে (update ছাড়াই)।
     *
     * DELETE /tasks/{task}/attachment route এ এই method call হয়।
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function deleteAttachment(Task $task): RedirectResponse
    {
        $this->authorizeTask($task);

        // Attachment মুছে ফেলা
        $this->deleteOldAttachment($task);

        // Database update করা
        $task->update([
            'attachment_path' => null,
            'attachment_name' => null,
        ]);

        return redirect()
            ->route('tasks.edit', $task)
            ->with('success', 'ফাইল সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * Task এর owner কি বর্তমান user কিনা পরীক্ষা করে।
     *
     * Authorization check - গুরুত্বপূর্ণ security measure।
     * অন্য user এর task access করার চেষ্টা করলে 403 error দেয়।
     *
     * @param Task $task
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'এই টাস্ক দেখার অনুমতি নেই।');
        }
    }

    /**
     * File upload handle করে এবং data array তে path যোগ করে।
     *
     * Storage::putFile() automatically unique filename তৈরি করে।
     * 'attachments' folder এ file রাখা হচ্ছে।
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    private function handleFileUpload(Request $request, array $data): array
    {
        $file = $request->file('attachment');

        // File storage এ সংরক্ষণ করা
        // putFile() একটি unique filename তৈরি করে
        $path = Storage::putFile('attachments', $file);

        $data['attachment_path'] = $path;
        $data['attachment_name'] = $file->getClientOriginalName();

        return $data;
    }

    /**
     * পুরাতন attachment file মুছে ফেলে।
     *
     * @param Task $task
     * @return void
     */
    private function deleteOldAttachment(Task $task): void
    {
        if ($task->hasAttachment()) {
            Storage::delete($task->attachment_path);
        }
    }
}
