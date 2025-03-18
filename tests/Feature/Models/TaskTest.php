<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testGetIsCompletedTrue(): void
    {
        $task = Task::factory()->completed()->create();
        $this->assertNotNull($task->completed_at);
        $this->assertTrue($task->is_completed);
    }

    public function testGetIsCompletedFalse(): void
    {
        $task = Task::factory()->incompleted()->create();
        $this->assertNull($task->completed_at);
        $this->assertFalse($task->is_completed);
    }

    public function testSetIsCompletedTrue(): void
    {
        $task = Task::factory()->incompleted()->create();
        $this->assertNull($task->completed_at);
        $task->update(['is_completed' => true]);
        $this->assertNotNull($task->completed_at);
        $this->assertTrue($task->is_completed);
    }

    public function testSetIsCompletedFalse(): void
    {
        $task = Task::factory()->completed()->create();
        $this->assertNotNull($task->completed_at);
        $task->update(['is_completed' => false]);
        $this->assertNull($task->completed_at);
        $this->assertFalse($task->is_completed);
    }
}
