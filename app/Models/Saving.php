<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use BelongsToUser;

    protected $fillable = [
        'uuid', 'user_id', 'goal_name', 'target_amount', 
        'current_amount', 'target_date', 'status', 'color'
    ];
}