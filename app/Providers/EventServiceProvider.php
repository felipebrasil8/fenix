<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [        

        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedEventListener',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogLoginEventListener',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogLogoutEventListener',
        ],
        'App\Events\NotificacaoEvent' => [
            'App\Listeners\NotificacaoCreatedEventListener',
         ],
         'Prettus\Repository\Events\RepositoryEntityUpdated' => [
            'App\Listeners\NotificacaoCreatedEventListener',
        ],
         'Prettus\Repository\Events\RepositoryEntityCreated' => [
            'App\Listeners\NotificacaoCreatedEventListener',
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
    }
}
