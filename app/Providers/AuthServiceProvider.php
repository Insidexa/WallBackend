<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        \Gate::define('update-comment', function ($user, $commentUserId) {
            return $user->id == $commentUserId;
        });

        \Gate::define('delete-comment', function ($user, $commentUserId) {
            return $user->id == $commentUserId;
        });

        \Gate::define('update-post', function ($user, $postUserId) {
            return $user->id == $postUserId;
        });

        \Gate::define('delete-post', function ($user, $postUserId) {
            return $user->id == $postUserId;
        });
    }
}
