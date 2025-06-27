<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
public function boot()
{
Broadcast::routes(['middleware' => ['web', 'auth']]);

    require base_path('routes/channels.php');
}

}
