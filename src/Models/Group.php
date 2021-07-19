<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laratalk_groups';

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

    public function scopeJoinGroupUser($query)
    {
        return $query->leftJoin(
            'laratalk_group_user',
            'laratalk_groups.id',
            '=',
            'laratalk_group_user.group_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            \Illuminate\Foundation\Auth\User::class,
            GroupUser::class,
            'group_id',
            'user_id',
        )->select('*');
    }
}
