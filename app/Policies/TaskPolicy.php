<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    private function isUserOwner(User $user, Task $task): bool
    {
        return !$task->taskList || $task->taskList->user_id === $user->getKey();
    }

    public function view(User $user, Task $task): bool
    {
        return $this->isUserOwner($user, $task);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isUserOwner($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isUserOwner($user, $task);
    }
}
