<?php

namespace Modules\UserStatus\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\UserStatus\Entities\UserStatus;

class UserStatusObserver
{
    /**
     * Handle the UserStatus "created" event.
     *
     * @param UserStatus  $userStatus
     * @return void
     */
    public function created(UserStatus $userStatus)
    {
        Cache::tags(['list-userStatus'])->flush();
    }

    /**
     * Handle the UserStatus "updated" event.
     *
     * @param  UserStatus  $userStatus
     * @return void
     */
    public function updated(UserStatus $userStatus)
    {
        Cache::tags(['list-userStatus'])->flush();
    }

    /**
     * Handle the UserStatus "deleted" event.
     *
     * @param  UserStatus  $userStatus
     * @return void
     */
    public function deleted(UserStatus $userStatus)
    {
        Cache::tags(['list-userStatus'])->flush();
    }

    /**
     * Handle the UserStatus "restored" event.
     *
     * @param  UserStatus  $userStatus
     * @return void
     */
    public function restored(UserStatus $userStatus)
    {
        Cache::tags(['list-userStatus'])->flush();
    }

    /**
     * Handle the UserStatus "force deleted" event.
     *
     * @param  UserStatus  $userStatus
     * @return void
     */
    public function forceDeleted(UserStatus $userStatus)
    {
        Cache::tags(['list-userStatus'])->flush();
    }
}
