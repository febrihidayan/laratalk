<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laratalk_messages';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'by_id', 'group_id', 'content'
    ];

    /**
     * Type message
     * 
     * @var number
     */
    public const MESSAGE = 0;

    /**
     * Type add_user group
     * 
     * @var number
     */
    public const ADD_USER = 1;

    /**
     * Type remove_user group
     * 
     * @var number
     */
    public const REMOVE_USER = 2;

    /**
     * Type add_admin group
     * 
     * @var number
     */
    public const ADD_ADMIN = 3;

    /**
     * Type remove_admin group
     * 
     * @var number
     */
    public const REMOVE_ADMIN = 4;

    /**
     * Status send message
     * 
     * @var string
     */
    public const SEND = 'send';

    /**
     * Status accept message
     * 
     * @var string
     */
    public const ACCEPT = 'accept';

    /**
     * Status read message
     * 
     * @var string
     */
    public const READ = 'read';

    public function readCount(): int
    {
        return $this->joinMeta()
            ->join('users', 'laratalk_message_recipient.to_id', '=', 'users.id')
            ->where([
                ['laratalk_messages.by_id', $this->id],
                ['laratalk_message_recipient.to_id', Auth::id()]
            ])
            ->whereNull('laratalk_message_recipient.read_at')->count();
    }

    public function statusMessage(): string
    {
        if ($this->read_at) {
            return self::READ;
        }

        if ($this->accept_at) {
            return self::ACCEPT;
        }

        return self::SEND;
    }

    public function scopeAuthUser($query)
    {
        return $query->where(function ($q) {
                $q->where('laratalk_messages.by_id', Auth::id())
                    ->orWhere('laratalk_message_recipient.to_id', Auth::id());
            });
    }

    public function scopeWhereMetaUser($query, $userId, $orWhere = false)
    {
        $fields = [
            ['by_id', $orWhere ? $userId : Auth::id()],
            ['laratalk_message_recipient.to_id', !$orWhere ? $userId : Auth::id()]
        ];

        if ($orWhere) {
            return $query->orWhere($fields);
        }

        return $query->where($fields);
    }

    public function scopeJoinMeta($query)
    {
        return $query->join(
            'laratalk_message_recipient',
            'laratalk_message_recipient.message_id',
            '=',
            'laratalk_messages.id'
        );
    }

    // TODO: Will be used for group messages
    public function scopeJoinMetaUser($query)
    {
        return $query->join(
            'users',
            'laratalk_message_recipient.to_id',
            '=',
            'users.id'
        );
    }

    public function scopeJoinUser($query)
    {
        return $query->joinMeta()
            ->join('users',  function ($join) {
                $join->on('laratalk_messages.by_id', '=', 'users.id')
                    ->orOn('laratalk_message_recipient.to_id', '=', 'users.id');
            });
    }

}
