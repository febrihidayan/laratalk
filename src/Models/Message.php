<?php

namespace FebriHidayan\Laratalk\Models;

use FebriHidayan\Laratalk\Config;
use FebriHidayan\Laratalk\Laratalk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'by_id', 'group_id', 'content',
        'type', 'parent_id', 'delete_user_id'
    ];

    /**
     * Type chat
     * 
     * @var number
     */
    public const CHAT = 0;

    /**
     * Type pull message
     * 
     * @var number
     */
    public const PULL_MESSAGE = 1;

    /**
     * Type create group
     * 
     * @var number
     */
    public const CREATE_GROUP = 2;

    /**
     * Type change group avatar
     * 
     * @var number
     */
    public const AVATAR_GROUP = 3;

    /**
     * Type rename group
     * 
     * @var number
     */
    public const RENAME_GROUP = 4;

    /**
     * Type change group description
     * 
     * @var number
     */
    public const DESCRIPTION_GROUP = 5;

    /**
     * Type change group info for all
     * 
     * @var number
     */
    public const INFO_ALL_GROUP = 6;

    /**
     * Type change group info for admin
     * 
     * @var number
     */
    public const INFO_ADMIN_GROUP = 7;

    /**
     * Type change group chat for all
     * 
     * @var number
     */
    public const CHAT_ALL_GROUP = 8;

    /**
     * Type change group chat for admin
     * 
     * @var number
     */
    public const CHAT_ADMIN_GROUP = 9;

    /**
     * Type add_user group
     * 
     * @var number
     */
    public const ADD_USER_GROUP = 10;

    /**
     * Type remove_user group
     * 
     * @var number
     */
    public const REMOVE_USER_GROUP = 11;

    /**
     * Type add_admin group
     * 
     * @var number
     */
    public const ADD_ADMIN_GROUP = 12;

    /**
     * Type remove_admin group
     * 
     * @var number
     */
    public const REMOVE_ADMIN_GROUP = 13;

    /**
     * Type user leave group
     * 
     * @var number
     */
    public const LEAVE_GROUP = 14;

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
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::messages();
    }

    /**
     * Get the type chat message.
     *
     * @return string
     */
    public function chatType(): string
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

    public function statusMessageRecipient(): string
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
                [Config::messages('type'), self::CHAT],
                [Config::messages('by_id'), $this->id],
                [Config::messageRecipient('message_id'), $this->message_id],
                [Config::messageRecipient('to_id'), Auth::id()]
            ])
            ->whereNull(Config::messageRecipient('read_at'))->count();
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
                $q->where(Config::messages('by_id'), Auth::id())
                    ->orWhere(Config::messageRecipient('to_id'), Auth::id());
            });
    }

    public function scopeWhereMetaUser($query, $userId, $orWhere = false)
    {
        $fields = [
            [Config::messages('by_id'), $orWhere ? $userId : Auth::id()],
            [Config::messageRecipient('to_id'), !$orWhere ? $userId : Auth::id()]
        ];

        if ($orWhere) {
            return $query->orWhere($fields);
        }

        return $query->where($fields);
    }

    public function scopeJoinRecipient($query)
    {
        return $query->leftJoin(
            Config::messageRecipient(),
            Config::messageRecipient('message_id'),
            '=',
            Config::messages('id')
        );
    }

    public function scopeJoinRecipientUser($query)
    {
        return $query->joinRecipient()
            ->leftJoin(
                Config::users(),
                Config::messageRecipient('to_id'),
                '=',
                Config::users('id')
            );
    }

    public function scopeJoinRecipientUserOrMessageUser($query)
    {
        return $query->joinRecipient()
            ->leftJoin(
                Config::users(),
                function ($join) {
                    $join
                        ->on(
                            Config::messageRecipient('to_id'),
                            '=',
                            Config::users('id')
                        )
                        ->orOn(
                            Config::messages('by_id'),
                            '=',
                            Config::users('id')
                        );
                }
            );
    }

    public function scopeJoinGroup($query)
    {
        return $query->leftJoin(
            Config::groups(),
            Config::messages('group_id'),
            '=',
            Config::groups('id')
        );
    }

    public function scopeJoinGroupUser($query)
    {
        return $query->leftJoin(
            Config::groupUser(),
            Config::groups('id'),
            '=',
            Config::groupUser('group_id')
        );
    }

    public function scopeJoinUser($query)
    {
        return $query->leftJoin(
            Config::users(),
            Config::messages('by_id'),
            '=',
            Config::users('id')
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
            Config::userModel(),
            'by_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            Config::userModel(),
            Config::messageRecipient(),
            'message_id',
            'to_id'
        );
    }

}
