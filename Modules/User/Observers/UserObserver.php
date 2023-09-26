<?php

namespace Modules\User\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\User\Entities\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User  $user
     * @return void
     */
    public function created(User $user)
    {
        Cache::tags(['list-users'])->flush();
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Cache::tags(['list-users'])->flush();
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Cache::tags(['list-users'])->flush();
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  User  $user
     * @return void
     */
    public function restored(User $user)
    {
        Cache::tags(['list-users'])->flush();
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        Cache::tags(['list-users'])->flush();
    }
}
