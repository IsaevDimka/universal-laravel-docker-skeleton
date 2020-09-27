<?php

namespace App\Jobs;

use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserUpdateLastVisitAt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $maxExceptions = 1;

    protected int $user_id;

    /**
     * Create a new job instance.
     *
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        UserService::instance()->updateLastVisitAt($this->user_id);
    }
}
