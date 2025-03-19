<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Task;
use App\Models\TaskList;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskRelatesToList
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $list = $request->route('list');
        $task = $request->route('task');
        if ($list instanceof TaskList && $task instanceof Task && $task->task_list_id !== $list->id) {
            return response()->json(['message' => 'Task does not belong to list.'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
