<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Install\Http\Controllers\InstallController;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     * 确定是否应用自动发现事件和监听器。
     *
     * @return bool
     */
    public function shouldDiscoverEvents() {
//        return false;
        return true;
    }

    /**
     * 获取应用于发现事件的监听器目录。
     *
     * @return array
     */
    protected function discoverEventsWithin() {
        return InstallController::listenersDirList();
    }
}
