<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TaskFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property bool $is_completed
 * @method static TaskFactory factory($count = null, $state = [])
 * @method static Builder<static>|Task newModelQuery()
 * @method static Builder<static>|Task newQuery()
 * @method static Builder<static>|Task onlyTrashed()
 * @method static Builder<static>|Task query()
 * @method static Builder<static>|Task whereCompletedAt($value)
 * @method static Builder<static>|Task whereCreatedAt($value)
 * @method static Builder<static>|Task whereDeletedAt($value)
 * @method static Builder<static>|Task whereDescription($value)
 * @method static Builder<static>|Task whereId($value)
 * @method static Builder<static>|Task whereName($value)
 * @method static Builder<static>|Task whereUpdatedAt($value)
 * @method static Builder<static>|Task withTrashed()
 * @method static Builder<static>|Task withoutTrashed()
 * @property-read TaskList|null $taskList
 * @property int $task_list_id
 * @method static Builder<static>|Task whereTaskListId($value)
 * @mixin Eloquent
 */
class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'completed_at',
        'is_completed',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => ($attributes['completed_at'] ?? null) !== null,
            set: function (bool $value, array $attributes) {
                if ($value && $attributes['completed_at'] === null) {
                    return ['completed_at' => now()];
                }
                if (!$value) {
                    return ['completed_at' => null];
                }

                return [];
            },
        );
    }

    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }
}
