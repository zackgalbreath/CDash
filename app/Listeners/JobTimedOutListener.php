<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobTimedOut;
use Illuminate\Support\Facades\Log;


/**
 * Log when a job times out.
 */
class JobTimedOutListener
{
    public function handle(JobTimedOut $event): void
    {
        Log::warning("Job {$event->job->getName()} timed out after {$event->job->timeout()} seconds for connection {$event->connectionName}");
    }
}
