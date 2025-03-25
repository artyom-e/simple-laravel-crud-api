<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\TaskList;
use App\Models\User;

class TaskListPolicy
{
    private function isUserOwner(User $user, TaskList $taskList): bool
    {
        return $taskList->user_id === $user->getKey();
    }

    public function view(User $user, TaskList $taskList): bool
    {
        return $this->isUserOwner($user, $taskList);
    }

    public function update(User $user, TaskList $taskList): bool
    {
        return $this->isUserOwner($user, $taskList);
    }

    public function delete(User $user, TaskList $taskList): bool
    {
        return $this->isUserOwner($user, $taskList);
    }

    public function viewTasks(User $user, TaskList $taskList): bool
    {
        return $this->isUserOwner($user, $taskList);
    }

    public function storeTask(User $user, TaskList $taskList): bool
    {
        return $this->isUserOwner($user, $taskList);
    }
}
