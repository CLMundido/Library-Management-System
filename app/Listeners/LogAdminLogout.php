<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Logout;
use Spatie\Activitylog\Models\Activity;

class LogAdminLogout
{
    public function handle(Logout $event): void{$user = $event->user;

        $user = $event->user;

        if ($user instanceof \App\Models\User && $user->hasRole('user')) {
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User logged out');
        } elseif ($user instanceof \App\Models\User && $user->hasRole('admin')) {
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Admin logged out');
        }
    }
}