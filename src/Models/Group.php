<?php

namespace FebriHidayan\Laratalk\Models;

use FebriHidayan\Laratalk\Config;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
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
        'name', 'description', 'avatar', 'user_id',
        'pinned_message_id'
    ];

    /**
     * Get role member
     * 
     * @var number
     */
    public const MEMBER = 0;

    /**
     * Get role admin
     * 
     * @var number
     */
    public const ADMIN = 1;

    /**
     * Get all role
     * 
     * @var array
     */
    public const ROLE = [
        self::MEMBER,
        self::ADMIN
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::groups();
    }

    public function scopeJoinGroupUser($query)
    {
        return $query->leftJoin(
            Config::groupUser(),
            "{$this->table}.id",
            '=',
            Config::groupUser('group_id')
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            Config::userModel(),
            GroupUser::class,
            'group_id',
            'user_id',
        )
        // to retrieve role column data
        ->select('*');
    }
}
