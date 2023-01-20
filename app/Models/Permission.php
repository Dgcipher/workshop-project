<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property int $role_id
 * @property string $privacy
 * @property array $capabilities
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCapabilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use HasFactory;

    protected $casts = [
        'capabilities' => 'array'
    ];

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
