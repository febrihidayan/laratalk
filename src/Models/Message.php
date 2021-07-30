<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Laratalk\Laratalk;

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
        'by_id', 'group_id', 'content', 'type', 'parent_id'
    ];

    /**
     * Type chat
     * 
     * @var number
     */
    public const CHAT = 0;

    /**
     * Type create group
     * 
     * @var number
     */
    public const CREATE_GROUP = 1;

    /**
     * Type change group avatar
     * 
     * @var number
     */
    public const AVATAR_GROUP = 2;

    /**
     * Type rename group
     * 
     * @var number
     */
    public const RENAME_GROUP = 3;

    /**
     * Type change group description
     * 
     * @var number
     */
    public const DESCRIPTION_GROUP = 4;

    /**
     * Type change group info for all
     * 
     * @var number
     */
    public const INFO_ALL_GROUP = 5;

    /**
     * Type change group info for admin
     * 
     * @var number
     */
    public const INFO_ADMIN_GROUP = 6;

    /**
     * Type change group chat for all
     * 
     * @var number
     */
    public const CHAT_ALL_GROUP = 7;

    /**
     * Type change group chat for admin
     * 
     * @var number
     */
    public const CHAT_ADMIN_GROUP = 8;

    /**
     * Type add_user group
     * 
     * @var number
     */
    public const ADD_USER_GROUP = 9;

    /**
     * Type remove_user group
     * 
     * @var number
     */
    public const REMOVE_USER_GROUP = 10;

    /**
     * Type add_admin group
     * 
     * @var number
     */
    public const ADD_ADMIN_GROUP = 11;

    /**
     * Type remove_admin group
     * 
     * @var number
     */
    public const REMOVE_ADMIN_GROUP = 12;

    /**
     * Type user leave group
     * 
     * @var number
     */
    public const LEAVE_GROUP = 13;

    /**
     * User type for message
     * 
     * @var string
     */
    public const TYPE_USER = 'user';

    /**
     * Group type for message
     * 
     * @var string
     */
    public const TYPE_GROUP = 'group';

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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'chat_type' => 'string',
    ];

    /**
     * Get the type chat message.
     *
     * @return string
     */
    public function getChatTypeAttribute()
    {
        if ($this->group_id) {
            return self::TYPE_GROUP;
        }

        return self::TYPE_USER;
    }

    public function chatType()
    {
        if ($this->group_id) {
            return self::TYPE_GROUP;
        }

        return self::TYPE_USER;
    }

    public function lastTime(): string
    {
        return Laratalk::lastTime($this->created_at, true);
    }

    public function statusMessageRecipient()
    {
        $data = (
            $this->chatType() == self::TYPE_GROUP
        )
            ? $this->recipients[0] : $this->recipient;

        if ($data->read_at) {
            return self::READ;
        }

        if ($data->accept_at) {
            return self::ACCEPT;
        }

        return self::SEND;
    }

    public function time(): string
    {
        return $this->created_at->format('H.i');
    }

    public function readCount(): int
    {
        return $this->joinRecipientUser()
            ->where([
                ['laratalk_messages.type', self::CHAT],
                ['laratalk_messages.by_id', $this->id],
                ['laratalk_message_recipient.message_id', $this->message_id],
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

    public function scopeJoinRecipient($query)
    {
        return $query->leftJoin(
            'laratalk_message_recipient',
            'laratalk_message_recipient.message_id',
            '=',
            'laratalk_messages.id'
        );
    }

    public function scopeJoinRecipientUser($query)
    {
        return $query->joinRecipient()
            ->leftJoin(
                'users',
                'laratalk_message_recipient.to_id',
                '=',
                'users.id'
            );
    }

    public function scopeJoinRecipientUserOrMessageUser($query)
    {
        return $query->joinRecipient()
            ->leftJoin(
                'users',
                function ($join) {
                    $join
                        ->on(
                            'laratalk_message_recipient.to_id',
                            '=',
                            'users.id'
                        )
                        ->orOn(
                            'laratalk_messages.by_id',
                            '=',
                            'users.id'
                        );
                }
            );
    }

    public function scopeJoinGroup($query)
    {
        return $query->leftJoin(
            'laratalk_groups',
            'laratalk_messages.group_id',
            '=',
            'laratalk_groups.id'
        );
    }

    public function scopeJoinUser($query)
    {
        return $query->leftJoin(
            'users',
            'laratalk_messages.by_id',
            '=',
            'users.id'
        );
    }

    public function recipient()
    {
        return $this->hasOne(
            MessageRecipient::class,
            'message_id'
        );
    }

    public function recipients()
    {
        return $this->hasMany(
            MessageRecipient::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'by_id'
        );
    }

}
