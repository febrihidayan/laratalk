<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;

class MessageMeta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laratalk_message_meta';

    /**
     * The primary key for the model.
     *
     * @var null
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id', 'to_id', 'accept_at', 'read_at', 'pinned'
    ];
}
