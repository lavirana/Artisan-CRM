<?php

namespace App\Jobs;

use App\Models\Note;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessActivityLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Note $note) {}

    public function handle(): void
    {
        // Place heavy background tasks here
        // E.g., Compiling activity analytics matrices or triggering external webhooks
        logger("Background processing completed for Note ID: {$this->note->id}");
    }
}