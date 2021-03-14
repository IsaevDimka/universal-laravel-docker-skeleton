<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\UserLoginActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class UserLoginActivityStoreJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected int $user_id;

    protected string $ip_address;

    protected string $user_agent;

    /**
     * Create a new job instance.
     */
    public function __construct(int $user_id, string $ip_address, string $user_agent)
    {
        $this->user_id = $user_id;
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $current_login_activity = UserLoginActivity::query()->where('user_id', '=', $this->user_id)->where(
            'ip_address',
            '=',
            $this->ip_address
        )->where('user_agent', '=', $this->user_agent)->first();

        if ($current_login_activity) {
            $current_login_activity->update([
                'is_current' => true,
            ]);
            $current_login_activity->touch();
        } else {
            // parse user agent
            $agent = (new Agent());
            $agent->setUserAgent($this->user_agent);

            // parse ip_address
            $geoip = geoip($this->ip_address);

            $current_login_activity = UserLoginActivity::create([
                'user_id' => $this->user_id,
                'ip_address' => $this->ip_address,
                'geoip' => $geoip->toArray(),
                'user_agent' => $this->user_agent,
                'parse_user_agent' => [
                    'device' => $agent->device(),
                    'browser' => $agent->browser(),
                    'deviceType' => $agent->deviceType(),
                    'isDesktop' => $agent->isDesktop(),
                    'isMobile' => $agent->isMobile(),
                    'platform' => $agent->platform(),
                ],
                'is_current' => true,
            ]);
        }

        // rollback is_current
        UserLoginActivity::query()->where('user_id', '=', $this->user_id)
            ->whereNotIn('id', [$current_login_activity->id])
            ->update([
                'is_current' => false,
            ]);
    }
}
