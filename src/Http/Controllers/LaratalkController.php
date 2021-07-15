<?php

namespace Laratalk\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laratalk\Events\Messages\StatusEvent;
use Laratalk\Laratalk;
use Laratalk\Models\Message;

class LaratalkController extends Controller
{
    public function __invoke()
    {
        $messages = Message::joinMeta()
            ->where('laratalk_message_meta.to_id', Auth::id())
            ->whereNull('laratalk_message_meta.accept_at');

        if ($messages->count()) {

            foreach ($messages->get()->unique('from_id') as $item) {

                $idMessages = $messages->get()
                    ->where('from_id', $item->from_id)
                    ->pluck('id');

                StatusEvent::dispatch(
                    [
                        'id' => $idMessages,
                        'content_to' => $item->to_id,
                        'status' => Message::ACCEPT
                    ],
                    $item->from_id
                );
                
            }

            $messages->update([
                'accept_at' => now()
            ]);

        }

        return view('laratalk::layout', [
            'scripts' => Laratalk::scriptBuild()
        ]);
    }
}
