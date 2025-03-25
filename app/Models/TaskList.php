<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TaskListFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 * @method static Builder<static>|TaskList newModelQuery()
 * @method static Builder<static>|TaskList newQuery()
 * @method static Builder<static>|TaskList onlyTrashed()
 * @method static Builder<static>|TaskList query()
 * @method static Builder<static>|TaskList withTrashed()
 * @method static Builder<static>|TaskList withoutTrashed()
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static TaskListFactory factory($count = null, $state = [])
 * @method static Builder<static>|TaskList whereCreatedAt($value)
 * @method static Builder<static>|TaskList whereDeletedAt($value)
 * @method static Builder<static>|TaskList whereDescription($value)
 * @method static Builder<static>|TaskList whereId($value)
 * @method static Builder<static>|TaskList whereName($value)
 * @method static Builder<static>|TaskList whereUpdatedAt($value)
 * @property int $user_id
 * @property-read User $user
 * @method static Builder<static>|TaskList whereUserId($value)
 * @mixin Eloquent
 */
class TaskList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
