<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Task Model
 *
 * এই Model টি tasks টেবিলের সাথে সম্পর্কিত।
 * প্রতিটি Task একজন User এর অধীনে থাকে (BelongsTo relationship)।
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string $status
 * @property string|null $attachment_path
 * @property string|null $attachment_name
 * @property string|null $due_date
 *
 * @see https://laravel.com/docs/eloquent
 */
class Task extends Model
{
    /**
     * Mass Assignment এ কোন কোন field গুলো fillable হবে তা নির্ধারণ করে।
     *
     * Mass Assignment হলো একসাথে অনেক field update করা।
     * উদাহরণ: Task::create($request->all())
     *
     * Security এর জন্য শুধুমাত্র নির্দিষ্ট field গুলোই fillable রাখা উচিত।
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'attachment_path',
        'attachment_name',
        'due_date',
    ];

    /**
     * Attribute Casting - database থেকে আসা data কে PHP type এ convert করে।
     *
     * এখানে due_date কে date type এ cast করা হয়েছে,
     * যাতে Carbon instance হিসেবে ব্যবহার করা যায়।
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /**
     * Task ও User এর মধ্যে সম্পর্ক নির্ধারণ করে।
     *
     * এটি একটি BelongsTo relationship।
     * অর্থাৎ, প্রতিটি Task একজন নির্দিষ্ট User এর অধীনে।
     *
     * ব্যবহার: $task->user->name
     *
     * @return BelongsTo<User, Task>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Task এর status গুলোর তালিকা।
     *
     * Static method হিসেবে রাখা হয়েছে যাতে
     * Controller বা View থেকে সহজে access করা যায়।
     *
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            'pending' => 'বাকি আছে',
            'in_progress' => 'চলমান',
            'completed' => 'সম্পন্ন',
        ];
    }

    /**
     * Task এ কোনো file attachment আছে কিনা পরীক্ষা করে।
     *
     * @return bool
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }
}
