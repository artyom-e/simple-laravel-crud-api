<?php

declare(strict_types=1);

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Database\Factories\TaskListFactory factory($count = null, $state = [])
 * @method static Builder<static>|TaskList whereCreatedAt($value)
 * @method static Builder<static>|TaskList whereDeletedAt($value)
 * @method static Builder<static>|TaskList whereDescription($value)
 * @method static Builder<static>|TaskList whereId($value)
 * @method static Builder<static>|TaskList whereName($value)
 * @method static Builder<static>|TaskList whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TaskList extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
