<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\todos;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateTodoFromComment implements ShouldQueue
{
    use Queueable;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentCreated $event): void
    {
        todos::create([
            'name' => 'New Comment Todo',
            'work' => 'The comment is ' . $event->comment->comment,
            'due_date' => now(),
        ]);
    }
}
