<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    use HasFactory;

//    protected $casts = [
//        'capabilities' => 'array'
//    ];

    protected $fillable = [
        'role_id',
        'privacy',
        'capabilities'
    ];

    public function role():BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
