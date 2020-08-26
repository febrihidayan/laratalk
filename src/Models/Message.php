<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

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

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

}
