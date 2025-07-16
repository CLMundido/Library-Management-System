<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogAdminLogin
{
    public function handle(Login $event): void{$user = $event->user;

        $user = $event->user;

        if ($user instanceof \App\Models\User && $user->hasRole('user')) {
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User logged in');
        } elseif ($user instanceof \App\Models\User && $user->hasRole('admin')) {
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Admin logged in');
        }
    }
}