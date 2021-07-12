<?php

namespace Laratalk\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laratalk_group_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image', 'user_id'
    ];
}
