<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
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
        'from_id', 'to_id', 'group_id', 'content', 'read_at',
        'pinned', 'parent_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
        'pinned' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'read_at',
    ];

    public const SEND = 'send';

    public const ACCEPT = 'accept';

    public const READ = 'read';

    public function readCount(): int
    {
        return $this->joinMeta()
            ->join('users', 'laratalk_message_meta.to_id', '=', 'users.id')
            ->where([
                ['laratalk_messages.from_id', $this->id],
                ['laratalk_message_meta.to_id', Auth::id()]
            ])
            ->whereNull('laratalk_message_meta.read_at')->count();
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
                $q->where('laratalk_messages.from_id', Auth::id())
                    ->orWhere('laratalk_message_meta.to_id', Auth::id());
            });
    }

    public function scopeWhereMetaUser($query, $userId, $orWhere = false)
    {
        $fields = [
            ['from_id', $orWhere ? $userId : Auth::id()],
            ['laratalk_message_meta.to_id', !$orWhere ? $userId : Auth::id()]
        ];

        if ($orWhere) {
            return $query->orWhere($fields);
        }

        return $query->where($fields);
    }

    public function scopeJoinMeta($query)
    {
        return $query->join(
            'laratalk_message_meta',
            'laratalk_message_meta.message_id',
            '=',
            'laratalk_messages.id'
        );
    }

    // TODO: Will be used for group messages
    public function scopeJoinMetaUser($query)
    {
        return $query->join(
            'users',
            'laratalk_message_meta.to_id',
            '=',
            'users.id'
        );
    }

    public function scopeJoinUser($query)
    {
        return $query->joinMeta()
            ->join('users',  function ($join) {
                $join->on('laratalk_messages.from_id', '=', 'users.id')
                    ->orOn('laratalk_message_meta.to_id', '=', 'users.id');
            });
    }

}
