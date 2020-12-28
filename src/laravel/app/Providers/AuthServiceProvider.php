<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Transaction;
use App\Policies\AccountPolicy;
use App\Policies\BudgetPolice;
use App\Policies\TransactionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Account::class     => AccountPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Budget::class      => BudgetPolice::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
