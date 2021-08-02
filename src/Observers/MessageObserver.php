<?php

namespace FebriHidayan\Laratalk\Observers;

use FebriHidayan\Laratalk\Models\Message;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     *
     * @param  \Laratalk\Models\Message  $message
     * @return void
     */
    public function created(Message $message)
    {
        //
    }

    /**
     * Handle the Message "updated" event.
     *
     * @param  \Laratalk\Models\Message  $message
     * @return void
     */
    public function updated(Message $message)
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     *
     * @param  \Laratalk\Models\Message  $message
     * @return void
     */
    public function deleted(Message $message)
    {
        //
    }
}
