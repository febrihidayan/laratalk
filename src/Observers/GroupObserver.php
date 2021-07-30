<?php

namespace Laratalk\Observers;

use Laratalk\Models\Group;

class GroupObserver
{
    /**
     * Handle the Group "created" event.
     *
     * @param  \Laratalk\Models\Group  $group
     * @return void
     */
    public function created(Group $group)
    {
        //
    }

    /**
     * Handle the Group "updated" event.
     *
     * @param  \Laratalk\Models\Group  $group
     * @return void
     */
    public function updated(Group $group)
    {
        //
    }

    /**
     * Handle the Group "deleted" event.
     *
     * @param  \Laratalk\Models\Group  $group
     * @return void
     */
    public function deleted(Group $group)
    {
        //
    }
}