<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laratalk_message_recipient';

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
        'message_id', 'to_id', 'accept_at', 'read_at',
        'pinned_by', 'pinned_to'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'accept_at' => 'datetime',
        'read_at' => 'datetime',
        'pinned_by' => 'boolean',
        'pinned_to' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'accept_at',
        'read_at'
    ];
}
